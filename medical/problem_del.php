<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_del.php,v 1.7 2004/07/10 16:59:23 jact Exp $
 */

/**
 * problem_del.php
 ********************************************************************
 * Medical Problem deletion process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
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
  $nav = "problems";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Problem_Query.php");
  require_once("../classes/Connection_Query.php"); /* referencial integrity */
  require_once("../classes/DelProblem_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../shared/record_log.php"); // record log

  ////////////////////////////////////////////////////////////////////
  // Retrieving post vars
  ////////////////////////////////////////////////////////////////////
  $idProblem = intval($_POST["id_problem"]);
  $idPatient = intval($_POST["id_patient"]);
  $wording = $_POST["wording"];

  ////////////////////////////////////////////////////////////////////
  // Prevent user from aborting script
  ////////////////////////////////////////////////////////////////////
  $oldAbort = ignore_user_abort(true);

  ////////////////////////////////////////////////////////////////////
  // Delete medical problems connections
  ////////////////////////////////////////////////////////////////////
  $connQ = new Connection_Query();
  $connQ->connect();
  if ($connQ->isError())
  {
    showQueryError($connQ);
  }

  $numRows = $connQ->select($idProblem);

  $conn = array();
  for ($i = 0; $i < $numRows; $i++)
  {
    $conn[] = $connQ->fetch();
  }
  $connQ->freeResult();

  while ($aux = array_shift($conn))
  {
    $connQ->delete($idProblem, $aux[1]);
    if ($connQ->isError())
    {
      $connQ->close();
      showQueryError($connQ);
    }
  }
  $connQ->close();
  unset($connQ);
  unset($conn);

  ////////////////////////////////////////////////////////////////////
  // Delete problem
  ////////////////////////////////////////////////////////////////////
  $problemQ = new Problem_Query();
  $problemQ->connect();
  if ($problemQ->isError())
  {
    showQueryError($problemQ);
  }

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    $numRows = $problemQ->select($idProblem);
    if ($problemQ->isError())
    {
      $problemQ->close();
      showQueryError($problemQ);
    }

    if ( !$numRows )
    {
      $problemQ->close();
      include_once("../shared/header.php");

      echo '<p>' . _("That medical problem does not exist.") . "</p>\n";

      include_once("../shared/footer.php");
      exit();
    }

    $problem = $problemQ->fetch();
    if ($problemQ->isError())
    {
      $problemQ->close();
      showFetchError($problemQ);
    }

    $delProblemQ = new DelProblem_Query();
    $delProblemQ->connect();
    if ($delProblemQ->isError())
    {
      showQueryError($delProblemQ);
    }

    $delProblemQ->insert($problem, $_SESSION['userId'], $_SESSION['loginSession']);
    if ($delProblemQ->isError())
    {
      $delProblemQ->close();
      showQueryError($delProblemQ);
    }
    unset($delProblemQ);
    unset($problem);
  }

  $problemQ->delete($idProblem);
  if ($problemQ->isError())
  {
    $problemQ->close();
    showQueryError($problemQ);
  }
  $problemQ->close();
  unset($problemQ);

  ////////////////////////////////////////////////////////////////////
  // Record log process
  ////////////////////////////////////////////////////////////////////
  recordLog("problem_tbl", "DELETE", $idProblem);

  ////////////////////////////////////////////////////////////////////
  // Reset abort setting
  ////////////////////////////////////////////////////////////////////
  ignore_user_abort($oldAbort);

  $returnLocation = "../medical/problem_list.php?key=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Redirect to problem list to avoid reload problem
  ////////////////////////////////////////////////////////////////////
  $info = urlencode($wording);
  header("Location: " . $returnLocation . "&deleted=Y&info=" . $info);
?>
