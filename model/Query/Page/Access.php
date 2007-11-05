<?php
/**
 * Access.php
 *
 * Contains the class Query_Page_Access
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Access.php,v 1.3 2007/11/05 15:56:58 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../Page.php");

/**
 * Query_Page_Access data access component for application users accesses
 *
 * Methods:
 *  bool Query_Page_Access(array $dsn = null)
 *  mixed select(int $year = 0, int $month = 0, int $day = 0, int $hour = 0)
 *  bool searchUser(int $idUser, int $page, int $limitFrom = 0)
 *  mixed fetch(void)
 *  bool insert(User $user)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.3
 */
class Query_Page_Access extends Query_Page
{
  /**
   * bool Query_Page_Access(array $dsn = null)
   *
   * Constructor function
   *
   * @param array $dsn (optional) Data Source Name
   * @return boolean returns false, if error occurs
   * @access public
   */
  function Query_Page_Access($dsn = null)
  {
    $this->_table = "access_log_tbl";

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
    $sql = "SELECT login,access_date,id_profile";
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
   * @since 0.7
   */
  function searchUser($idUser, $page, $limitFrom = 0)
  {
    parent::_resetStats($page);

    $sql = " FROM " . $this->_table;
    $sql .= " WHERE id_user=" . intval($idUser);

    $sqlCount = "SELECT COUNT(*) AS row_count" . $sql;

    $sql = "SELECT login,access_date,id_profile" . $sql;
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
   * @return array returns access log or false if no more logs to fetch
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

    $access = array();
    $access["login"] = urldecode($array["login"]);
    $access["access_date"] = urldecode($array["access_date"]);
    $access["id_profile"] = intval($array["id_profile"]);

    return $access;
  }

  /**
   * bool insert(User $user)
   *
   * Inserts a new application user access into the database.
   *
   * @param User $user application user data
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($user)
  {
    $sql = "INSERT INTO " . $this->_table;
    $sql .= " (id_user, login, access_date, id_profile) VALUES (";
    $sql .= $user->getIdUser() . ", ";
    $sql .= "'" . urlencode($user->getLogin()) . "', ";
    $sql .= "NOW(), ";
    $sql .= $user->getIdProfile() . ");";

    return $this->exec($sql);
  }
} // end class
?>
