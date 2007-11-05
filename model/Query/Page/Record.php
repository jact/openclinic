<?php
/**
 * Record.php
 *
 * Contains the class Query_Page_Record
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Record.php,v 1.4 2007/11/05 15:56:58 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../Page.php");

/**
 * Query_Page_Record data access component for record logs
 *
 * Methods:
 *  bool Query_Page_Record(array $dsn = null)
 *  mixed select(int $year = 0, int $month = 0, int $day = 0, int $hour = 0)
 *  bool searchUser(int $idUser, int $page, int $limitFrom = 0)
 *  mixed fetch(void)
 *  bool insert(int $idUser, string $login, string $tableName, string $operation, string $affectedRow)
 *  void log(string $class, string, $operation, array $key, string $method = "select")
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.3
 */
class Query_Page_Record extends Query_Page
{
  /**
   * bool Query_Page_Record(array $dsn = null)
   *
   * Constructor function
   *
   * @param array $dsn (optional) Data Source Name
   * @return boolean returns false, if error occurs
   * @access public
   */
  function Query_Page_Record($dsn = null)
  {
    $this->_table = "record_log_tbl";

    return parent::Query($dsn);
  }

  /**
   * mixed select(int $year = 0, int $month = 0, int $day = 0, int $hour = 0)
   *
   * Executes a query
   *
   * @param int $year (optional)
   * @param int $month (optional)
   * @param int $day (optional)
   * @param int $hour (optional)
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   * @since 0.4
   */
  function select($year = 0, $month = 0, $day = 0, $hour = 0)
  {
    $sql = "SELECT *";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE 1";
    if ($year)
    {
      $sql .= " AND YEAR(access_date)=" . intval($year);
    }
    if ($month)
    {
      $sql .= " AND MONTH(access_date)=" . intval($month);
    }
    if ($day)
    {
      $sql .= " AND DATE_FORMAT(access_date, '%d')=" . intval($day);
    }
    if ($hour)
    {
      $sql .= " AND DATE_FORMAT(access_date, '%H')=" . intval($hour);
    }
    $sql .= " ORDER BY access_date DESC";

    return ($this->exec($sql) ? $this->numRows() : false);
  }

  /**
   * bool searchUser(int $idUser, int $page, int $limitFrom = 0)
   *
   * Executes a query
   *
   * @param int $idUser
   * @param int $page What page should be returned if results are more than one page
   * @param int $limitFrom (optional) maximum number of results
   * @return boolean returns false, if error occurs
   * @access public
   */
  function searchUser($idUser, $page, $limitFrom = 0)
  {
    parent::_resetStats($page);

    $sql = " FROM " . $this->_table;
    //$sql .= " WHERE id_profile=profile_tbl.id_profile";
    $sql .= " WHERE id_user=" . intval($idUser);

    $sqlCount = "SELECT COUNT(*) AS row_count" . $sql;

    $sql = "SELECT *" . $sql;
    $sql .= " ORDER BY access_date DESC";

    // setting limit so we can page through the results
    $offset = ($this->_currentPage - 1) * intval($this->_itemsPerPage);
    if ($offset >= $limitFrom && $limitFrom > 0)
    {
      $offset = 0;
    }
    $limitTo = intval($this->_itemsPerPage);
    if ($limitTo > 0)
    {
      $sql .= " LIMIT " . $offset . "," . $limitTo . ";";
    }

    //Error::debug($limitFrom, "limitFrom"); // debug
    //Error::debug($offset, "offset"); // debug
    //Error::debug($sql, "sql"); // debug

    // Running row count sql statement
    if ( !$this->exec($sqlCount) )
    {
      return false;
    }

    $array = parent::fetchRow();
    parent::_calculateStats($array["row_count"], $limitFrom);
    if ( !$this->getRowCount() )
    {
      return false;
    }

    // Running search sql statement
    return $this->exec($sql);
  }

  /**
   * mixed fetch(void)
   *
   * Fetches a row from the query result and populates an array object.
   *
   * @return array returns record log or false if no more logs to fetch
   * @access public
   * @since 0.4
   */
  function fetch()
  {
    $array = parent::fetchRow();
    if ($array == false)
    {
      return false;
    }

    parent::_incrementRow();

    $record = array();
    $record["login"] = urldecode($array["login"]);
    $record["access_date"] = urldecode($array["access_date"]);
    $record["table_name"] = urldecode($array["table_name"]);
    $record["operation"] = urldecode($array["operation"]);
    $record["affected_row"] = urldecode($array["affected_row"]);

    return $record;
  }

  /**
   * bool insert(int $idUser, string $login, string $tableName, string $operation, string $affectedRow)
   *
   * Inserts a new record log into the database.
   *
   * @param int $idUser key of user that makes the operation
   * @param string $login login of user that makes the operation
   * @param string $tableName
   * @param string $operation one between INSERT, UPDATE, DELETE
   * @param string $affectedRow serialized row data
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($idUser, $login, $tableName, $operation, $affectedRow)
  {
    $sql = "INSERT INTO " . $this->_table;
    $sql .= " (id_user, login, access_date, table_name, operation, affected_row) VALUES (";
    $sql .= intval($idUser) . ", ";
    $sql .= "'" . urlencode($login) . "', ";
    $sql .= "NOW(), ";
    $sql .= "'" . urlencode($tableName) . "', ";
    $sql .= "'" . urlencode($operation) . "', ";
    $sql .= "'" . urlencode($affectedRow) . "')";
    //echo $sql; exit(); // debug

    return $this->exec($sql);
  }

  /**
   * void log(string $class, string, $operation, array $key, string $method = "select")
   *
   * Inserts a new record in log operations table if it is possible
   *
   * @param string $class
   * @param string $operation one between INSERT, UPDATE, DELETE
   * @param array $key primary key of the record
   * @param string $method (optional)
   * @return void
   * @access public
   * @since 0.8
   * @see OPEN_DEMO
   */
  function log($class, $operation, $key, $method = "select")
  {
    if (defined("OPEN_DEMO") && OPEN_DEMO)
    {
      return; // disabled in demo version
    }

    $queryQ = new $class;
    if ( !call_user_func_array(array($queryQ, $method), $key) )
    {
      $queryQ->close();

      return;
    }

    $data = $queryQ->fetchRow(); // obtains an array
    if ( !$data )
    {
      $queryQ->close();
      Error::fetch($queryQ);

      return;
    }

    $data = serialize($data);

    $table = $queryQ->getTableName();
    $queryQ->close();
    unset($queryQ);

    $this->insert($_SESSION['auth']['user_id'], $_SESSION['auth']['login_session'], $table, $operation, $data);
  }
} // end class
?>
