<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: test_edit.php,v 1.13 2006/03/15 20:47:25 jact Exp $
 */

/**
 * test_edit.php
 *
 * Medical test edition process
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: ../medical/test_list.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $onlyDoctor = false;
  $restrictInDemo = true; // To prevent users' malice

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Test_Query.php");
  require_once("../shared/record_log.php"); // record log

  /**
   * Retrieving post vars
   */
  $idPatient = intval($_POST["id_patient"]);
  $idProblem = intval($_POST["id_problem"]);
  $idTest = intval($_POST["id_test"]);

  $errorLocation = "../medical/test_edit_form.php?key=" . $idProblem . "&pat=" . $idPatient . "&test=" . $idTest; // controlling var

  /**
   * Validate data
   */
  $test = new Test();

  $test->setIdTest($_POST["id_test"]);

  require_once("../medical/test_validate_post.php");

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Update medical test
   */
  $testQ = new Test_Query();
  $testQ->connect();

  $testQ->update($test);

  $testQ->close();
  unset($testQ);

  /**
   * Record log process
   */
  recordLog("Test_Query", "UPDATE", array($test->getIdTest()));

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  // To header, without &amp;
  $returnLocation = "../medical/test_list.php?key=" . $idProblem . "&pat=" . $idPatient; // controlling var

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  $info = urlencode($test->getPathFilename(false));
  unset($test);
  header("Location: " . $returnLocation . "&updated=Y&info=" . $info);
?>
