<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_edit.php,v 1.4 2004/07/10 16:59:23 jact Exp $
 */

/**
 * problem_edit.php
 ********************************************************************
 * Medical Problem edition process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to medical problems list if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: ../medical/problem_list.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = false;

  ////////////////////////////////////////////////////////////////////
  // Retrieving post var
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_POST["id_patient"]);

  $errorLocation = "../medical/problem_edit_form.php?key=" . $_POST["id_problem"] . "&pat=" . $idPatient;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Problem_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../shared/record_log.php"); // record log

  ////////////////////////////////////////////////////////////////////
  // Validate data
  ////////////////////////////////////////////////////////////////////
  $problem = new Problem();

  $problem->setIdProblem($_POST["id_problem"]);

  require_once("../medical/problem_validate_post.php");

  ////////////////////////////////////////////////////////////////////
  // Prevent user from aborting script
  ////////////////////////////////////////////////////////////////////
  $oldAbort = ignore_user_abort(true);

  ////////////////////////////////////////////////////////////////////
  // Update problem
  ////////////////////////////////////////////////////////////////////
  $problemQ = new Problem_Query();
  $problemQ->connect();
  if ($problemQ->isError())
  {
    showQueryError($problemQ);
  }

  $problemQ->update($problem);
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
  recordLog("problem_tbl", "UPDATE", $_POST["id_problem"]);

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
  header("Location: " . $returnLocation . "&updated=Y&info=" . $info);
?>
