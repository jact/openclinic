<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Query.php,v 1.3 2004/07/07 17:28:11 jact Exp $
 */

/**
 * Query.php
 ********************************************************************
 * Contains the class Query
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

require_once("../classes/DbConnection.php");

/**
 * Query parent data access component class for all data access components
 ********************************************************************
 * @author jact <jachavar@terra.es>
 * @access public
 ********************************************************************
 * Methods:
 *  bool connect(string $database = "", string $user = "", string $pwd = "", string $host = "")
 *  bool close(void)
 *  bool exec(string $sql)
 *  mixed fetchRow(int $arrayType = MYSQL_ASSOC)
 *  mixed fetchAll(int $arrayType = MYSQL_ASSOC)
 *  int numRows(void)
 *  bool resetResult(void)
 *  bool freeResult(void)
 *  mixed affectedRows(void)
 *  void clearErrors(void)
 *  bool isError(void)
 *  string getError(void)
 *  int getDbErrno(void)
 *  string getDbError(void)
 *  string getSQL(void)
 */
class Query
{
  var $_conn;
  var $_isError = false;
  var $_error = ""; // so it could change the error message
  var $_dbErrno = 0; // it is not superfluous
  var $_dbError = ""; // it is not superfluous
  var $_SQL = ""; // it is not superfluous

  /**
   * bool connect(string $database = "", string $user = "", string $pwd = "", string $host = "")
   ********************************************************************
   * Instantiates private connection var and connects to the database
   ********************************************************************
   * @param string $database (optional)
   * @param string $user (optional)
   * @param string $pwd (optional)
   * @param string $host (optional)
   * @return void
   * @access public
   */
  function connect($database = "", $user = "", $pwd = "", $host = "")
  {
    if (empty($database))
    {
      $this->_conn = new DbConnection();
    }
    else
    {
      $this->_conn = new DbConnection($database, $user, $pwd, $host);
    }

    $result = $this->_conn->connect();
    if ($result === false)
    {
      $this->_isError = true;
      $this->_error = $this->_conn->getError();
      $this->_dbErrno = $this->_conn->getDbErrno();
      $this->_dbError = $this->_conn->getDbError();
    }

    return $result;
  }

  /**
   * bool close(void)
   ********************************************************************
   * Closes database and destroys connection
   ********************************************************************
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
    }
    unset($this->_conn);

    return $result;
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

    $result = $this->_conn->exec($sql);
    if ($result === false)
    {
      $this->_isError = true;
      $this->_error = $this->_conn->getError();
      $this->_dbErrno = $this->_conn->getDbErrno();
      $this->_dbError = $this->_conn->getDbError();
    }

    return $result;
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
    $result = $this->_conn->fetchRow($arrayType);
    if ($result === false)
    {
      $this->_isError = true;
      $this->_error = $this->_conn->getError();
    }

    return $result;
  }

  /**
   * mixed fetchAll(int $arrayType = MYSQL_ASSOC)
   ********************************************************************
   * Returns all records in an array
   ********************************************************************
   * @param int $arrayType (optional) array type to return
   * @return array resulting array. Returns false if there is an empty result
   * @access public
   */
  function fetchAll($arrayType = MYSQL_ASSOC)
  {
    $result = $this->_conn->fetchAll($arrayType);
    if ($result === false)
    {
      $this->_isError = true;
      $this->_error = $this->_conn->getError();
      $this->_dbErrno = $this->_conn->getDbErrno();
      $this->_dbError = $this->_conn->getDbError();
    }

    return $result;
  }

  /**
   * int numRows(void)
   ********************************************************************
   * Returns the number of rows in the result
   ********************************************************************
   * @return int, number of rows in result
   * @access public
   */
  function numRows()
  {
    return $this->_conn->numRows();
  }

  /**
   * bool resetResult(void)
   ********************************************************************
   * @return boolean false if error occurred
   * @access public
   */
  function resetResult()
  {
    return $this->_conn->resetResult();
  }

  /**
   * bool freeResult(void)
   ********************************************************************
   * @return boolean false if error occurred
   * @access public
   */
  function freeResult()
  {
    return $this->_conn->freeResult();
  }

  /**
   * mixed affectedRows(void)
   ********************************************************************
   * @return int number of affected rows or false if an error occurs
   * @access public
   */
  function affectedRows()
  {
    return $this->_conn->affectedRows();
  }

  /**
   * void clearErrors(void)
   ********************************************************************
   * clears error info
   ********************************************************************
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
   ********************************************************************
   * @return boolean true if error occurred
   * @access public
   */
  function isError()
  {
    return $this->_isError;
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
   * @return string SQL used in query when an error occurs in Query execution
   * @access public
   */
  function getSQL()
  {
    return $this->_SQL;
  }
} // end class
?>
