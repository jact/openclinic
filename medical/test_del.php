<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: test_del.php,v 1.7 2004/10/04 21:41:00 jact Exp $
 */

/**
 * test_del.php
 ********************************************************************
 * Medical test deletion process
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
  $restrictInDemo = true; // To prevent users' malice

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Test_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../shared/record_log.php"); // record log
  require_once("../lib/validator_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving post vars
  ////////////////////////////////////////////////////////////////////
  $idProblem = intval($_POST["id_problem"]);
  $idTest = intval($_POST["id_test"]);
  $idPatient = intval($_POST["id_patient"]);
  $file = safeText($_POST["file"]);

  ////////////////////////////////////////////////////////////////////
  // Prevent user from aborting script
  ////////////////////////////////////////////////////////////////////
  $oldAbort = ignore_user_abort(true);

  ////////////////////////////////////////////////////////////////////
  // Delete medical test
  ////////////////////////////////////////////////////////////////////
  $testQ = new Test_Query();
  $testQ->connect();
  if ($testQ->isError())
  {
    showQueryError($testQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Record log process (before deleting process)
  ////////////////////////////////////////////////////////////////////
  recordLog($testQ->getTableName(), "DELETE", array($idTest));

  $testQ->delete($idTest);
  if ($testQ->isError())
  {
    $testQ->close();
    showQueryError($testQ);
  }
  $testQ->close();
  unset($testQ);

  //@unlink($file); // do not remove the file because LORTAD

  ////////////////////////////////////////////////////////////////////
  // Reset abort setting
  ////////////////////////////////////////////////////////////////////
  ignore_user_abort($oldAbort);

  // To header, without &amp;
  $returnLocation = "../medical/test_list.php?key=" . $idProblem . "&pat=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Redirect to medical test list to avoid reload problem
  ////////////////////////////////////////////////////////////////////
  $info = urlencode($file);
  header("Location: " . $returnLocation . "&deleted=Y&info=" . $info);
?>
