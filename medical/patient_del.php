<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_del.php,v 1.2 2004/04/24 14:52:14 jact Exp $
 */

/**
 * patient_del.php
 ********************************************************************
 * Patient deletion process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || empty($_GET["key"]) || empty($_GET["name"]))
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

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_GET["key"]);
  $patName = $_GET["name"];

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/History_Query.php");
  require_once("../classes/Patient_Query.php");
  require_once("../classes/Relative_Query.php"); /* referencial integrity */
  require_once("../classes/DelPatient_Query.php");
  require_once("../classes/Problem_Query.php"); /* referencial integrity */
  require_once("../classes/DelProblem_Query.php"); /* referencial integrity */
  require_once("../lib/error_lib.php");
  require_once("../shared/record_log.php"); // record log

  ////////////////////////////////////////////////////////////////////
  // Prevent user from aborting script
  ////////////////////////////////////////////////////////////////////
  $oldAbort = ignore_user_abort(true);

  ////////////////////////////////////////////////////////////////////
  // Delete relatives
  ////////////////////////////////////////////////////////////////////
  $relQ = new Relative_Query();
  $relQ->connect();
  if ($relQ->errorOccurred())
  {
    showQueryError($relQ);
  }

  $numRows = $relQ->select($idPatient);

  $rel = array();
  for ($i = 0; $i < $numRows; $i++)
  {
    $rel[] = $relQ->fetchRelative();
  }
  $relQ->freeResult();

  while ($aux = array_shift($rel))
  {
    if ( !$relQ->delete($idPatient, $aux[1]) )
    {
      $relQ->close();
      showQueryError($relQ);
    }
  }
  $relQ->close();
  unset($relQ);
  unset($rel);

  ////////////////////////////////////////////////////////////////////
  // Delete patient
  ////////////////////////////////////////////////////////////////////
  $patQ = new Patient_Query();
  $patQ->connect();
  if ($patQ->errorOccurred())
  {
    showQueryError($patQ);
  }

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    $numRows = $patQ->select($idPatient);
    if ($patQ->errorOccurred())
    {
      $patQ->close();
      showQueryError($patQ);
    }

    if ( !$numRows )
    {
      $patQ->close();
      include_once("../shared/header.php");

      echo '<p>' . _("That patient does not exist.") . "</p>\n";

      include_once("../shared/footer.php");
      exit();
    }

    $patient = $patQ->fetchPatient();

    $historyQ = new History_Query();
    $historyQ->connect();
    if ($historyQ->errorOccurred())
    {
      showQueryError($historyQ);
    }

    if ( !$historyQ->selectPersonal($idPatient) )
    {
      $historyQ->close();
      showQueryError($historyQ);
    }
    $historyP = $historyQ->fetchPersonal();

    if ( !$historyQ->selectFamily($idPatient) )
    {
      $historyQ->close();
      showQueryError($historyQ);
    }
    $historyF = $historyQ->fetchFamily();
    debug($patient); debug($historyP); debug($historyF, "", true);

    $delPatientQ = new DelPatient_Query();
    $delPatientQ->connect();
    if ($delPatientQ->errorOccurred())
    {
      showQueryError($delPatientQ);
    }

    if ( !$delPatientQ->insert($patient, $historyP, $historyF, $_SESSION['userId'], $_SESSION['loginSession']) )
    {
      $delPatientQ->close();
      showQueryError($delPatientQ);
    }
    unset($delPatientQ);
    unset($patient);
    unset($historyQ);
    unset($historyP);
    unset($historyF);
  }

  if ( !$patQ->delete($idPatient) )
  {
    $patQ->close();
    showQueryError($patQ);
  }
  $patQ->close();
  unset($patQ);

  ////////////////////////////////////////////////////////////////////
  // Record log process
  ////////////////////////////////////////////////////////////////////
  recordLog("patient_tbl", "DELETE", $idPatient);

  ////////////////////////////////////////////////////////////////////
  // Delete asociated problems
  ////////////////////////////////////////////////////////////////////
  $problemQ = new Problem_Query();
  $problemQ->connect();
  if ($problemQ->errorOccurred())
  {
    showQueryError($problemQ);
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
      $array[$i] = $problemQ->fetchProblem();
    }
    $problemQ->freeResult();
    $problemQ->close();
    unset($problemQ);

    $delProblemQ = new DelProblem_Query();
    $delProblemQ->connect();
    if ($delProblemQ->errorOccurred())
    {
      showQueryError($delProblemQ);
    }

    for ($i = 0; $i < $numRows; $i++)
    {
      if ( !$delProblemQ->insert($array[$i], $_SESSION['userId'], $_SESSION['loginSession']) )
      {
        $delProblemQ->close();
        showQueryError($delProblemQ);
      }
    }
    $delProblemQ->close();
    unset($delProblemQ);

    $problemQ = new Problem_Query();
    $problemQ->connect();
    if ($problemQ->errorOccurred())
    {
      showQueryError($problemQ);
    }

    for ($i = 0; $i < $numRows; $i++)
    {
      if ( !$problemQ->delete($array[$i]->_idProblem) )
      {
        $problemQ->close();
        showQueryError($problemQ);
      }
    }
    $problemQ->close();
    unset($problemQ);

    for ($i = 0; $i < $numRows; $i++)
    {
      recordLog("problem_tbl", "DELETE", $array[$i]->_idProblem);
    }
    unset($array);
  }

  ////////////////////////////////////////////////////////////////////
  // Afterwards: closed problems
  ////////////////////////////////////////////////////////////////////
  $problemQ = new Problem_Query();
  $problemQ->connect();
  if ($problemQ->errorOccurred())
  {
    showQueryError($problemQ);
  }

  $numRows = $problemQ->selectProblems($idPatient, true);
  if ($numRows)
  {
    $array = array();
    for ($i = 0; $i < $numRows; $i++)
    {
      $array[$i] = $problemQ->fetchProblem();
    }
    $problemQ->freeResult();
    $problemQ->close();
    unset($problemQ);

    $delProblemQ = new DelProblem_Query();
    $delProblemQ->connect();
    if ($delProblemQ->errorOccurred())
    {
      showQueryError($delProblemQ);
    }

    for ($i = 0; $i < $numRows; $i++)
    {
      if ( !$delProblemQ->insert($array[$i], $_SESSION['userId'], $_SESSION['loginSession']) )
      {
        $delProblemQ->close();
        showQueryError($delProblemQ);
      }
    }
    $delProblemQ->close();
    unset($delProblemQ);

    $problemQ = new Problem_Query();
    $problemQ->connect();
    if ($problemQ->errorOccurred())
    {
      showQueryError($problemQ);
    }

    for ($i = 0; $i < $numRows; $i++)
    {
      if ( !$problemQ->delete($array[$i]->_idProblem) )
      {
        $problemQ->close();
        showQueryError($problemQ);
      }
    }
    $problemQ->close();
    unset($problemQ);

    for ($i = 0; $i < $numRows; $i++)
    {
      recordLog("problem_tbl", "DELETE", $array[$i]->_idProblem);
    }
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

  ////////////////////////////////////////////////////////////////////
  // Show success page
  ////////////////////////////////////////////////////////////////////
  $title = _("Delete Patient");
  require_once("../shared/header.php");

  $returnLocation = "../medical/patient_view.php?key=" . $idPatient . "&amp;reset=Y";

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Social Data") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  echo '<p>' . sprintf(_("Patient, %s, has been deleted."), $patName) . "</p>\n";

  //echo '<p><a href="../medical/patient_search_form.php">' . _("Return to Patient Search") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
