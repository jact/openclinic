<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Test_Query.php,v 1.3 2004/06/16 19:08:48 jact Exp $
 */

/**
 * Test_Query.php
 ********************************************************************
 * Contains the class Test_Query
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

require_once("../classes/Query.php");
require_once("../classes/Test.php");

/**
 * Test_Query data access component for medical tests
 ********************************************************************
 * @author jact <jachavar@terra.es>
 * @access public
 ********************************************************************
 * Methods:
 *  mixed getLastId(void)
 *  mixed select(int $idProblem, int $idTest = 0)
 *  mixed fetch(void)
 *  bool insert(Test $test)
 *  bool update(Test $test)
 *  bool delete(int $idTest)
 */
class Test_Query extends Query
{
  /**
   * mixed getLastId(void)
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @return mixed if error occurs returns false, else last insert id
   * @access public
   */
  function getLastId()
  {
    $sql = "SELECT LAST_INSERT_ID() AS last_id";
    $sql .= " FROM medical_test_tbl";

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
   * mixed select(int $idProblem, int $idTest = 0)
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @param int $idProblem key of medical problem
   * @param int $idTest (optional) key of medical test
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function select($idProblem, $idTest = 0)
  {
    $sql = "SELECT * FROM medical_test_tbl";
    $sql .= " WHERE id_problem=" . intval($idProblem);
    if ($idTest > 0)
    {
      $sql .= " AND id_test=" . intval($idTest);
    }

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing medical test information.";
      return false;
    }

    return $this->numRows();
  }

  /**
   * mixed fetch(void)
   ********************************************************************
   * Fetches a row from the query result and populates the Test object.
   ********************************************************************
   * @return Test returns medical test or false if no more tests to fetch
   * @access public
   */
  function fetch()
  {
    $array = $this->fetchRow();
    if ($array == false)
    {
      return false;
    }

    $test = new Test();
    $test->setIdTest(intval($array["id_test"]));
    $test->setIdProblem(intval($array["id_problem"]));
    $test->setDocumentType(urldecode($array["document_type"]));
    $test->setPathFilename(urldecode($array["path_filename"]));

    return $test;
  }

  /**
   * bool insert(Test $test)
   ********************************************************************
   * Inserts a new medical test into the medical tests table.
   ********************************************************************
   * @param Test $test medical test to insert
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($test)
  {
    $sql = "INSERT INTO medical_test_tbl ";
    $sql .= "(id_test, id_problem, document_type, path_filename) VALUES (NULL, ";
    $sql .= $test->getIdProblem() . ", ";
    $sql .= ($test->getDocumentType() == "") ? "NULL, " : "'" . urlencode($test->getDocumentType()) . "', ";
    $sql .= "'" . urlencode($test->getPathFilename()) . "');";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error inserting new medical test information.";
    }

    return $result;
  }

  /**
   * bool update(Test $test)
   ********************************************************************
   * Update a medical test in the medical tests table.
   ********************************************************************
   * @param Test $test medical test to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  function update($test)
  {
    $sql = "UPDATE medical_test_tbl SET";
    $sql .= " document_type=" . (($test->getDocumentType() == "") ? "NULL," : "'" . urlencode($test->getDocumentType()) . "',");
    $sql .= " path_filename='" . urlencode($test->getPathFilename()) . "'";
    $sql .= " WHERE id_test=" . $test->getIdTest() . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error updating medical test information.";
    }

    return $result;
  }

  /**
   * bool delete(int $idTest)
   ********************************************************************
   * Delete a row in the medical test table.
   ********************************************************************
   * @param int $idTest
   * @return boolean returns false, if error occurs
   * @access public
   */
  function delete($idTest)
  {
    $sql = "DELETE FROM medical_test_tbl";
    $sql .= " WHERE id_test=" . intval($idTest);

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error deleting medical test information.";
    }

    return $result;
  }
} // end class
?>
