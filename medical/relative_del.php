<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: relative_del.php,v 1.7 2004/10/04 21:38:26 jact Exp $
 */

/**
 * relative_del.php
 ********************************************************************
 * Relation between patients deletion process
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
  $nav = "social";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Relative_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../shared/record_log.php"); // record log
  require_once("../lib/validator_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving post vars
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_POST["id_patient"]);
  $idRelative = intval($_POST["id_relative"]);
  $relName = safeText($_POST["name"]);

  ////////////////////////////////////////////////////////////////////
  // Prevent user from aborting script
  ////////////////////////////////////////////////////////////////////
  $oldAbort = ignore_user_abort(true);

  ////////////////////////////////////////////////////////////////////
  // Delete relative
  ////////////////////////////////////////////////////////////////////
  $relQ = new Relative_Query();
  $relQ->connect();
  if ($relQ->isError())
  {
    showQueryError($relQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Record log process (before deleting process)
  ////////////////////////////////////////////////////////////////////
  recordLog($relQ->getTableName(), "DELETE", array($idPatient, $idRelative));

  $relQ->delete($idPatient, $idRelative);
  if ($relQ->isError())
  {
    $relQ->close();
    showQueryError($relQ);
  }
  $relQ->close();
  unset($relQ);

  ////////////////////////////////////////////////////////////////////
  // Reset abort setting
  ////////////////////////////////////////////////////////////////////
  ignore_user_abort($oldAbort);

  $returnLocation = "../medical/relative_list.php?key=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Redirect to relative list to avoid reload problem
  ////////////////////////////////////////////////////////////////////
  $info = urlencode($relName);
  header("Location: " . $returnLocation . "&deleted=Y&info=" . $info);
?>
