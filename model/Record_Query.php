<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Record_Query.php,v 1.2 2004/04/18 14:40:46 jact Exp $
 */

/**
 * Record_Query.php
 ********************************************************************
 * Contains the class Record_Query
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

require_once("../classes/Query.php");

/**
 * Record_Query data access component for record logs
 ********************************************************************
 * @author jact <jachavar@terra.es>
 * @access public
 ********************************************************************
 * Methods:
 *  mixed select(int $year = 0, int $month = 0, int $day = 0, int $hour = 0)
 *  mixed fetchRecord(void)
 *  bool insert(int $idUser, string $login, string $tableName, string $operation, int $idKey1, int $idKey2 = 0)
 */
class Record_Query extends Query
{
  /**
   * mixed select(int $year = 0, int $month = 0, int $day = 0, int $hour = 0)
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @param int $year (optional)
   * @param int $month (optional)
   * @param int $day (optional)
   * @param int $hour (optional)
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function select($year = 0, $month = 0, $day = 0, $hour = 0)
  {
    $sql = "SELECT * FROM record_log_tbl";
    $sql .= " WHERE 1";
    if ($year != "")
    {
      $sql .= " AND YEAR(access_date)=" . intval($year);
    }
    if ($month != "")
    {
      $sql .= " AND MONTH(access_date)=" . intval($month);
    }
    if ($day != "")
    {
      $sql .= " AND DATE_FORMAT(access_date, '%d')=" . intval($day);
    }
    if ($hour != "")
    {
      $sql .= " AND DATE_FORMAT(access_date, '%H')=" . intval($hour);
    }
    $sql .= " ORDER BY access_date;";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing record log information.";
      return false;
    }

    return $this->numRows();
  }

  /**
   * mixed fetchRecord(void)
   ********************************************************************
   * Fetches a row from the query result and populates an array object.
   ********************************************************************
   * @return array returns record log or false if no more logs to fetch
   * @access public
   */
  function fetchRecord()
  {
    $array = $this->fetchRow();
    if ($array == false)
    {
      return false;
    }

    $record = array();
    $record["login"] = urldecode($array["login"]);
    $record["access_date"] = $array["access_date"];
    $record["table_name"] = urldecode($array["table_name"]);
    $record["operation"] = urldecode($array["operation"]);
    $record["id_key1"] = $array["id_key1"];
    $record["id_key2"] = $array["id_key2"];

    return $record;
  }

  /**
   * bool insert(int $idUser, string $login, string $tableName, string $operation, int $idKey1, int $idKey2 = 0)
   ********************************************************************
   * Inserts a new record log into the database.
   ********************************************************************
   * @param int $idUser key of user that makes the operation
   * @param string $login login of user that makes the operation
   * @param string $tableName
   * @param string $operation one between INSERT, UPDATE, DELETE
   * @param int $idKey1 principal key of the record
   * @param int $idKey2 (optional) second principal key of the record
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($idUser, $login, $tableName, $operation, $idKey1, $idKey2 = 0)
  {
    $sql = "INSERT INTO record_log_tbl ";
    $sql .= "(id_user, login, access_date, table_name, operation, id_key1, id_key2) VALUES (";
    $sql .= intval($idUser) . ", ";
    $sql .= "'" . urlencode($login) . "', ";
    $sql .= "NOW(), ";
    $sql .= "'" . urlencode($tableName) . "', ";
    $sql .= "'" . urlencode($operation) . "', ";
    $sql .= ($idKey1 == 0) ? "LAST_INSERT_ID(), " : intval($idKey1) . ", ";
    $sql .= ($idKey2 == 0) ? "NULL);" : intval($idKey2) . ");";
    //echo $sql; exit(); // debug

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error inserting new record log information.";
    }

    return $result;
  }
} // end class
?>
