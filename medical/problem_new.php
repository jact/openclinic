<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_new.php,v 1.5 2004/07/24 16:17:30 jact Exp $
 */

/**
 * problem_new.php
 ********************************************************************
 * Medical Problem addition process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: ../medical/problem_new_form.php");
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
  require_once("../lib/error_lib.php");
  require_once("../shared/record_log.php"); // record log

  ////////////////////////////////////////////////////////////////////
  // Retrieving post var
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_POST["id_patient"]);

  $errorLocation = "../medical/problem_new_form.php?key=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Validate data
  ////////////////////////////////////////////////////////////////////
  $problem = new Problem();

  require_once("../medical/problem_validate_post.php");

  ////////////////////////////////////////////////////////////////////
  // Prevent user from aborting script
  ////////////////////////////////////////////////////////////////////
  $oldAbort = ignore_user_abort(true);

  ////////////////////////////////////////////////////////////////////
  // Insert new medical problem
  ////////////////////////////////////////////////////////////////////
  $problemQ = new Problem_Query();
  $problemQ->connect();
  if ($problemQ->isError())
  {
    showQueryError($problemQ);
  }

  $problemQ->insert($problem);
  if ($problemQ->isError())
  {
    $problemQ->close();
    showQueryError($problemQ);
  }

  $idProblem = $problemQ->getLastId();
  if ($problemQ->isError())
  {
    $problemQ->close();
    showQueryError($problemQ);
  }

  $table = $problemQ->getTableName();

  $problemQ->close();
  unset($problemQ);

  ////////////////////////////////////////////////////////////////////
  // Record log process
  ////////////////////////////////////////////////////////////////////
  recordLog($table, "INSERT", array($idProblem));

  ////////////////////////////////////////////////////////////////////
  // Reset abort setting
  ////////////////////////////////////////////////////////////////////
  ignore_user_abort($oldAbort);

  ////////////////////////////////////////////////////////////////////
  // Destroy form values and errors
  ////////////////////////////////////////////////////////////////////
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  $returnLocation = "../medical/problem_list.php?key=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Redirect to problem list to avoid reload problem
  ////////////////////////////////////////////////////////////////////
  $info = urlencode($problem->getWording());
  unset($problem);
  header("Location: " . $returnLocation . "&added=Y&info=" . $info);
?>
