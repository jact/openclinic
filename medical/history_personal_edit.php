<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_personal_edit.php,v 1.4 2004/07/24 16:17:30 jact Exp $
 */

/**
 * history_personal_edit.php
 ********************************************************************
 * Personal antecedents of a patient edition process
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

  $history->setBirthGrowth($_POST["birth_growth"]);
  $_POST["birth_growth"] = $history->getBirthGrowth();

  $history->setGrowthSexuality($_POST["growth_sexuality"]);
  $_POST["growth_sexuality"] = $history->getGrowthSexuality();

  $history->setFeed($_POST["feed"]);
  $_POST["feed"] = $history->getFeed();

  $history->setHabits($_POST["habits"]);
  $_POST["habits"] = $history->getHabits();

  $history->setPeristalticConditions($_POST["peristaltic_conditions"]);
  $_POST["peristaltic_conditions"] = $history->getPeristalticConditions();

  $history->setPsychological($_POST["psychological"]);
  $_POST["psychological"] = $history->getPsychological();

  $history->setChildrenComplaint($_POST["children_complaint"]);
  $_POST["children_complaint"] = $history->getChildrenComplaint();

  $history->setVenerealDisease($_POST["venereal_disease"]);
  $_POST["venereal_disease"] = $history->getVenerealDisease();

  $history->setAccidentSurgicalOperation($_POST["accident_surgical_operation"]);
  $_POST["accident_surgical_operation"] = $history->getAccidentSurgicalOperation();

  $history->setMedicinalIntolerance($_POST["medicinal_intolerance"]);
  $_POST["medicinal_intolerance"] = $history->getMedicinalIntolerance();

  $history->setMentalIllness($_POST["mental_illness"]);
  $_POST["mental_illness"] = $history->getMentalIllness();

  if ( !$history->validateData() )
  {
    //$pageErrors["???"] = $history->get???Error();
    //$pageErrors["???"] = $history->get???Error();

    $_SESSION["postVars"] = $_POST;
    //$_SESSION["pageErrors"] = $pageErrors;

    header("Location: ../medical/history_personal_edit_form.php?key=" . $idPatient);
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Prevent user from aborting script
  ////////////////////////////////////////////////////////////////////
  $oldAbort = ignore_user_abort(true);

  ////////////////////////////////////////////////////////////////////
  // Update personal antecedents
  ////////////////////////////////////////////////////////////////////
  $historyQ = new History_Query();
  $historyQ->connect();
  if ($historyQ->isError())
  {
    showQueryError($historyQ);
  }

  $historyQ->updatePersonal($history);
  if ($historyQ->isError())
  {
    $historyQ->close();
    showQueryError($historyQ);
  }

  $table = $historyQ->getTableName();

  $historyQ->close();
  unset($historyQ);

  ////////////////////////////////////////////////////////////////////
  // Record log process
  ////////////////////////////////////////////////////////////////////
  recordLog($table, "UPDATE", array($_POST["id_patient"]));

  ////////////////////////////////////////////////////////////////////
  // Reset abort setting
  ////////////////////////////////////////////////////////////////////
  ignore_user_abort($oldAbort);

  ////////////////////////////////////////////////////////////////////
  // Destroy form values and errors
  ////////////////////////////////////////////////////////////////////
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  header("Location: ../medical/history_personal_edit_form.php?key=" . $idPatient . "&reset=Y&updated=Y");
?>
