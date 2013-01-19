<?php
/**
 * DbConnection.php
 *
 * Contains the class DbConnection
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: DbConnection.php,v 1.20 2013/01/19 10:25:15 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

if (file_exists(dirname(__FILE__) . "/../config/database_constants.php"))
{
  include_once(dirname(__FILE__) . "/../config/database_constants.php");
}

if ( !defined("OPEN_PERSISTENT") )
{
  define("OPEN_PERSISTENT", true);
}

if ( !defined("OPEN_SQL_DEBUG") )
{
  define("OPEN_SQL_DEBUG", false);
}

/**
 * DbConnection encapsulates all database specific functions for the project
 *
 * Methods:
 *  void DbConnection(array $dsn = null)
 *  bool connect(bool $persistency = OPEN_PERSISTENT)
 *  bool close(void)
 *  bool exec(string $sql, array $params = null)
 *  mixed fetchRow(int $arrayType = MYSQL_ASSOC)
 *  mixed fetchAll(int $arrayType = MYSQL_ASSOC)
 *  mixed affectedRows(void)
 *  mixed lastInsertId(void)
 *  bool resetResult(void)
 *  bool rowSeek(int $row = 0)
 *  int numRows(void)
 *  int numFields(void)
 *  string fieldType(int $index = 0)
 *  bool listTables(void)
 *  string tableName(int $index = 0)
 *  bool listFields(string $table)
 *  string fieldName(int $index = 0)
 *  mixed getLink(void)
 *  string getError(void)
 *  int getDbErrno(void)
 *  string getDbError(void)
 *  string getSQL(void)
 *  bool freeResult(void)
 *  string __toString(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class DbConnection
{
  /**
   * @var string
   * @access private
   */
  private $_host;

  /**
   * @var int
   * @access private
   */
  private $_port;

  /**
   * @var string
   * @access private
   */
  private $_userName;

  /**
   * @var string
   * @access private
   */
  private $_passwd;

  /**
   * @var string
   * @access private
   */
  private $_dbName;

  /**
   * @var mixed
   * @access private
   */
  private $_link;

  /**
   * @var mixed
   * @access private
   */
  private $_result;

  /**
   * @var string
   * @access private
   */
  private $_error;

  /**
   * @var int
   * @access private
   */
  private $_dbErrno;

  /**
   * @var string
   * @access private
   */
  private $_dbError;

  /**
   * @var string
   * @access private
   */
  private $_SQL;

  /**
   * void DbConnection(array $dsn = null)
   *
   * Constructor function
   *
   * @param array $dsn (optional) Data Source Name:
   *  array(
   *    'db' => string,
   *    'user' => string,
   *    'pwd' => string,
   *    'host' => string,
   *    'port' => int
   *  )
   * @return void
   * @access public
   * @since 0.7
   */
  public function DbConnection($dsn = null)
  {
    if ( !isset($dsn['db']) )
    {
      $this->_host = (defined("OPEN_HOST") ? OPEN_HOST : "localhost");
      $this->_userName = (defined("OPEN_USERNAME")) ? OPEN_USERNAME : "root";
      $this->_passwd = (defined("OPEN_PWD")) ? OPEN_PWD : "";
      $this->_dbName = (defined("OPEN_DATABASE")) ? OPEN_DATABASE : "openclinic";
      $this->_port = (defined("OPEN_PORT")) ? OPEN_PORT : 3306;
    }
    else
    {
      $this->_host = isset($dsn['host']) ? $dsn['host'] : '';
      $this->_userName = isset($dsn['user']) ? $dsn['user'] : '';
      $this->_passwd = isset($dsn['pwd']) ? $dsn['pwd'] : '';
      $this->_dbName = isset($dsn['db']) ? $dsn['db'] : '';
      $this->_port = isset($dsn['port']) ? intval($dsn['port']) : 3306;
    }
  }

  /**
   * bool connect(bool $persistency = OPEN_PERSISTENT)
   *
   * Connects to the database
   *
   * @param bool $persistency (optional) persistent connection or not
   * @return boolean returns false, if error occurs
   * @access public
   */
  public function connect($persistency = OPEN_PERSISTENT)
  {
    $this->_link = ($persistency)
      ? mysql_pconnect($this->_host . ":" . $this->_port, $this->_userName, $this->_passwd)
      : mysql_connect($this->_host . ":" . $this->_port, $this->_userName, $this->_passwd, true); // always open new link
    if ($this->_link == false)
    {
      $this->_error = "Unable to connect to host.";
      $this->_dbErrno = mysql_errno();
      $this->_dbError = mysql_error();

      return false;
    }

    $this->_result = mysql_select_db($this->_dbName, $this->_link);
    if ($this->_result == false)
    {
      $this->_error = "Unable to select database.";
      $this->_dbErrno = mysql_errno();
      $this->_dbError = mysql_error();
    }

    return $this->_result;
  }

  /**
   * bool close(void)
   *
   * Closes database connection
   *
   * @return boolean returns false, if error occurs
   * @access public
   */
  public function close()
  {
    $this->_result = mysql_close($this->_link);
    if ($this->_result == false)
    {
      $this->_error = "Unable to close database.";
      $this->_dbErrno = mysql_errno();
      $this->_dbError = mysql_error();
    }

    return $this->_result;
  }

  /**
   * bool exec(string $sql, array $params = null)
   *
   * Executes a query
   *
   * @param string $sql SQL of query to execute
   * @param array $params (optional) SQL parameters to prepare sentence
   * @return boolean returns false, if error occurs
   * @access public
   * @see OPEN_SQL_DEBUG
   */
  public function exec($sql, $params = null)
  {
    if (is_array($params))
    {
      $translations = substr_count($sql, '?');
      $parameters = count($params);
      if ($translations != $parameters)
      {
        $this->_error = "Unable to prepare query.";
        return false;
      }

      $temp = explode('?', $sql);
      $sql = "";
      $i = 0;
      foreach ($temp as $value)
      {
        $sql .= $value;
        if ($i < $parameters)
        {
          switch (gettype($params[$i]))
          {
            case 'NULL':
              $sql .= "NULL";
              break;

            case 'integer':
            case 'double':
              if ($params[$i] == 0)
              {
                $sql .= "NULL";
              }
              else
              {
                $sql .= $params[$i];
              }
              break;

            case 'string':
              if (empty($params[$i]))
              {
                $sql .= "NULL";
              }
              else
              {
                $sql .= "'" . mysql_real_escape_string($params[$i]) . "'"; // PHP >= 4.3.0
              }
              break;
          }
        }
        $i++;
      }
    }

    $this->_SQL = $sql;
    if (OPEN_SQL_DEBUG)
    {
      Error::trace($sql);
    }

    $this->_result = mysql_query($sql, $this->_link);
    if ($this->_result == false)
    {
      $this->_error = "Unable to execute query.";
      $this->_dbErrno = mysql_errno();
      $this->_dbError = mysql_error();
    }

    return $this->_result;
  }

  /**
   * mixed fetchRow(int $arrayType = MYSQL_ASSOC)
   *
   * Returns a resulting array
   *
   * @param int $arrayType (optional) array type to return
   * @return array resulting array. Returns false, if no more rows to fetch.
   * @access public
   */
  public function fetchRow($arrayType = MYSQL_ASSOC)
  {
    if ($this->_result == false)
    {
      $this->_error = "Invalid result. Must execute query first.";
      return false;
    }

    if ($arrayType != MYSQL_ASSOC && $arrayType != MYSQL_NUM && $arrayType != MYSQL_BOTH)
    {
      $arrayType = MYSQL_ASSOC; // default value
    }

    $array = mysql_fetch_array($this->_result, $arrayType);
    if ($array == false)
    {
      $this->_error = "Empty row. No more rows.";
    }

    return $array;
  }

  /**
   * mixed fetchAll(int $arrayType = MYSQL_ASSOC)
   *
   * Returns all records in an array
   *
   * @param int $arrayType (optional) array type to return
   * @return array resulting array. Returns false if there is an empty result
   * @see fetchRow
   * @access public
   */
  public function fetchAll($arrayType = MYSQL_ASSOC)
  {
    if ($this->_result == false)
    {
      $this->_error = "Invalid result. Must execute query first.";
      return false;
    }

    if ( !$this->resetResult() )
    {
      $this->_error = "Invalid result. There is not records.";
      return false;
    }

    $recordset = array();
    while ($row = $this->fetchRow($arrayType))
    {
      $recordset[] = $row;
    }

    return $recordset;
  }

  /**
   * mixed affectedRows(void)
   *
   * Returns number of affected rows by last MySQL sentence
   *
   * @return int number of affected rows or false if an error occurs
   * @access public
   * @since 0.2
   */
  public function affectedRows()
  {
    return ($this->_link ? mysql_affected_rows($this->_link) : false);
  }

  /**
   * mixed lastInsertId(void)
   *
   * Returns last id generated by last INSERT sentence
   *
   * @return false, if no link, int otherwise.
   * @access public
   * @since 0.2
   */
  public function lastInsertId()
  {
    return ($this->_link ? mysql_insert_id($this->_link) : false);
  }

  /**
   * bool resetResult(void)
   *
   * Resets row point to the first row in the resultset
   *
   * @return false, if no more rows to fetch, true otherwise.
   * @access public
   */
  public function resetResult()
  {
    return ($this->_result ? (mysql_data_seek($this->_result, 0) != 0) : false);
  }

  /**
   * bool rowSeek(int $row = 0)
   *
   * Moves row point to the $row number in the resultset
   *
   * @param int $row (optional) row number
   * @return false, if no more rows to fetch, true otherwise.
   * @access public
   * @since 0.2
   */
  public function rowSeek($row = 0)
  {
    return ($this->_result ? (mysql_data_seek($this->_result, $row) != 0) : false);
  }

  /**
   * int numRows(void)
   *
   * Returns the number of rows in the result
   *
   * @return int number of rows in the result
   * @access public
   */
  public function numRows()
  {
    return mysql_num_rows($this->_result);
  }

  /**
   * int numFields(void)
   *
   * Returns the number of fields in the result
   *
   * @return int number of fields in result
   * @access public
   */
  public function numFields()
  {
    return mysql_num_fields($this->_result);
  }

  /**
   * string fieldType(int $index = 0)
   *
   * Returns the type of the $index field in the result
   *
   * @param int $index (optional) index of the field
   * @return string type of the field
   * @access public
   */
  public function fieldType($index = 0)
  {
    return mysql_field_type($this->_result, $index);
  }

  /**
   * bool listTables(void)
   *
   * Lists database tables
   *
   * @return bool false if an error occurs
   * @access public
   */
  public function listTables()
  {
    return (($this->_result = mysql_query("SHOW TABLES", $this->_link)) != 0);
  }

  /**
   * string tableName(int $index = 0)
   *
   * Returns the name of the $index table in the result
   *
   * @param int $index (optional) index of the table
   * @return string name of the table
   * @access public
   */
  public function tableName($index = 0)
  {
    return mysql_tablename($this->_result, $index);
  }

  /**
   * bool listFields(string $table)
   *
   * Lists fields of the result
   *
   * @param string $table name of the table
   * @return bool false if an error occurs
   * @access public
   */
  public function listFields($table)
  {
    return (($this->_result = mysql_query("SHOW COLUMNS FROM " . $table, $this->_link)) != 0);
  }

  /**
   * string fieldName(int $index = 0)
   *
   * Returns the name of the $index field in the result
   *
   * @param int $index (optional) index of the field
   * @return string name of the field
   * @access public
   */
  public function fieldName($index = 0)
  {
    return mysql_field_name($this->_result, $index);
  }

  /**
   * mixed getLink(void)
   *
   * @return mixed connection link or false
   * @access public
   */
  public function getLink()
  {
    return $this->_link;
  }

  /**
   * string getError(void)
   *
   * @return string error message
   * @access public
   */
  public function getError()
  {
    return $this->_error;
  }

  /**
   * int getDbErrno(void)
   *
   * @return int error number returned from database
   * @access public
   */
  public function getDbErrno()
  {
    return $this->_dbErrno;
  }

  /**
   * string getDbError(void)
   *
   * @return string error message returned from database
   * @access public
   */
  public function getDbError()
  {
    return $this->_dbError;
  }

  /**
   * string getSQL(void)
   *
   * @return string SQL used in exec when an error occurs in query execution
   * @access public
   */
  public function getSQL()
  {
    return $this->_SQL;
  }

  /**
   * bool freeResult(void)
   *
   * Frees result memory
   *
   * @return bool false if an error occurs
   * @access public
   * @since 0.4
   */
  public function freeResult()
  {
    return ($this->_result ? (mysql_free_result($this->_result) != 0) : false);
  }

  /**
   * string __toString(void)
   *
   * @return string class name
   * @access public
   * @since 0.8
   */
  public function __toString()
  {
    return __CLASS__;
  }
} // end class
?>
