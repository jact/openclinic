<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: connection_del.php,v 1.7 2004/07/24 16:17:30 jact Exp $
 */

/**
 * connection_del.php
 ********************************************************************
 * Connection between medical problems deletion process
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

  ////////////////////////////////////////////////////////////////////
  // Retrieving post vars
  ////////////////////////////////////////////////////////////////////
  $idProblem = intval($_POST["id_problem"]);
  $idConnection = intval($_POST["id_connection"]);
  $idPatient = intval($_POST["id_patient"]);
  $wording = $_POST["wording"];

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Connection_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../shared/record_log.php"); // record log

  ////////////////////////////////////////////////////////////////////
  // Prevent user from aborting script
  ////////////////////////////////////////////////////////////////////
  $oldAbort = ignore_user_abort(true);

  ////////////////////////////////////////////////////////////////////
  // Delete relative
  ////////////////////////////////////////////////////////////////////
  $connQ = new Connection_Query();
  $connQ->connect();
  if ($connQ->isError())
  {
    showQueryError($connQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Record log process (before deleting process)
  ////////////////////////////////////////////////////////////////////
  recordLog($connQ->getTableName(), "DELETE", array($idProblem, $idConnection));

  $connQ->delete($idProblem, $idConnection);
  if ($connQ->isError())
  {
    $connQ->close();
    showQueryError($connQ);
  }

  $connQ->close();
  unset($connQ);

  ////////////////////////////////////////////////////////////////////
  // Reset abort setting
  ////////////////////////////////////////////////////////////////////
  ignore_user_abort($oldAbort);

  // To header, without &amp;
  $returnLocation = "../medical/connection_list.php?key=" . $idProblem . "&pat=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Redirect to connection problem list to avoid reload problem
  ////////////////////////////////////////////////////////////////////
  $info = urlencode($wording);
  header("Location: " . $returnLocation . "&deleted=Y&info=" . $info);
?>
