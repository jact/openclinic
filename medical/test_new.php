<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: test_new.php,v 1.6 2004/10/04 21:42:40 jact Exp $
 */

/**
 * test_new.php
 ********************************************************************
 * Medical test addition process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: ../medical/test_new_form.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = false;
  $restrictInDemo = true; // To prevent users' malice

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Test_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../shared/record_log.php"); // record log

  ////////////////////////////////////////////////////////////////////
  // Retrieving post vars
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_POST["id_patient"]);
  $idProblem = intval($_POST["id_problem"]);

  ////////////////////////////////////////////////////////////////////
  // Validate data
  ////////////////////////////////////////////////////////////////////
  $test = new Test();

  require_once("../medical/test_validate_post.php");

  ////////////////////////////////////////////////////////////////////
  // Prevent user from aborting script
  ////////////////////////////////////////////////////////////////////
  $oldAbort = ignore_user_abort(true);

  ////////////////////////////////////////////////////////////////////
  // Insert new medical test
  ////////////////////////////////////////////////////////////////////
  $testQ = new Test_Query();
  $testQ->connect();
  if ($testQ->isError())
  {
    showQueryError($testQ);
  }

  $testQ->insert($test);
  if ($testQ->isError())
  {
    $testQ->close();
    showQueryError($testQ);
  }

  $idTest = $testQ->getLastId();
  if ($testQ->isError())
  {
    $testQ->close();
    showQueryError($testQ);
  }

  $table = $testQ->getTableName();

  $testQ->close();
  unset($testQ);

  ////////////////////////////////////////////////////////////////////
  // Record log process
  ////////////////////////////////////////////////////////////////////
  recordLog($table, "INSERT", array($idTest));

  ////////////////////////////////////////////////////////////////////
  // Reset abort setting
  ////////////////////////////////////////////////////////////////////
  ignore_user_abort($oldAbort);

  ////////////////////////////////////////////////////////////////////
  // Destroy form values and errors
  ////////////////////////////////////////////////////////////////////
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  // To header, without &amp;
  $returnLocation = "../medical/test_list.php?key=" . $idProblem . "&pat=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Redirect to medical test list to avoid reload problem
  ////////////////////////////////////////////////////////////////////
  $info = urlencode($test->getPathFilename());
  unset($test);
  header("Location: " . $returnLocation . "&added=Y&info=" . $info);
?>
