<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Access_Query.php,v 1.2 2004/04/18 14:40:45 jact Exp $
 */

/**
 * Access_Query.php
 ********************************************************************
 * Contains the class Access_Query
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

require_once("../classes/Query.php");

/**
 * Access_Query data access component for application users accesses
 ********************************************************************
 * @author jact <jachavar@terra.es>
 * @access public
 ********************************************************************
 * Methods:
 *  mixed select(int $year = 0, int $month = 0, int $day = 0, int $hour = 0)
 *  mixed selectUser(int $idUser)
 *  mixed fetchAccess(void)
 *  bool insert(User $user)
 */
class Access_Query extends Query
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
    $sql = "SELECT login,access_date,description FROM access_log_tbl,profile_tbl";
    $sql .= " WHERE access_log_tbl.id_profile=profile_tbl.id_profile";
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
    $sql .= " ORDER BY access_date";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing user access information.";
      return false;
    }

    return $this->numRows();
  }

  /**
   * mixed selectUser(int $idUser)
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @param int $idUser
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function selectUser($idUser)
  {
    $sql = "SELECT login,access_date,description FROM access_log_tbl,profile_tbl";
    $sql .= " WHERE access_log_tbl.id_profile=profile_tbl.id_profile";
    $sql .= " AND access_log_tbl.id_user=" . intval($idUser);
    $sql .= " ORDER BY access_date";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing user access information.";
      return false;
    }

    return $this->numRows();
  }

  /**
   * mixed fetchAccess(void)
   ********************************************************************
   * Fetches a row from the query result and populates an array object.
   ********************************************************************
   * @return array returns access log or false if no more logs to fetch
   * @access public
   */
  function fetchAccess()
  {
    $array = $this->fetchRow();
    if ($array == false)
    {
      return false;
    }

    $access = array();
    $access["login"] = urldecode($array["login"]);
    $access["access_date"] = $array["access_date"];
    $access["profile"] = urldecode($array["description"]);

    return $access;
  }

  /**
   * bool insert(User $user)
   ********************************************************************
   * Inserts a new application user access into the database.
   ********************************************************************
   * @param User $user application user data
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($user)
  {
    $sql = "INSERT INTO access_log_tbl ";
    $sql .= "(id_user, login, access_date, id_profile) VALUES (";
    $sql .= $user->getIdUser() . ", ";
    $sql .= "'" . urlencode($user->getLogin()) . "', ";
    $sql .= "NOW(), ";
    $sql .= $user->getIdProfile() . ");";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error inserting new user access information.";
    }

    return $result;
  }
} // end class
?>
