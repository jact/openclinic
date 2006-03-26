<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: visited_list.php,v 1.14 2006/03/26 15:20:50 jact Exp $
 */

/**
 * visited_list.php
 *
 * Set of functions to manage visited patients array
 *
 * @author jact <jachavar@gmail.com>
 * @since 0.4
 * @todo convert in class?
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
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

  require_once("../classes/Patient_Page_Query.php");

  /**
   * string getPatientName(int $idPatient)
   *
   * Returns a patient name.
   *
   * @param int $idPatient key of patient to show header
   * @return string patient name
   * @access public
   */
  function getPatientName($idPatient)
  {
    $patQ = new Patient_Page_Query();
    $patQ->connect();

    if ( !$patQ->select($idPatient) )
    {
      return _("That patient does not exist.");
    }

    $pat = $patQ->fetch();
    if ( !$pat )
    {
      $patQ->close();
      Error::fetch($patQ);
    }

    $patQ->freeResult();
    $patQ->close();

    $patName = $pat->getFirstName() . " " . $pat->getSurname1() . " " . $pat->getSurname2();

    unset($patQ);
    unset($pat);

    return $patName;
  }

  /**
   * array deleteItemArray(mixed $key, array &$array)
   *
   * Delete a item from a associative array.
   *
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
   *
   * Add a visited patient to the list.
   *
   * @param int $idPatient key of patient to show header
   * @param string $patientName (optional) name of patient
   * @return void
   * @access public
   * @see OPEN_DEMO, OPEN_VISITED_ITEMS
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
   *
   * Delete a visited patient from the list.
   *
   * @param int $idPatient key of patient to show header
   * @return void
   * @access public
   * @see OPEN_DEMO
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
