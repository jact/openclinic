<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: DbConnection.php,v 1.4 2004/07/21 18:06:11 jact Exp $
 */

/**
 * DbConnection.php
 ********************************************************************
 * Contains the class DbConnection
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

if (file_exists("../database_constants.php"))
{
  include_once("../database_constants.php");
}

/**
 * DbConnection encapsulates all database specific functions for the project
 ********************************************************************
 * @author jact <jachavar@terra.es>
 * @access public
 ********************************************************************
 * Methods:
 *  void DbConnection(string $database = "", string $user = "", string $pwd = "", string $host = "")
 *  bool connect(bool $persistency = false)
 *  bool close(void)
 *  bool exec(string $sql)
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
 *  int getLink(void)
 *  string getError(void)
 *  int getDbErrno(void)
 *  string getDbError(void)
 *  string getSQL(void)
 *  bool freeResult(void)
 */
class DbConnection
{
  /**
   * @var string
   * @access private
   */
  var $_host;

  /**
   * @var string
   * @access private
   */
  var $_userName;

  /**
   * @var string
   * @access private
   */
  var $_passwd;

  /**
   * @var string
   * @access private
   */
  var $_dbName;

  /**
   * @var int
   * @access private
   */
  var $_link;

  /**
   * @var mixed
   * @access private
   */
  var $_result;

  /**
   * @var string
   * @access private
   */
  var $_error;

  /**
   * @var int
   * @access private
   */
  var $_dbErrno;

  /**
   * @var string
   * @access private
   */
  var $_dbError;

  /**
   * @var string
   * @access private
   */
  var $_SQL;

  /**
   * void DbConnection(string $database = "", string $user = "", string $pwd = "", string $host = "")
   ********************************************************************
   * Constructor function
   ********************************************************************
   * @param string $database (optional)
   * @param string $user (optional)
   * @param string $pwd (optional)
   * @param string $host (optional)
   * @return void
   * @access public
   */
  function DbConnection($database = "", $user = "", $pwd = "", $host = "")
  {
    $this->_host = (empty($database)) ? OPEN_HOST : $host;
    $this->_userName = (empty($database)) ? OPEN_USERNAME : $user;
    $this->_passwd = (empty($database)) ? OPEN_PWD : $pwd;
    $this->_dbName = (empty($database)) ? OPEN_DATABASE : $database;
  }

  /**
   * bool connect(bool $persistency = false)
   ********************************************************************
   * Connects to the database
   ********************************************************************
   * @param bool $persistency (optional) persistent connection or not
   * @return boolean returns false, if error occurs
   * @access public
   */
  function connect($persistency = false)
  {
    $this->_link = ($persistency) ? mysql_pconnect($this->_host, $this->_userName, $this->_passwd) : mysql_connect($this->_host, $this->_userName, $this->_passwd);
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
   ********************************************************************
   * Closes database connection
   ********************************************************************
   * @return boolean returns false, if error occurs
   * @access public
   */
  function close()
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
   * bool exec(string $sql)
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @param string $sql SQL of query to execute
   * @return boolean returns false, if error occurs
   * @access public
   */
  function exec($sql)
  {
    $this->_SQL = $sql;

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
   ********************************************************************
   * Returns a resulting array
   ********************************************************************
   * @param int $arrayType (optional) array type to return
   * @return array resulting array. Returns false, if no more rows to fetch.
   * @access public
   */
  function fetchRow($arrayType = MYSQL_ASSOC)
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
   ********************************************************************
   * Returns all records in an array
   ********************************************************************
   * @param int $arrayType (optional) array type to return
   * @return array resulting array. Returns false if there is an empty result
   * @see fetchRow
   * @access public
   */
  function fetchAll($arrayType = MYSQL_ASSOC)
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
   ********************************************************************
   * Returns number of affected rows by last MySQL sentence
   ********************************************************************
   * @return int number of affected rows or false if an error occurs
   * @access public
   */
  function affectedRows()
  {
    return ($this->_link ? mysql_affected_rows($this->_link) : false);
  }

  /**
   * mixed lastInsertId(void)
   ********************************************************************
   * Returns last id generated by last INSERT sentence
   ********************************************************************
   * @return false, if no link, int otherwise.
   * @access public
   */
  function lastInsertId()
  {
    return ($this->_link ? mysql_insert_id($this->_link) : false);
  }

  /**
   * bool resetResult(void)
   ********************************************************************
   * Resets row point to the first row in the resultset
   ********************************************************************
   * @return false, if no more rows to fetch, true otherwise.
   * @access public
   */
  function resetResult()
  {
    return ($this->_result ? (mysql_data_seek($this->_result, 0) != 0) : false);
  }

  /**
   * bool rowSeek(int $row = 0)
   ********************************************************************
   * Moves row point to the $row number in the resultset
   ********************************************************************
   * @param int $row (optional) row number
   * @return false, if no more rows to fetch, true otherwise.
   * @access public
   */
  function rowSeek($row = 0)
  {
    return ($this->_result ? (mysql_data_seek($this->_result, $row) != 0) : false);
  }

  /**
   * int numRows(void)
   ********************************************************************
   * Returns the number of rows in the result
   ********************************************************************
   * @return int number of rows in the result
   * @access public
   */
  function numRows()
  {
    return mysql_num_rows($this->_result);
  }

  /**
   * int numFields(void)
   ********************************************************************
   * Returns the number of fields in the result
   ********************************************************************
   * @return int number of fields in result
   * @access public
   */
  function numFields()
  {
    return mysql_num_fields($this->_result);
  }

  /**
   * string fieldType(int $index = 0)
   ********************************************************************
   * Returns the type of the $index field in the result
   ********************************************************************
   * @param int $index (optional) index of the field
   * @return string type of the field
   * @access public
   */
  function fieldType($index = 0)
  {
    return mysql_field_type($this->_result, $index);
  }

  /**
   * bool listTables(void)
   ********************************************************************
   * Lists database tables
   ********************************************************************
   * @return bool false if an error occurs
   * @access public
   */
  function listTables()
  {
    return (($this->_result = mysql_list_tables($this->_dbName, $this->_link)) != 0);
  }

  /**
   * string tableName(int $index = 0)
   ********************************************************************
   * Returns the name of the $index table in the result
   ********************************************************************
   * @param int $index (optional) index of the table
   * @return string name of the table
   * @access public
   */
  function tableName($index = 0)
  {
    return mysql_tablename($this->_result, $index);
  }

  /**
   * bool listFields(string $table)
   ********************************************************************
   * Lists fields of the result
   ********************************************************************
   * @param string $table name of the table
   * @return bool false if an error occurs
   * @access public
   */
  function listFields($table)
  {
    return (($this->_result = mysql_list_fields($this->_dbName, $table, $this->_link)) != 0);
  }

  /**
   * string fieldName(int $index = 0)
   ********************************************************************
   * Returns the name of the $index field in the result
   ********************************************************************
   * @param int $index (optional) index of the field
   * @return string name of the field
   * @access public
   */
  function fieldName($index = 0)
  {
    return mysql_field_name($this->_result, $index);
  }

  /**
   * int getLink(void)
   ********************************************************************
   * @return int connection link
   * @access public
   */
  function getLink()
  {
    return $this->_link;
  }

  /**
   * string getError(void)
   ********************************************************************
   * @return string error message
   * @access public
   */
  function getError()
  {
    return $this->_error;
  }

  /**
   * int getDbErrno(void)
   ********************************************************************
   * @return int error number returned from database
   * @access public
   */
  function getDbErrno()
  {
    return $this->_dbErrno;
  }

  /**
   * string getDbError(void)
   ********************************************************************
   * @return string error message returned from database
   * @access public
   */
  function getDbError()
  {
    return $this->_dbError;
  }

  /**
   * string getSQL(void)
   ********************************************************************
   * @return string SQL used in exec when an error occurs in query execution
   * @access public
   */
  function getSQL()
  {
    return $this->_SQL;
  }

  /**
   * bool freeResult(void)
   ********************************************************************
   * Frees result memory
   ********************************************************************
   * @return bool false if an error occurs
   * @access public
   */
  function freeResult()
  {
    return ($this->_result ? (mysql_free_result($this->_result) != 0) : false);
  }
} // end class
?>
