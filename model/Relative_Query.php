<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Relative_Query.php,v 1.2 2004/04/18 14:40:46 jact Exp $
 */

/**
 * Relative_Query.php
 ********************************************************************
 * Contains the class Relative_Query
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

require_once("../classes/Query.php");

/**
 * Relative_Query data access component for relative table
 ********************************************************************
 * @author jact <jachavar@terra.es>
 * @access public
 ********************************************************************
 * Methods:
 *  mixed select(int $idPatient, int $idRelative = 0)
 *  mixed fetchRelative(void)
 *  bool insert(int $idPatient, int $idRelative)
 *  bool delete(int $idPatient, int $idRelative)
 */
class Relative_Query extends Query
{
  /**
   * mixed select(int $idPatient, int $idRelative = 0)
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @param int $idPatient key of patient
   * @param int $idRelative (optional) key of relative
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function select($idPatient, $idRelative = 0)
  {
    $sql = "SELECT * FROM relative_tbl";
    $sql .= " WHERE id_patient=" . intval($idPatient);
    if ($idRelative > 0)
    {
      $sql .= " AND id_relative=" . intval($idRelative);
    }

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing relative patient information.";
      return false;
    }

    return $this->numRows();
  }

  /**
   * mixed fetchRelative(void)
   ********************************************************************
   * Fetches a row from the query result.
   ********************************************************************
   * @return array returns array or false if no more rows to fetch
   * @access public
   */
  function fetchRelative()
  {
    $array = $this->fetchRow(MYSQL_NUM);

    return $array; // false or array
  }

  /**
   * bool insert(int $idPatient, int $idRelative)
   ********************************************************************
   * Inserts a new relative patient into the database.
   ********************************************************************
   * @param int $idPatient
   * @param int $idRelative
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($idPatient, $idRelative)
  {
    $sql = "INSERT INTO relative_tbl ";
    $sql .= "(id_patient, id_relative) VALUES (";
    $sql .= intval($idPatient) . ", ";
    $sql .= intval($idRelative) . ");";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error inserting new relative patient information.";
      return false;
    }

    $sql = "INSERT INTO relative_tbl VALUES (";
    $sql .= intval($idRelative) . ", ";
    $sql .= intval($idPatient) . ");";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error inserting new relative patient information.";
    }

    return $result;
  }

  /**
   * bool delete(int $idPatient, int $idRelative)
   ********************************************************************
   * Delete a row in the relative table.
   ********************************************************************
   * @param int $idPatient
   * @param int $idRelative
   * @return boolean returns false, if error occurs
   * @access public
   */
  function delete($idPatient, $idRelative)
  {
    $sql = "DELETE FROM relative_tbl";
    $sql .= " WHERE id_patient=" . intval($idPatient);
    $sql .= " AND id_relative=" . intval($idRelative);

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error deleting relative patient information.";
      return false;
    }

    $sql = "DELETE FROM relative_tbl";
    $sql .= " WHERE id_patient=" . intval($idRelative);
    $sql .= " AND id_relative=" . intval($idPatient);

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error deleting relative patient information.";
    }

    return $result;
  }
} // end class
?>
