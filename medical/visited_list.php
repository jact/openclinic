<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: visited_list.php,v 1.8 2004/10/17 14:57:04 jact Exp $
 */

/**
 * visited_list.php
 ********************************************************************
 * Set of functions to manage visited patients array
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * @since 0.4
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['PATH_TRANSLATED'])
  {
    header("Location: ../index.php");
    exit();
  }

/**
 * Functions:
 *  string getPatientName(int $idPatient)
 *  array deleteItemArray(mixed $key, array &$array)
 *  void addPatient(int $idPatient, string $patientName = "")
 *  void deletePatient(int $idPatient)
 */

  require_once("../classes/Patient_Query.php");

  /**
   * string getPatientName(int $idPatient)
   ********************************************************************
   * Returns a patient name.
   ********************************************************************
   * @param int $idPatient key of patient to show header
   * @return string patient name
   * @access public
   */
  function getPatientName($idPatient)
  {
    $patQ = new Patient_Query();
    $patQ->connect();
    if ($patQ->isError())
    {
      showQueryError($patQ);
    }

    $numRows = $patQ->select($idPatient);
    if ($patQ->isError())
    {
      $patQ->close();
      showQueryError($patQ);
    }

    if ( !$numRows )
    {
      return _("That patient does not exist.");
    }

    $pat = $patQ->fetch();
    if ($patQ->isError())
    {
      $patQ->close();
      showFetchError($patQ);
    }

    $patQ->freeResult();

    $patName = $pat->getFirstName() . " " . $pat->getSurname1() . " " . $pat->getSurname2();

    unset($patQ);
    unset($pat);

    return $patName;
  }

  /**
   * array deleteItemArray(mixed $key, array &$array)
   ********************************************************************
   * Delete a item from a associative array.
   ********************************************************************
   * @param mixed $key key of element to delete of the array
   * @param array &$array array to transform
   * @return array
   * @access public
   */
  function deleteItemArray($key, &$array)
  {
    if ( !array_key_exists($key, $array) )
    {
      return $array;
    }

    $result = null;
    foreach ($array as $arrKey => $arrValue)
    {
      if ($arrKey != $key)
      {
        $result[$arrKey] = $arrValue;
      }
    }

    return $result;
  }

  /**
   * void addPatient(int $idPatient, string $patientName = "")
   ********************************************************************
   * Add a visited patient to the list.
   ********************************************************************
   * @param int $idPatient key of patient to show header
   * @param string $patientName (optional) name of patient
   * @return void
   * @access public
   */
  function addPatient($idPatient, $patientName = "")
  {
    if (defined("OPEN_DEMO") && OPEN_DEMO)
    {
      return;
    }

    if (empty($patientName))
    {
      $patientName = getPatientName($idPatient);
    }

    /*if (gettype($_SESSION["visitedPatients"]) == "array")
    {
      if ( !array_key_exists($idPatient, $_SESSION["visitedPatients"]) )
      {
        $_SESSION["visitedPatients"][$idPatient] = $patientName;
      }
    }
    else
    {
      $_SESSION["visitedPatients"][$idPatient] = $patientName;
    }*/
    $_SESSION["visitedPatients"][$idPatient] = $patientName;
    $_SESSION["visitedPatients"] = array_unique($_SESSION["visitedPatients"]);

    $size = sizeof($_SESSION["visitedPatients"]);
    if ($size > OPEN_VISITED_ITEMS)
    {
      reset($_SESSION["visitedPatients"]);
      $aux = array_keys($_SESSION["visitedPatients"], current($_SESSION["visitedPatients"]));
      $_SESSION["visitedPatients"] = deleteItemArray($aux[0], $_SESSION["visitedPatients"]);
    }
  }

  /**
   * void deletePatient(int $idPatient)
   ********************************************************************
   * Delete a visited patient from the list.
   ********************************************************************
   * @param int $idPatient key of patient to show header
   * @return void
   * @access public
   */
  function deletePatient($idPatient)
  {
    if (defined("OPEN_DEMO") && OPEN_DEMO)
    {
      return;
    }

    $_SESSION["visitedPatients"] = deleteItemArray($idPatient, $_SESSION["visitedPatients"]);
  }
?>
