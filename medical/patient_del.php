<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_del.php,v 1.12 2005/07/19 19:51:13 jact Exp $
 */

/**
 * patient_del.php
 *
 * Patient deletion process
 *
 * Author: jact <jachavar@gmail.com>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/History_Query.php");
  require_once("../classes/Patient_Page_Query.php");
  require_once("../classes/Relative_Query.php"); /* referencial integrity */
  require_once("../classes/DelPatient_Query.php");
  require_once("../classes/Problem_Page_Query.php"); /* referencial integrity */
  require_once("../classes/DelProblem_Query.php"); /* referencial integrity */
  require_once("../shared/record_log.php"); // record log
  require_once("../lib/validator_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving post vars
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_POST["id_patient"]);
  $patName = safeText($_POST["name"]);

  ////////////////////////////////////////////////////////////////////
  // Prevent user from aborting script
  ////////////////////////////////////////////////////////////////////
  $oldAbort = ignore_user_abort(true);

  ////////////////////////////////////////////////////////////////////
  // Delete relatives
  ////////////////////////////////////////////////////////////////////
  $relQ = new Relative_Query();
  $relQ->connect();
  if ($relQ->isError())
  {
    Error::query($relQ);
  }

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
    if ($relQ->isError())
    {
      $relQ->close();
      Error::query($relQ);
    }
  }
  $relQ->close();
  unset($relQ);
  unset($rel);

  ////////////////////////////////////////////////////////////////////
  // Delete patient
  ////////////////////////////////////////////////////////////////////
  $patQ = new Patient_Page_Query();
  $patQ->connect();
  if ($patQ->isError())
  {
    Error::query($patQ);
  }

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    $numRows = $patQ->select($idPatient);
    if ($patQ->isError())
    {
      $patQ->close();
      Error::query($patQ);
    }

    if ( !$numRows )
    {
      $patQ->close();
      include_once("../shared/header.php");

      echo '<p>' . _("That patient does not exist.") . "</p>\n";

      include_once("../shared/footer.php");
      exit();
    }

    $patient = $patQ->fetch();
    if ($patQ->isError())
    {
      $patQ->close();
      Error::fetch($patQ);
    }

    $historyQ = new History_Query();
    $historyQ->connect();
    if ($historyQ->isError())
    {
      Error::query($historyQ);
    }

    $historyQ->selectPersonal($idPatient);
    if ($historyQ->isError())
    {
      $historyQ->close();
      Error::query($historyQ);
    }
    $historyP = $historyQ->fetchPersonal();

    $historyQ->selectFamily($idPatient);
    if ($historyQ->isError())
    {
      $historyQ->close();
      Error::query($historyQ);
    }
    $historyF = $historyQ->fetchFamily();
    //debug($patient); debug($historyP); debug($historyF, "", true);

    $delPatientQ = new DelPatient_Query();
    $delPatientQ->connect();
    if ($delPatientQ->isError())
    {
      Error::query($delPatientQ);
    }

    $delPatientQ->insert($patient, $historyP, $historyF, $_SESSION['userId'], $_SESSION['loginSession']);
    if ($delPatientQ->isError())
    {
      $delPatientQ->close();
      Error::query($delPatientQ);
    }
    unset($delPatientQ);
    unset($patient);
    unset($historyQ);
    unset($historyP);
    unset($historyF);
  }

  ////////////////////////////////////////////////////////////////////
  // Record log process (before deleting process)
  ////////////////////////////////////////////////////////////////////
  recordLog($patQ->getTableName(), "DELETE", array($idPatient));

  $patQ->delete($idPatient);
  if ($patQ->isError())
  {
    $patQ->close();
    Error::query($patQ);
  }

  $patQ->close();
  unset($patQ);

  ////////////////////////////////////////////////////////////////////
  // Delete asociated problems
  ////////////////////////////////////////////////////////////////////
  $problemQ = new Problem_Page_Query();
  $problemQ->connect();
  if ($problemQ->isError())
  {
    Error::query($problemQ);
  }

  ////////////////////////////////////////////////////////////////////
  // First: open problems
  ////////////////////////////////////////////////////////////////////
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
    if ($delProblemQ->isError())
    {
      Error::query($delProblemQ);
    }

    for ($i = 0; $i < $numRows; $i++)
    {
      $delProblemQ->insert($array[$i], $_SESSION['userId'], $_SESSION['loginSession']);
      if ($delProblemQ->isError())
      {
        $delProblemQ->close();
        Error::query($delProblemQ);
      }
    }
    $delProblemQ->close();
    unset($delProblemQ);

    $problemQ = new Problem_Page_Query();
    $problemQ->connect();
    if ($problemQ->isError())
    {
      Error::query($problemQ);
    }

    $table = $problemQ->getTableName();

    ////////////////////////////////////////////////////////////////////
    // Record log process (before deleting process)
    ////////////////////////////////////////////////////////////////////
    for ($i = 0; $i < $numRows; $i++)
    {
      recordLog($table, "DELETE", array($array[$i]->getIdProblem()));
    }

    for ($i = 0; $i < $numRows; $i++)
    {
      $problemQ->delete($array[$i]->getIdProblem());
      if ($problemQ->isError())
      {
        $problemQ->close();
        Error::query($problemQ);
      }
    }
    $problemQ->close();
    unset($problemQ);
    unset($array);
  }

  ////////////////////////////////////////////////////////////////////
  // Afterwards: closed problems
  ////////////////////////////////////////////////////////////////////
  $problemQ = new Problem_Page_Query();
  $problemQ->connect();
  if ($problemQ->isError())
  {
    Error::query($problemQ);
  }

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
    if ($delProblemQ->isError())
    {
      Error::query($delProblemQ);
    }

    for ($i = 0; $i < $numRows; $i++)
    {
      $delProblemQ->insert($array[$i], $_SESSION['userId'], $_SESSION['loginSession']);
      if ($delProblemQ->isError())
      {
        $delProblemQ->close();
        Error::query($delProblemQ);
      }
    }
    $delProblemQ->close();
    unset($delProblemQ);

    $problemQ = new Problem_Page_Query();
    $problemQ->connect();
    if ($problemQ->isError())
    {
      Error::query($problemQ);
    }

    $table = $problemQ->getTableName();

    ////////////////////////////////////////////////////////////////////
    // Record log process (before deleting process)
    ////////////////////////////////////////////////////////////////////
    for ($i = 0; $i < $numRows; $i++)
    {
      recordLog($table, "DELETE", array($array[$i]->getIdProblem()));
    }

    for ($i = 0; $i < $numRows; $i++)
    {
      $problemQ->delete($array[$i]->getIdProblem());
      if ($problemQ->isError())
      {
        $problemQ->close();
        Error::query($problemQ);
      }
    }
    $problemQ->close();
    unset($problemQ);
    unset($array);
  }

  ////////////////////////////////////////////////////////////////////
  // Update session variables
  ////////////////////////////////////////////////////////////////////
  require_once("../medical/visited_list.php");
  deletePatient($idPatient);

  ////////////////////////////////////////////////////////////////////
  // Reset abort setting
  ////////////////////////////////////////////////////////////////////
  ignore_user_abort($oldAbort);

  $returnLocation = "../medical/patient_search_form.php";

  ////////////////////////////////////////////////////////////////////
  // Redirect to patient search form to avoid reload problem
  ////////////////////////////////////////////////////////////////////
  $info = urlencode($patName);
  header("Location: " . $returnLocation . "?deleted=Y&info=" . $info);
?>
