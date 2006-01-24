<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_new.php,v 1.11 2006/01/24 19:59:42 jact Exp $
 */

/**
 * problem_new.php
 *
 * Medical Problem addition process
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: ../medical/problem_new_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Problem_Page_Query.php");
  require_once("../shared/record_log.php"); // record log

  /**
   * Retrieving post var
   */
  $idPatient = intval($_POST["id_patient"]);

  $errorLocation = "../medical/problem_new_form.php?key=" . $idPatient; // controlling var

  /**
   * Validate data
   */
  $problem = new Problem();

  require_once("../medical/problem_validate_post.php");

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Insert new medical problem
   */
  $problemQ = new Problem_Page_Query();
  $problemQ->connect();

  $problemQ->insert($problem);
  $idProblem = $problemQ->getLastId();

  $problemQ->close();
  unset($problemQ);

  /**
   * Record log process
   */
  recordLog("Problem_Page_Query", "INSERT", array($idProblem));

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  $returnLocation = "../medical/problem_list.php?key=" . $idPatient; // controlling var

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  $info = urlencode($problem->getWording()) . ($problem->getClosingDate(false) ? "&closed=Y" : "");
  unset($problem);
  header("Location: " . $returnLocation . "&added=Y&info=" . $info);
?>
