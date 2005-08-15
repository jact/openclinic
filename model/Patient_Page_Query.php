<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Patient_Page_Query.php,v 1.4 2005/08/15 11:03:27 jact Exp $
 */

/**
 * Patient_Page_Query.php
 *
 * Contains the class Patient_Page_Query
 *
 * Author: jact <jachavar@gmail.com>
 */

require_once("../classes/Page_Query.php");
require_once("../classes/Patient.php");

/**
 * Patient_Page_Query data access component for patients
 *
 * @author jact <jachavar@gmail.com>
 * @access public
 *
 * Methods:
 *  void Patient_Page_Query(void)
 *  bool search(int $type, array $word, int $page, string $logical, int $limitFrom = 0)
 *  mixed getLastId(void)
 *  mixed select(int $idPatient)
 *  mixed fetch(void)
 *  bool existName(string $firstName, string $surname1, string $surname2, int $idPatient = 0)
 *  bool insert(Patient $patient)
 *  bool update(Patient $patient)
 *  bool delete(int $idPatient)
 */
class Patient_Page_Query extends Page_Query
{
  /**
   * void Patient_Page_Query(void)
   *
   * Constructor function
   *
   * @return void
   * @access public
   */
  function Patient_Page_Query()
  {
    $this->_table = "patient_tbl";
  }

  /**
   * bool search(int $type, array $word, int $page, string $logical, int $limitFrom = 0)
   *
   * Executes a query search
   *
   * @param int $type one of the global constants
   * @param array (string) $word string(s) to search for
   * @param int $page What page should be returned if results are more than one page
   * @param string $logical logical operator to concatenate string(s) to search for
   * @param int $limitFrom (optional) maximum number of results
   * @return boolean returns false, if error occurs
   * @access public
   */
  function search($type, $word, $page, $logical, $limitFrom = 0)
  {
    // reset stats
    $this->_rowNumber = 0;
    $this->_currentRow = 0;
    $this->_currentPage = ($page > 1) ? intval($page) : 1;
    $this->_rowCount = 0;
    $this->_pageCount = 0;

    // Building sql statements
    switch ($type)
    {
      case OPEN_SEARCH_SURNAME1:
        $field = $this->_table . "." . "surname1";
        break;

      case OPEN_SEARCH_SURNAME2:
        $field = $this->_table . "." . "surname2";
        break;

      case OPEN_SEARCH_FIRSTNAME:
        $field = $this->_table . "." . "first_name";
        break;

      case OPEN_SEARCH_NIF:
        $field = $this->_table . "." . "nif";
        break;

      case OPEN_SEARCH_NTS:
        $field = $this->_table . "." . "nts";
        break;

      case OPEN_SEARCH_NSS:
        $field = $this->_table . "." . "nss";
        break;

      case OPEN_SEARCH_BIRTHPLACE:
        $field = $this->_table . "." . "birth_place";
        break;

      case OPEN_SEARCH_ADDRESS:
        $field = $this->_table . "." . "address";
        break;

      case OPEN_SEARCH_PHONE:
        $field = $this->_table . "." . "phone_contact";
        break;

      case OPEN_SEARCH_INSURANCE:
        $field = $this->_table . "." . "insurance_company";
        break;

      case OPEN_SEARCH_COLLEGIATE:
        $field = "staff_tbl.collegiate_number";
        break;

      default:
        $field = "no_field";
        break;
    }

    // Building sql statements
    $sql = " FROM " . $this->_table . " LEFT JOIN staff_tbl ON " . $this->_table . ".id_member=staff_tbl.id_member";
    $sql .= " WHERE ";

    $num = sizeof($word);
    if ($num > 1)
    {
      for ($i = 0; $i < ($num - 1); $i++)
      {
        if ($logical == OPEN_NOT)
        {
          $sql .= $field . " NOT LIKE '%" . $word[$i] . "%' AND ";
        }
        else
        {
          $sql .= $field . " LIKE '%" . $word[$i] . "%' " . $logical . " ";
        }
      }
    }
    if ($logical == OPEN_NOT)
    {
      $sql .= $field . " NOT LIKE '%" . $word[$num - 1] . "%'";
    }
    else
    {
      $sql .= $field . " LIKE '%" . $word[$num - 1] . "%'";
    }

    $sqlCount = "SELECT COUNT(*) AS row_count " . $sql;

    $sql = "SELECT " . $this->_table . ".*, staff_tbl.collegiate_number, FLOOR((TO_DAYS(GREATEST(IF(decease_date='0000-00-00',CURRENT_DATE,decease_date),IFNULL(decease_date,CURRENT_DATE))) - TO_DAYS(birth_date)) / 365) AS age " . $sql;
    $sql .= " ORDER BY " . $this->_table . ".surname1, " . $this->_table . ".surname2, " . $this->_table . ".first_name";
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
    $countResult = $this->exec($sqlCount);
    if ($countResult == false)
    {
      $this->_error = "Error counting patient search results.";
      return false;
    }

    // Calculate stats based on row count
    $array = $this->fetchRow();
    $this->_rowCount = $array["row_count"];
    if ($limitFrom > 0 && $limitFrom < $this->_rowCount)
    {
      $this->_rowCount = $limitFrom;
    }
    $this->_pageCount = (intval($this->_itemsPerPage) > 0) ? ceil($this->_rowCount / $this->_itemsPerPage) : 1;

    // Running search sql statement
    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error searching patient information.";
      return false;
    }

    return $result;
  }

  /**
   * mixed getLastId(void)
   *
   * Executes a query
   *
   * @return mixed if error occurs returns false, else last insert id
   * @access public
   */
  function getLastId()
  {
    $sql = "SELECT LAST_INSERT_ID() AS last_id";
    $sql .= " FROM " . $this->_table;

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing last id information.";
      return false;
    }

    $array = $this->fetchRow();
    return ($array == false ? 0 : $array["last_id"]);
  }

  /**
   * mixed select(int $idPatient)
   *
   * Executes a query
   *
   * @param int $idPatient key of patient to select
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function select($idPatient)
  {
    $sql = "SELECT " . $this->_table . ".*, staff_tbl.collegiate_number, ";
    $sql .= "FLOOR((TO_DAYS(GREATEST(IF(decease_date='0000-00-00',CURRENT_DATE,decease_date),IFNULL(decease_date,CURRENT_DATE))) - TO_DAYS(birth_date)) / 365) AS age";
    $sql .= " FROM " . $this->_table . " LEFT JOIN staff_tbl ON " . $this->_table . ".id_member=staff_tbl.id_member";
    $sql .= " WHERE id_patient=" . intval($idPatient);

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing patient information.";
      return false;
    }

    return $this->numRows();
  }

  /**
   * mixed fetch(void)
   *
   * Fetches a row from the query result and populates the Patient object.
   *
   * @return mixed returns patient or false if no more patients to fetch
   * @access public
   */
  function fetch()
  {
    $array = $this->fetchRow();
    if ($array == false)
    {
      return false;
    }

    // increment rowNumber
    $this->_rowNumber = $this->_rowNumber + 1;
    $this->_currentRow = $this->_rowNumber + (($this->_currentPage - 1) * $this->_itemsPerPage);

    $patient = new Patient();
    $patient->setIdPatient(intval($array["id_patient"]));
    //$patient->setLastUpdateDate(urldecode($array["last_update_date"]));
    $patient->setIdMember(intval($array["id_member"]));
    $patient->setCollegiateNumber(urldecode($array["collegiate_number"]));
    $patient->setNIF(urldecode($array["nif"]));
    $patient->setFirstName(urldecode($array["first_name"]));
    $patient->setSurname1(urldecode($array["surname1"]));
    $patient->setSurname2(urldecode($array["surname2"]));
    $patient->setAddress(urldecode($array["address"]));
    $patient->setPhone(urldecode($array["phone_contact"]));
    $patient->setSex(urlencode($array["sex"]));
    $patient->setRace(urldecode($array["race"]));
    $patient->setBirthDate(urldecode($array["birth_date"]));
    $patient->setAge(intval($array["age"]));
    $patient->setBirthPlace(urldecode($array["birth_place"]));
    $patient->setDeceaseDate(urldecode($array["decease_date"]));
    $patient->setNTS(urldecode($array["nts"]));
    $patient->setNSS(urldecode($array["nss"]));
    $patient->setFamilySituation(urldecode($array["family_situation"]));
    $patient->setLabourSituation(urldecode($array["labour_situation"]));
    $patient->setEducation(urldecode($array["education"]));
    $patient->setInsuranceCompany(urldecode($array["insurance_company"]));

    return $patient;
  }

  /**
   * bool existName(string $firstName, string $surname1, string $surname2, int $idPatient = 0)
   *
   * Returns true if patient name already exists
   *
   * @param string $firstName
   * @param string $surname1
   * @param string $surname2
   * @param int $idPatient (optional) key of patient
   * @return boolean returns true if name already exists
   * @access public
   */
  function existName($firstName, $surname1, $surname2, $idPatient = 0)
  {
    $sql = "SELECT COUNT(id_patient)";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE first_name='" . urlencode($firstName) . "'";
    $sql .= " AND surname1='" . urlencode($surname1) . "'";
    $sql .= " AND surname2='" . urlencode($surname2) . "'";
    if ($idPatient)
    {
      $sql .= " AND id_patient<>" . intval($idPatient);
    }

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error checking for dup name.";
      return false;
    }

    $array = $this->fetchRow(MYSQL_NUM);

    return ($array[0] > 0);
  }

  /**
   * bool insert(Patient $patient)
   *
   * Inserts a new patient into the database.
   *
   * @param Patient $patient patient to insert
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($patient)
  {
    if (function_exists("is_a") && !is_a($patient, "Patient") ) // SF.net DEMO version PHP 4.1.2
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    /*$isDupName = $this->existName($patient->getFirstName(), $patient->getSurname1(), $patient->getSurname2());
    if ($this->isError())
    {
      return false;
    }

    if ($isDupName)
    {
      $this->_isError = true;
      $this->_error = "Patient name, " . $patient->getFirstName();
      $this->_error .= " " . $patient->getSurname1();
      $this->_error .= " " . $patient->getSurname2() . ", is already in use.";
      return false;
    }*/

    $sql = "INSERT INTO " . $this->_table;
    $sql .= " (id_patient, nif, first_name, surname1, surname2, address, ";
    $sql .= "phone_contact, sex, race, birth_date, birth_place, decease_date, nts, nss, ";
    $sql .= "family_situation, labour_situation, education, insurance_company, ";
    $sql .= "id_member) VALUES (NULL, ";
    //$sql .= "'" . $patient->getLastUpdateDate() . "', ";
    $sql .= ($patient->getNIF() == "") ? "NULL, " : "'" . urlencode($patient->getNIF()) . "', ";
    $sql .= "'" . urlencode($patient->getFirstName()) . "', ";
    $sql .= "'" . urlencode($patient->getSurname1()) . "', ";
    $sql .= "'" . urlencode($patient->getSurname2()) . "', ";
    $sql .= ($patient->getAddress() =="") ? "NULL, " : "'" . urlencode($patient->getAddress()) . "', ";
    $sql .= ($patient->getPhone() == "") ? "NULL, " : "'" . urlencode($patient->getPhone()) . "', ";
    $sql .= "'" . $patient->getSex() . "', ";
    $sql .= ($patient->getRace() == "") ? "NULL, " : "'" . urlencode($patient->getRace()) . "', ";
    $sql .= ($patient->getBirthDate() == "") ? "NULL, " : "'" . $patient->getBirthDate() . "', ";
    $sql .= ($patient->getBirthPlace() == "") ? "NULL, " : "'" . urlencode($patient->getBirthPlace()) . "', ";
    $sql .= ($patient->getDeceaseDate() == "") ? "NULL, " : "'" . $patient->getDeceaseDate() . "', ";
    $sql .= ($patient->getNTS() == "") ? "NULL, " : "'" . urlencode($patient->getNTS()) . "', ";
    $sql .= ($patient->getNSS() == "") ? "NULL, " : "'" . urlencode($patient->getNSS()) . "', ";
    $sql .= ($patient->getFamilySituation() == "") ? "NULL, " : "'" . urlencode($patient->getFamilySituation()) . "', ";
    $sql .= ($patient->getLabourSituation() == "") ? "NULL, " : "'" . urlencode($patient->getLabourSituation()) . "', ";
    $sql .= ($patient->getEducation() == "") ? "NULL, " : "'" . urlencode($patient->getEducation()) . "', ";
    $sql .= ($patient->getInsuranceCompany() == "") ? "NULL, " : "'" . urlencode($patient->getInsuranceCompany()) . "', ";
    $sql .= ($patient->getIdMember() == 0) ? "NULL);" : $patient->getIdMember() . ");";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error inserting new patient information.";
      return false;
    }

    $sql = "INSERT INTO history_tbl (id_patient) VALUES (";
    $sql .= $this->getLastId() . ");";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error inserting new patient information in history.";
    }

    return $result;
  }

  /**
   * bool update(Patient $patient)
   *
   * Update a patient in the database.
   *
   * @param Patient $patient patient to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  function update($patient)
  {
    if (function_exists("is_a") && !is_a($patient, "Patient") ) // SF.net DEMO version PHP 4.1.2
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    /*$isDupName = $this->existName($patient->getFirstName(), $patient->getSurname1(), $patient->getSurname2(), $patient->getIdPatient());
    if ($this->isError())
    {
      return false;
    }

    if ($isDupName)
    {
      $this->_isError = true;
      $this->_error = "Patient name, " . $patient->getFirstName();
      $this->_error .= " " . $patient->getSurname1();
      $this->_error .= " " . $patient->getSurname2() . ", is already in use.";
      return false;
    }*/

    $sql = "UPDATE " . $this->_table . " SET ";
    //$sql .= "last_update_date=curdate(), ";
    $sql .= "nif=" . (($patient->getNIF() == "") ? "NULL, " : "'" . urlencode($patient->getNIF()) . "', ");
    $sql .= "first_name='" . urlencode($patient->getFirstName()) . "', ";
    $sql .= "surname1='" . urlencode($patient->getSurname1()) . "', ";
    $sql .= "surname2='" . urlencode($patient->getSurname2()) . "', ";
    $sql .= "address=" . (($patient->getAddress() =="") ? "NULL, " : "'" . urlencode($patient->getAddress()) . "', ");
    $sql .= "phone_contact=" . (($patient->getPhone() == "") ? "NULL, " : "'" . urlencode($patient->getPhone()) . "', ");
    $sql .= "sex='" . $patient->getSex() . "', ";
    $sql .= "race=" . (($patient->getRace() == "") ? "NULL, " : "'" . urlencode($patient->getRace()) . "', ");
    $sql .= "birth_date=" . (($patient->getBirthDate() == "") ? "NULL, " : "'" . $patient->getBirthDate() . "', ");
    $sql .= "birth_place=" . (($patient->getBirthPlace() == "") ? "NULL, " : "'" . urlencode($patient->getBirthPlace()) . "', ");
    $sql .= "decease_date=" . (($patient->getDeceaseDate() == "") ? "NULL, " : "'" . $patient->getDeceaseDate() . "', ");
    $sql .= "nts=" . (($patient->getNTS() == "") ? "NULL, " : "'" . urlencode($patient->getNTS()) . "', ");
    $sql .= "nss=" . (($patient->getNSS() == "") ? "NULL, " : "'" . urlencode($patient->getNSS()) . "', ");
    $sql .= "family_situation=" . (($patient->getFamilySituation() == "") ? "NULL, " : "'" . urlencode($patient->getFamilySituation()) . "', ");
    $sql .= "labour_situation=" . (($patient->getLabourSituation() == "") ? "NULL, " : "'" . urlencode($patient->getLabourSituation()) . "', ");
    $sql .= "education=" . (($patient->getEducation() == "") ? "NULL, " : "'" . urlencode($patient->getEducation()) . "', ");
    $sql .= "insurance_company=" . (($patient->getInsuranceCompany() == "") ? "NULL, " : "'" . urlencode($patient->getInsuranceCompany()) . "', ");
    $sql .= "id_member=" . (($patient->getIdMember() == 0) ? "NULL " : $patient->getIdMember() . " ");
    $sql .= "WHERE id_patient=" . $patient->getIdPatient();

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error updating patient information.";
    }

    return $result;
  }

  /**
   * bool delete(int $idPatient)
   *
   * Deletes a patient from the database.
   *
   * @param int $idPatient key of patient to delete
   * @return boolean returns false, if error occurs
   * @access public
   */
  function delete($idPatient)
  {
    $sql = "DELETE FROM " . $this->_table;
    $sql .= " WHERE id_patient=" . intval($idPatient);

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error deleting patient information.";
      return false;
    }

    $sql = "DELETE FROM history_tbl";
    $sql .= " WHERE id_patient=" . intval($idPatient);

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error deleting patient information.";
    }

    return $result;
  }
} // end class
?>
