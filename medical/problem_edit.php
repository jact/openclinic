<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_edit.php,v 1.12 2005/08/15 16:39:39 jact Exp $
 */

/**
 * problem_edit.php
 *
 * Medical Problem edition process
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Checking for post vars. Go back to medical problems list if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: ../medical/problem_list.php");
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
   * Retrieving post vars
   */
  $idPatient = intval($_POST["id_patient"]);
  $idProblem = intval($_POST["id_problem"]);

  $errorLocation = "../medical/problem_edit_form.php?key=" . $idProblem . "&pat=" . $idPatient; // controlling var

  /**
   * Validate data
   */
  $problem = new Problem();

  $problem->setIdProblem($_POST["id_problem"]);

  require_once("../medical/problem_validate_post.php");

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Update problem
   */
  $problemQ = new Problem_Page_Query();
  $problemQ->connect();
  if ($problemQ->isError())
  {
    Error::query($problemQ);
  }

  $problemQ->update($problem);
  if ($problemQ->isError())
  {
    $problemQ->close();
    Error::query($problemQ);
  }

  $problemQ->close();
  unset($problemQ);

  /**
   * Record log process
   */
  recordLog("Problem_Page_Query", "UPDATE", array($idProblem));

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
  header("Location: " . $returnLocation . "&updated=Y&info=" . $info);
?>
