<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: connection_del.php,v 1.13 2006/01/23 23:13:29 jact Exp $
 */

/**
 * connection_del.php
 *
 * Connection between medical problems deletion process
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Connection_Query.php");
  require_once("../shared/record_log.php"); // record log
  require_once("../lib/Check.php");

  /**
   * Retrieving post vars
   */
  $idProblem = intval($_POST["id_problem"]);
  $idConnection = intval($_POST["id_connection"]);
  $idPatient = intval($_POST["id_patient"]);
  $wording = Check::safeText($_POST["wording"]);

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Delete relative
   */
  $connQ = new Connection_Query();
  $connQ->connect();

  /**
   * Record log process (before deleting process)
   */
  recordLog("Connection_Query", "DELETE", array($idProblem, $idConnection));

  $connQ->delete($idProblem, $idConnection);

  $connQ->close();
  unset($connQ);

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  // To header, without &amp;
  $returnLocation = "../medical/connection_list.php?key=" . $idProblem . "&pat=" . $idPatient;

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  $info = urlencode($wording);
  header("Location: " . $returnLocation . "&deleted=Y&info=" . $info);
?>
