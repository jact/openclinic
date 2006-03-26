<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Query.php,v 1.12 2006/03/26 15:55:45 jact Exp $
 */

/**
 * Query.php
 *
 * Contains the class Query
 *
 * @author jact <jachavar@gmail.com>
 */

require_once("../classes/DbConnection.php");
require_once("../lib/Error.php");

/**
 * Query parent data access component class for all data access components
 *
 * Methods:
 *  bool connect(string $database = "", string $user = "", string $pwd = "", string $host = "", int $port = 3306)
 *  bool close(void)
 *  bool exec(string $sql, array $params = null)
 *  mixed fetchRow(int $arrayType = MYSQL_ASSOC)
 *  mixed fetchAll(int $arrayType = MYSQL_ASSOC)
 *  int numRows(void)
 *  bool resetResult(void)
 *  bool freeResult(void)
 *  mixed affectedRows(void)
 *  mixed getPrimaryKey(string $table = "")
 *  string getRowData(array $key, array $value, string $table = "")
 *  void clearErrors(void)
 *  bool isError(void)
 *  void captureError(bool $value)
 *  string getError(void)
 *  int getDbErrno(void)
 *  string getDbError(void)
 *  string getSQL(void)
 *  string getTableName(void)
 *
 * @author jact <jachavar@gmail.com>
 * @package OpenClinic
 * @access public
 */
class Query
{
  var $_conn;
  var $_isError = false;
  var $_captureError = false;
  var $_error = ""; // so it could change the error message
  var $_dbErrno = 0; // it is not superfluous
  var $_dbError = ""; // it is not superfluous
  var $_SQL = ""; // it is not superfluous
  var $_table = ""; // to extends classes
  var $_primaryKey = null; // to extends classes
  var $_map = null; // to extends classes

  /**
   * bool connect(string $database = "", string $user = "", string $pwd = "", string $host = "", int $port = 3306)
   *
   * Instantiates private connection var and connects to the database
   *
   * @param string $database (optional)
   * @param string $user (optional)
   * @param string $pwd (optional)
   * @param string $host (optional)
   * @param int $port (optional)
   * @return void
   * @access public
   */
  function connect($database = "", $user = "", $pwd = "", $host = "", $port = 3306)
  {
    if (empty($database))
    {
      $this->_conn = new DbConnection();
    }
    else
    {
      $this->_conn = new DbConnection($database, $user, $pwd, $host, $port);
    }

    $result = $this->_conn->connect();
    if ($result === false)
    {
      $this->_isError = true;
      $this->_error = $this->_conn->getError();
      $this->_dbErrno = $this->_conn->getDbErrno();
      $this->_dbError = $this->_conn->getDbError();

      if ( !$this->_captureError )
      {
        Error::query($this);
      }
    }

    return $result;
  }

  /**
   * bool close(void)
   *
   * Closes database and destroys connection
   *
   * @return boolean returns false, if error occurs
   * @access public
   */
  function close()
  {
    $result = $this->_conn->close();
    if ($result === false)
    {
      $this->_isError = true;
      $this->_error = $this->_conn->getError();
      $this->_dbErrno = $this->_conn->getDbErrno();
      $this->_dbError = $this->_conn->getDbError();

      if ( !$this->_captureError )
      {
        Error::query($this);
      }
    }
    unset($this->_conn);

    return $result;
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
   * @since 0.6
   */
  function exec($sql, $params = null)
  {
    $this->_SQL = $sql;

    $result = $this->_conn->exec($sql, $params);
    if ($result === false)
    {
      $this->_isError = true;
      $this->_error = $this->_conn->getError();
      $this->_dbErrno = $this->_conn->getDbErrno();
      $this->_dbError = $this->_conn->getDbError();

      if ( !$this->_captureError )
      {
        $this->close();
        Error::query($this);
      }
    }

    return $result;
  }

  /**
   * mixed fetchRow(int $arrayType = MYSQL_ASSOC)
   *
   * Returns a resulting array
   *
   * @param int $arrayType (optional) array type to return
   * @return array resulting array. Returns false, if no more rows to fetch.
   * @access public
   * @since 0.4
   */
  function fetchRow($arrayType = MYSQL_ASSOC)
  {
    $result = $this->_conn->fetchRow($arrayType);
    /*if ($result === false)
    {
      $this->_isError = true;
      $this->_error = $this->_conn->getError();

      if ( !$this->_captureError )
      {
        $this->close();
        Error::query($this);
      }
    }*/

    return $result;
  }

  /**
   * mixed fetchAll(int $arrayType = MYSQL_ASSOC)
   *
   * Returns all records in an array
   *
   * @param int $arrayType (optional) array type to return
   * @return array resulting array. Returns false if there is an empty result
   * @access public
   * @since 0.4
   */
  function fetchAll($arrayType = MYSQL_ASSOC)
  {
    $result = $this->_conn->fetchAll($arrayType);
    /*if ($result === false)
    {
      $this->_isError = true;
      $this->_error = $this->_conn->getError();
      $this->_dbErrno = $this->_conn->getDbErrno();
      $this->_dbError = $this->_conn->getDbError();

      if ( !$this->_captureError )
      {
        $this->close();
        Error::query($this);
      }
    }*/

    return $result;
  }

  /**
   * int numRows(void)
   *
   * Returns the number of rows in the result
   *
   * @return int, number of rows in result
   * @access public
   * @since 0.4
   */
  function numRows()
  {
    return $this->_conn->numRows();
  }

  /**
   * bool resetResult(void)
   *
   * @return boolean false if error occurred
   * @access public
   */
  function resetResult()
  {
    return $this->_conn->resetResult();
  }

  /**
   * bool freeResult(void)
   *
   * @return boolean false if error occurred
   * @access public
   */
  function freeResult()
  {
    return $this->_conn->freeResult();
  }

  /**
   * mixed affectedRows(void)
   *
   * @return int number of affected rows or false if an error occurs
   * @access public
   * @since 0.7
   */
  function affectedRows()
  {
    return $this->_conn->affectedRows();
  }

  /**
   * mixed getPrimaryKey(string $table = "")
   *
   * Returns the key fields of a table
   *
   * @param string $table (optional) name of table (if empty, $this->_table)
   * @return mixed array with key fields or false if an error occurs
   * @access public
   * @since 0.7
   */
  function getPrimaryKey($table = "")
  {
    if (empty($this->_table) && empty($table))
    {
      return false;
    }

    if (empty($table) && is_array($this->_primaryKey))
    {
      return $this->_primaryKey;
    }

    $sql = "SHOW FIELDS FROM " . (( !empty($table) ) ? trim($table) : $this->_table);

    $this->exec($sql);
    $result = $this->fetchAll();

    $key = array();
    foreach ($result as $k => $value)
    {
      if ($value["Key"] == "PRI")
      {
        //$key[$value["Field"]] = $value["Field"];
        $key[] = $value["Field"];
      }
    }

    return (count($key)) ? $key : false;
  }

  /**
   * string getRowData(array $key, array $value, string $table = "")
   *
   * Returns serialized row data of a table
   *
   * @param array $key key fields of table
   * @param array $value values of key fields of table
   * @param string $table (optional) name of table (if empty, $this->_table)
   * @return string serialized row data
   * @access public
   * @since 0.7
   */
  function getRowData($key, $value, $table = "")
  {
    if (count($key) == 0 || count($value) == 0)
    {
      return;
    }

    $sql = "SELECT *";
    $sql .= " FROM " . (( !empty($table) ) ? trim($table) : $this->_table);
    if ($key !== false)
    {
      $sql .= " WHERE ";
      for ($i = 0; $i < count($key); $i++)
      {
        $sql .= $key[$i] . "=" . ((gettype($value[$i]) == "string") ? "'" . $value[$i] . "'" : $value[$i]) . " AND ";
      }
      $sql = substr($sql, 0, -5); // to delete last " AND "
    }
    else // if $key is false, only one row will be retrieved
    {
      $sql .= " LIMIT 1";
    }

    $this->exec($sql);
    $result = $this->fetchRow();

    $result = array_values($result);
    $result = serialize($result);

    return $result;
  }

  /**
   * void clearErrors(void)
   *
   * clears error info
   *
   * @access public
   */
  function clearErrors()
  {
    $this->_isError = false;
    $this->_error = "";
    $this->_dbErrno = 0;
    $this->_dbError = "";
    $this->_SQL = "";
  }

  /**
   * bool isError(void)
   *
   * @return boolean true if error occurred
   * @access public
   */
  function isError()
  {
    return $this->_isError;
  }

  /**
   * void captureError(bool $value)
   *
   * @param bool $value
   * @access public
   * @since 0.8
   */
  function captureError($value)
  {
    $this->_captureError = ($value != false);
  }

  /**
   * string getError(void)
   *
   * @return string error message
   * @access public
   */
  function getError()
  {
    return $this->_error;
  }

  /**
   * int getDbErrno(void)
   *
   * @return int error number returned from database
   * @access public
   */
  function getDbErrno()
  {
    return $this->_dbErrno;
  }

  /**
   * string getDbError(void)
   *
   * @return string error message returned from database
   * @access public
   */
  function getDbError()
  {
    return $this->_dbError;
  }

  /**
   * string getSQL(void)
   *
   * @return string SQL used in query when an error occurs in Query execution
   * @access public
   */
  function getSQL()
  {
    return $this->_SQL;
  }

  /**
   * string getTableName(void)
   *
   * @return string table name
   * @access public
   */
  function getTableName()
  {
    return $this->_table;
  }
} // end class
?>
