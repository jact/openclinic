<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_personal_edit_form.php,v 1.13 2006/03/15 20:44:17 jact Exp $
 */

/**
 * history_personal_edit_form.php
 *
 * Edition screen of a patient personal antecedents
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "history";
  $onlyDoctor = false;

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/History_Query.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError

  /**
   * Retrieving get vars
   */
  $idPatient = intval($_GET["key"]);

  /**
   * Search database
   */
  $historyQ = new History_Query();
  $historyQ->connect();

  if ( !$historyQ->selectPersonal($idPatient) )
  {
    $historyQ->close();
    include_once("../shared/header.php");

    HTML::message(_("That patient does not exist."), OPEN_MSG_ERROR);

    include_once("../shared/footer.php");
    exit();
  }

  $history = $historyQ->fetch();
  if ($history)
  {
    $formVar["id_patient"] = $history->getIdPatient();
    $formVar["birth_growth"] = $history->getBirthGrowth();
    $formVar["growth_sexuality"] = $history->getGrowthSexuality();
    $formVar["feed"] = $history->getFeed();
    $formVar["habits"] = $history->getHabits();
    $formVar["peristaltic_conditions"] = $history->getPeristalticConditions();
    $formVar["psychological"] = $history->getPsychological();
    $formVar["children_complaint"] = $history->getChildrenComplaint();
    $formVar["venereal_disease"] = $history->getVenerealDisease();
    $formVar["accident_surgical_operation"] = $history->getAccidentSurgicalOperation();
    $formVar["medicinal_intolerance"] = $history->getMedicinalIntolerance();
    $formVar["mental_illness"] = $history->getMentalIllness();
  }
  else
  {
    Error::fetch($historyQ, false);
  }
  $historyQ->freeResult();
  $historyQ->close();
  unset($historyQ);
  unset($history);

  /**
   * Show page
   */
  $title = _("Edit Personal Antecedents");
  $focusFormField = "birth_growth"; // to avoid JavaScript mistakes in demo version
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  $returnLocation = "../medical/history_personal_view.php?key=" . $idPatient;

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Clinic History") => "../medical/history_list.php?key=" . $idPatient,
    _("View Personal Antecedents") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  showPatientHeader($idPatient);

  require_once("../shared/form_errors_msg.php");

  /**
   * Display update message if coming from setting_edit with a successful update.
   */
  if (isset($_GET["updated"]))
  {
    HTML::message(_("Personal Antecedents have been updated."), OPEN_MSG_INFO);
  }

  /**
   * Edit form
   */
  echo '<form method="post" action="../medical/history_personal_edit.php">' . "\n";

  Form::hidden("id_patient", $idPatient);

  require_once("../medical/history_personal_fields.php");

  echo "</form>\n";

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  require_once("../shared/footer.php");
?>
