<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_family_edit.php,v 1.3 2004/07/07 17:22:28 jact Exp $
 */

/**
 * history_family_edit.php
 ********************************************************************
 * Family antecedents of a patient edition process
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
  $nav = "history";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/History_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../shared/record_log.php"); // record log

  ////////////////////////////////////////////////////////////////////
  // Retrieving post var
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_POST["id_patient"]);

  ////////////////////////////////////////////////////////////////////
  // Validate data
  ////////////////////////////////////////////////////////////////////
  $history = new History();

  $history->setIdPatient($_POST["id_patient"]);

  $history->setParentsStatusHealth($_POST["parents_status_health"]);
  $_POST["parents_status_health"] = $history->getParentsStatusHealth();

  $history->setBrothersStatusHealth($_POST["brothers_status_health"]);
  $_POST["brothers_status_health"] = $history->getBrothersStatusHealth();

  $history->setSpouseChildsStatusHealth($_POST["spouse_childs_status_health"]);
  $_POST["spouse_childs_status_health"] = $history->getSpouseChildsStatusHealth();

  $history->setFamilyIllness($_POST["family_illness"]);
  $_POST["family_illness"] = $history->getFamilyIllness();

  if ( !$history->validateData() )
  {
    //$pageErrors["???"] = $history->get???Error();
    //$pageErrors["???"] = $history->get???Error();

    $_SESSION["postVars"] = $_POST;
    //$_SESSION["pageErrors"] = $pageErrors;

    header("Location: ../medical/history_family_edit_form.php?key=" . $idPatient);
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Prevent user from aborting script
  ////////////////////////////////////////////////////////////////////
  $oldAbort = ignore_user_abort(true);

  ////////////////////////////////////////////////////////////////////
  // Update family antecedents
  ////////////////////////////////////////////////////////////////////
  $historyQ = new History_Query();
  $historyQ->connect();
  if ($historyQ->isError())
  {
    showQueryError($historyQ);
  }

  $historyQ->updateFamily($history);
  if ($historyQ->isError())
  {
    $historyQ->close();
    showQueryError($historyQ);
  }
  $historyQ->close();
  unset($historyQ);

  ////////////////////////////////////////////////////////////////////
  // Record log process
  ////////////////////////////////////////////////////////////////////
  recordLog("history_tbl", "UPDATE", $idPatient);

  ////////////////////////////////////////////////////////////////////
  // Reset abort setting
  ////////////////////////////////////////////////////////////////////
  ignore_user_abort($oldAbort);

  ////////////////////////////////////////////////////////////////////
  // Destroy form values and errors
  ////////////////////////////////////////////////////////////////////
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  header("Location: ../medical/history_family_edit_form.php?key=" . $idPatient . "&reset=Y&updated=Y");
?>
