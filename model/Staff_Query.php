<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Staff_Query.php,v 1.4 2004/06/16 19:08:48 jact Exp $
 */

/**
 * Staff_Query.php
 ********************************************************************
 * Contains the class Staff_Query
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

require_once("../classes/Query.php");
require_once("../classes/Staff.php");

/**
 * Staff_Query data access component for clinic staff members
 ********************************************************************
 * @author jact <jachavar@terra.es>
 * @access public
 ********************************************************************
 * Methods:
 *  mixed select(int $idMember = 0)
 *  bool selectDoctor(string $collegiateNumber)
 *  mixed selectType(string $type = 'A')
 *  mixed fetch(void)
 *  bool existLogin(string $login, int $idMember = 0)
 *  bool insert(Staff $staff)
 *  bool update(Staff $staff)
 *  bool delete(int $idMember, int $idUser = 0)
 */
class Staff_Query extends Query
{
  /**
   * mixed select(int $idMember = 0)
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @param int $idMember (optional) key of staff member to select
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function select($idMember = 0)
  {
    $sql = "SELECT *";
    $sql .= " FROM staff_tbl";
    if ($idMember > 0)
    {
      $sql .= " WHERE id_member=" . intval($idMember);
    }
    $sql .= " ORDER BY first_name, surname1";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing staff member information.";
      return false;
    }

    return $this->numRows();
  }

  /**
   * bool selectDoctor(string $collegiateNumber)
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @param string $collegiateNumber of staff member to select
   * @return boolean returns false, if error occurs
   * @access public
   */
  function selectDoctor($collegiateNumber)
  {
    $sql = "SELECT *";
    $sql .= " FROM staff_tbl";
    $sql .= " WHERE collegiate_number='" . urlencode($collegiateNumber) . "'";
    //$sql .= " ORDER BY first_name, surname1";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing staff member information.";
    }

    return $result;
  }

  /**
   * mixed selectType(string $type = 'A')
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @param string $type (optional) type of staff member to select
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function selectType($type = 'A')
  {
    switch ($type)
    {
      case 'A':
        $type = OPEN_ADMINISTRATIVE;
        break;

      case 'D':
        $type = OPEN_DOCTOR;
        break;
    }

    $sql = "SELECT *";
    $sql .= " FROM staff_tbl";
    $sql .= " WHERE member_type='" . $type . "'";
    $sql .= " ORDER BY first_name, surname1";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing staff member information.";
      return false;
    }

    return $this->numRows();
  }

  /**
   * mixed fetch(void)
   ********************************************************************
   * Fetches a row from the query result and populates the Staff object.
   ********************************************************************
   * @return mixed returns staff member or false if no more staff members to fetch
   * @access public
   */
  function fetch()
  {
    $array = $this->fetchRow();
    if ($array == false)
    {
      return false;
    }

    $staff = new Staff();
    $staff->setIdMember(intval($array["id_member"]));
    $staff->setMemberType(urldecode($array["member_type"]));
    $staff->setCollegiateNumber(urldecode($array["collegiate_number"]));
    $staff->setNIF(urldecode($array["nif"]));
    $staff->setFirstName(urldecode($array["first_name"]));
    $staff->setSurname1(urldecode($array["surname1"]));
    $staff->setSurname2(urldecode($array["surname2"]));
    $staff->setAddress(urldecode($array["address"]));
    $staff->setPhone(urldecode($array["phone_contact"]));
    $staff->setLogin(urldecode($array["login"]));
    $staff->setIdUser(intval($array["id_user"]));

    return $staff;
  }

  /**
   * bool existLogin(string $login, int $idMember = 0)
   ********************************************************************
   * Returns true if login already exists
   ********************************************************************
   * @param string $login staff member login
   * @param int $idMember (optional) key of staff member
   * @return boolean returns true if login already exists or false if error occurs
   * @access public
   */
  function existLogin($login, $idMember = 0)
  {
    $sql = "SELECT COUNT(login) FROM staff_tbl";
    $sql .= " WHERE login='" . urlencode($login) . "'";
    if ($idMember > 0)
    {
      $sql .= " AND id_member<>" . intval($idMember);
    }

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error checking for dup login.";
      return false;
    }

    $array = $this->fetchRow(MYSQL_NUM);

    return ($array[0] > 0);
  }

  /**
   * bool insert(Staff $staff)
   ********************************************************************
   * Inserts a new staff member into the staff table.
   ********************************************************************
   * @param Staff $staff staff member to insert
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($staff)
  {
    /*$isDupLogin = $this->existLogin($staff->getLogin());
    if ($this->errorOccurred())
    {
      return false;
    }

    if ($isDupLogin)
    {
      $this->_errorOccurred = true;
      $this->_error = "Login is already in use.";
      return false;
    }*/

    //print_r($staff); exit(); // debug
    $sql = "INSERT INTO staff_tbl ";
    $sql .= "(id_member, member_type, collegiate_number, nif, first_name, surname1, ";
    $sql .= "surname2, address, phone_contact, id_user, login) VALUES (NULL, ";
    $sql .= "'" . $staff->getMemberType() . "', ";
    $sql .= (($staff->getCollegiateNumber() == "") ? "NULL, " : "'" . urlencode($staff->getCollegiateNumber()) . "', ");
    $sql .= "'" . urlencode($staff->getNIF()) . "', ";
    $sql .= "'" . urlencode($staff->getFirstName()) . "', ";
    $sql .= "'" . urlencode($staff->getSurname1()) . "', ";
    $sql .= "'" . urlencode($staff->getSurname2()) . "', ";
    $sql .= (($staff->getAddress() == "") ? "NULL, " : "'" . urlencode($staff->getAddress()) . "', ");
    $sql .= (($staff->getPhone() == "") ? "NULL, " : "'" . urlencode($staff->getPhone()) . "', ");
    $sql .= (($staff->getIdUser() == "") ? "NULL, " : "'" . $staff->getIdUser() . "', ");
    $sql .= (($staff->getLogin() == "") ? "NULL);" : "'" . urlencode($staff->getLogin()) . "');");

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error inserting new member user information.";
    }

    return $result;
  }

  /**
   * bool update(Staff $staff)
   ********************************************************************
   * Update a staff member in the staff table.
   ********************************************************************
   * @param Staff $staff staff member to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  function update($staff)
  {
    // If changing login check to see if it already exists.
    /*$isDupLogin = $this->existLogin($staff->getLogin(), $staff->getIdMember());
    if ($this->errorOccurred())
    {
      return false;
    }

    if ($isDupLogin)
    {
      $this->_errorOccurred = true;
      $this->_error = "Login is already in use.";
      return false;
    }*/

    $sql = "UPDATE staff_tbl SET ";
    $sql .= "collegiate_number=" . (($staff->getCollegiateNumber() == "") ? "NULL, " : "'" . urlencode($staff->getCollegiateNumber()) . "', ");
    $sql .= "nif='" . urlencode($staff->getNIF()) . "', ";
    $sql .= "first_name='" . urlencode($staff->getFirstName()) . "', ";
    $sql .= "surname1='" . urlencode($staff->getSurname1()) . "', ";
    $sql .= "surname2='" . urlencode($staff->getSurname2()) . "', ";
    $sql .= "address=" . (($staff->getAddress() == "") ? "NULL, " : "'" . urlencode($staff->getAddress()) . "', ");
    $sql .= "phone_contact=" . (($staff->getPhone() == "") ? "NULL, " : "'" . urlencode($staff->getPhone()) . "', ");
    $sql .= "login=" . (($staff->getLogin() == "") ? "NULL" : "'" . urlencode($staff->getLogin()) . "'");
    $sql .= " WHERE id_member=" . $staff->getIdMember() . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error updating member user information.";
    }

    return $result;
  }

  /**
   * bool delete(int $idMember, int $idUser = 0)
   ********************************************************************
   * Deletes a staff member from the staff table.
   ********************************************************************
   * @param string $idMember key of staff member to delete
   * @param string $idUser key of user to delete
   * @return boolean returns false, if error occurs
   * @access public
   */
  function delete($idMember, $idUser = 0)
  {
    $sql = "DELETE FROM staff_tbl WHERE id_member=" . intval($idMember) . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error deleting staff information.";
      return false;
    }

    if ($idUser == 0)
    {
      return $result;
    }

    $sql = "DELETE FROM user_tbl WHERE id_user=" . intval($idUser) . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error deleting user information.";
    }

    return $result;
  }
} // end class
?>
