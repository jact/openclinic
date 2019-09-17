<?php
/**
 * DbConnection.php
 *
 * Contains the class DbConnection
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2019 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
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

function mysqli_field_name($result, $field_offset)
{
    $properties = mysqli_fetch_field_direct($result, $field_offset);
    return is_object($properties) ? $properties->name : null;
}

/**
 * DbConnection encapsulates all database specific functions for the project
 *
 * Methods:
 *  void __construct(array $dsn = null)
 *  bool connect(bool $persistency = OPEN_PERSISTENT)
 *  bool close(void)
 *  bool exec(string $sql, array $params = null)
 *  mixed fetchRow(int $arrayType = MYSQLI_ASSOC)
 *  mixed fetchAll(int $arrayType = MYSQLI_ASSOC)
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
   * void __construct(array $dsn = null)
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
  public function __construct($dsn = null)
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
    $this->_link = new mysqli(
      $this->_host,
      $this->_userName,
      $this->_passwd,
      $this->_dbName,
      $this->_port
    );
    if ($this->_link->connect_errno)
    {
      $this->_error = "Failed to connect to database server.";
      $this->_dbErrno = $this->_link->connect_errno;
      $this->_dbError = $this->_link->connect_error;

      return false;
    }

    return true;
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
    $this->_result = mysqli_close($this->_link);
    if ($this->_result == false)
    {
      $this->_error = "Unable to close database.";
      $this->_dbErrno = $this->_link->errno;
      $this->_dbError = $this->_link->error;
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
                $sql .= "'" . mysqli_real_escape_string($this->_link, $params[$i]) . "'"; // PHP >= 4.3.0
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
      AppError::trace($sql);
    }

    $this->_result = mysqli_query($this->_link, $sql);
    if ($this->_result == false)
    {
      $this->_error = "Unable to execute query.";
      $this->_dbErrno = $this->_link->errno;
      $this->_dbError = $this->_link->error;
    }

    return $this->_result;
  }

  /**
   * mixed fetchRow(int $arrayType = MYSQLI_ASSOC)
   *
   * Returns a resulting array
   *
   * @param int $arrayType (optional) array type to return
   * @return array resulting array. Returns false, if no more rows to fetch.
   * @access public
   */
  public function fetchRow($arrayType = MYSQLI_ASSOC)
  {
    if ($this->_result == false)
    {
      $this->_error = "Invalid result. Must execute query first.";
      return false;
    }

    if ($arrayType != MYSQLI_ASSOC && $arrayType != MYSQLI_NUM && $arrayType != MYSQLI_BOTH)
    {
      $arrayType = MYSQLI_ASSOC; // default value
    }

    $array = mysqli_fetch_array($this->_result, $arrayType);
    if ($array == false)
    {
      $this->_error = "Empty row. No more rows.";
    }

    return $array;
  }

  /**
   * mixed fetchAll(int $arrayType = MYSQLI_ASSOC)
   *
   * Returns all records in an array
   *
   * @param int $arrayType (optional) array type to return
   * @return array resulting array. Returns false if there is an empty result
   * @see fetchRow
   * @access public
   */
  public function fetchAll($arrayType = MYSQLI_ASSOC)
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
    return ($this->_link ? mysqli_affected_rows($this->_link) : false);
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
    return ($this->_link ? mysqli_insert_id($this->_link) : false);
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
    return ($this->_result ? (mysqli_data_seek($this->_result, 0) != 0) : false);
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
    return ($this->_result ? (mysqli_data_seek($this->_result, $row) != 0) : false);
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
    return mysqli_num_rows($this->_result);
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
    return mysqli_num_fields($this->_result);
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
    $types = array(
      MYSQLI_TYPE_DECIMAL => 'decimal',
      MYSQLI_TYPE_NEWDECIMAL => 'numeric',
      MYSQLI_TYPE_BIT => 'bit',
      MYSQLI_TYPE_TINY => 'tinyint',
      MYSQLI_TYPE_SHORT => 'smallint',
      MYSQLI_TYPE_LONG => 'long',
      MYSQLI_TYPE_FLOAT => 'float',
      MYSQLI_TYPE_DOUBLE => 'double',
      MYSQLI_TYPE_NULL => 'default_null',
      MYSQLI_TYPE_TIMESTAMP => 'timestamp',
      MYSQLI_TYPE_LONGLONG => 'bigint',
      MYSQLI_TYPE_INT24 => 'mediumint',
      MYSQLI_TYPE_DATE => 'date',
      MYSQLI_TYPE_TIME => 'time',
      MYSQLI_TYPE_DATETIME => 'datetime',
      MYSQLI_TYPE_YEAR => 'year',
      MYSQLI_TYPE_NEWDATE => 'date',
      MYSQLI_TYPE_INTERVAL => 'interval',
      MYSQLI_TYPE_ENUM => 'enum',
      MYSQLI_TYPE_SET => 'set',
      MYSQLI_TYPE_TINY_BLOB => 'tinyblob',
      MYSQLI_TYPE_MEDIUM_BLOB => 'mediumblob',
      MYSQLI_TYPE_LONG_BLOB => 'longblob',
      MYSQLI_TYPE_BLOB => 'blob',
      MYSQLI_TYPE_VAR_STRING => 'varchar',
      MYSQLI_TYPE_STRING => 'char',
      MYSQLI_TYPE_CHAR => 'tinyint',
      MYSQLI_TYPE_GEOMETRY => 'geometry'
    );

    $metadata = mysqli_fetch_field_direct($this->_result, $index);
    return $types[$metadata->type];
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
    $this->_result = mysqli_query($this->_link, "SHOW TABLES");
    return ($this->_link->error == '');
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
    $this->listTables();
    $this->rowSeek($index);
    $this->_result = $this->fetchRow(MYSQLI_NUM);
    return $this->_result[0];
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
    $this->_result = mysqli_query($this->_link, "SHOW COLUMNS FROM " . $table);
    return ($this->_link->error == '');
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
    return mysqli_field_name($this->_result, $index);
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
    return ($this->_result ? (mysqli_free_result($this->_result) != 0) : false);
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
