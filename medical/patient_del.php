<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_del.php,v 1.18 2006/03/12 18:49:30 jact Exp $
 */

/**
 * patient_del.php
 *
 * Patient deletion process
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/History_Query.php");
  require_once("../classes/Patient_Page_Query.php");
  require_once("../classes/Relative_Query.php"); // referencial integrity
  require_once("../classes/DelPatient_Query.php");
  require_once("../classes/Problem_Page_Query.php"); // referencial integrity
  require_once("../classes/DelProblem_Query.php"); // referencial integrity
  require_once("../shared/record_log.php"); // record log

  /**
   * Retrieving post vars
   */
  $idPatient = intval($_POST["id_patient"]);
  $patName = Check::safeText($_POST["name"]);

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Delete relatives
   */
  $relQ = new Relative_Query();
  $relQ->connect();

  $numRows = $relQ->select($idPatient);

  $rel = array();
  for ($i = 0; $i < $numRows; $i++)
  {
    $rel[] = $relQ->fetch();
  }
  $relQ->freeResult();

  while ($aux = array_shift($rel))
  {
    $relQ->delete($idPatient, $aux[1]);
  }
  $relQ->close();
  unset($relQ);
  unset($rel);

  /**
   * Delete patient
   */
  $patQ = new Patient_Page_Query();
  $patQ->connect();

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    if ( !$patQ->select($idPatient) )
    {
      $patQ->close();
      include_once("../shared/header.php");

      HTML::message(_("That patient does not exist."), OPEN_MSG_ERROR);

      include_once("../shared/footer.php");
      exit();
    }

    $patient = $patQ->fetch();
    if ( !$patient )
    {
      $patQ->close();
      Error::fetch($patQ);
    }

    $historyQ = new History_Query();
    $historyQ->connect();

    $historyQ->selectPersonal($idPatient);
    $historyP = $historyQ->fetch();

    $historyQ->selectFamily($idPatient);
    $historyF = $historyQ->fetch();
    //Error::debug($patient); Error::debug($historyP); Error::debug($historyF, "", true);

    $delPatientQ = new DelPatient_Query();
    $delPatientQ->connect();

    $delPatientQ->insert($patient, $historyP, $historyF, $_SESSION['userId'], $_SESSION['loginSession']);

    unset($delPatientQ);
    unset($patient);
    unset($historyQ);
    unset($historyP);
    unset($historyF);
  }

  /**
   * Record log process (before deleting process)
   */
  recordLog("Patient_Page_Query", "DELETE", array($idPatient));

  $patQ->delete($idPatient);

  $patQ->close();
  unset($patQ);

  /**
   * Delete asociated problems
   */
  $problemQ = new Problem_Page_Query();
  $problemQ->connect();

  /**
   * First: open problems
   */
  $numRows = $problemQ->selectProblems($idPatient, false);
  if ($numRows)
  {
    $array = array();
    for ($i = 0; $i < $numRows; $i++)
    {
      $array[$i] = $problemQ->fetch();
    }
    $problemQ->freeResult();
    $problemQ->close();
    unset($problemQ);

    $delProblemQ = new DelProblem_Query();
    $delProblemQ->connect();

    for ($i = 0; $i < $numRows; $i++)
    {
      $delProblemQ->insert($array[$i], $_SESSION['userId'], $_SESSION['loginSession']);
    }
    $delProblemQ->close();
    unset($delProblemQ);

    $problemQ = new Problem_Page_Query();
    $problemQ->connect();

    /**
     * Record log process (before deleting process)
     */
    for ($i = 0; $i < $numRows; $i++)
    {
      recordLog("Problem_Page_Query", "DELETE", array($array[$i]->getIdProblem()));
    }

    for ($i = 0; $i < $numRows; $i++)
    {
      $problemQ->delete($array[$i]->getIdProblem());
    }
    $problemQ->close();
    unset($problemQ);
    unset($array);
  }

  /**
   * Afterwards: closed problems
   */
  $problemQ = new Problem_Page_Query();
  $problemQ->connect();

  $numRows = $problemQ->selectProblems($idPatient, true);
  if ($numRows)
  {
    $array = array();
    for ($i = 0; $i < $numRows; $i++)
    {
      $array[$i] = $problemQ->fetch();
    }
    $problemQ->freeResult();
    $problemQ->close();
    unset($problemQ);

    $delProblemQ = new DelProblem_Query();
    $delProblemQ->connect();

    for ($i = 0; $i < $numRows; $i++)
    {
      $delProblemQ->insert($array[$i], $_SESSION['userId'], $_SESSION['loginSession']);
    }
    $delProblemQ->close();
    unset($delProblemQ);

    $problemQ = new Problem_Page_Query();
    $problemQ->connect();

    /**
     * Record log process (before deleting process)
     */
    for ($i = 0; $i < $numRows; $i++)
    {
      recordLog("Problem_Page_Query", "DELETE", array($array[$i]->getIdProblem()));
    }

    for ($i = 0; $i < $numRows; $i++)
    {
      $problemQ->delete($array[$i]->getIdProblem());
    }
    $problemQ->close();
    unset($problemQ);
    unset($array);
  }

  /**
   * Update session variables
   */
  require_once("../medical/visited_list.php");
  deletePatient($idPatient);

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  $returnLocation = "../medical/patient_search_form.php";

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  $info = urlencode($patName);
  header("Location: " . $returnLocation . "?deleted=Y&info=" . $info);
?>
