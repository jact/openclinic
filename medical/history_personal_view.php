<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_personal_view.php,v 1.10 2006/01/24 20:20:34 jact Exp $
 */

/**
 * history_personal_view.php
 *
 * Personal antecedents screen
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "history";
  $onlyDoctor = true;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/History_Query.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  /**
   * Retrieving get var
   */
  $idPatient = intval($_GET["key"]);

  /**
   * Search database for problem
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

  $history = $historyQ->fetchPersonal();
  if ( !$history )
  {
    $historyQ->close();
    Error::fetch($historyQ);
  }

  $historyQ->freeResult();
  $historyQ->close();
  unset($historyQ);

  /**
   * Show page
   */
  $title = _("View Personal Antecedents");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Clinic History") => "../medical/history_list.php?key=" . $idPatient,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  showPatientHeader($idPatient);

  if ($hasMedicalAdminAuth)
  {
    echo '<p><a href="../medical/history_personal_edit_form.php?key=' . $idPatient . '&amp;reset=Y">' . _("Edit Personal Antecedents") . "</a></p>\n";
  }

  /**
   * Show personal antecedents
   */
  echo '<h2>' . _("Personal Antecedents") . "</h2>\n";

  if ($history->getBirthGrowth())
  {
    echo '<h3>' . _("Birth and Growth") . "</h3>\n";
    echo '<p>' . nl2br($history->getBirthGrowth()) . "</p>\n";
  }

  if ($history->getGrowthSexuality())
  {
    echo '<h3>' . _("Growth and Sexuality") . "</h3>\n";
    echo '<p>' . nl2br($history->getGrowthSexuality()) . "</p>\n";
  }

  if ($history->getFeed())
  {
    echo '<h3>' . _("Feed") . "</h3>\n";
    echo '<p>' . nl2br($history->getFeed()) . "</p>\n";
  }

  if ($history->getHabits())
  {
    echo '<h3>' . _("Habits") . "</h3>\n";
    echo '<p>' . nl2br($history->getHabits()) . "</p>\n";
  }

  if ($history->getPeristalticConditions())
  {
    echo '<h3>' . _("Peristaltic Conditions") . "</h3>\n";
    echo '<p>' . nl2br($history->getPeristalticConditions()) . "</p>\n";
  }

  if ($history->getPsychological())
  {
    echo '<h3>' . _("Psychological Conditions") . "</h3>\n";
    echo '<p>' . nl2br($history->getPsychological()) . "</p>\n";
  }

  if ($history->getChildrenComplaint())
  {
    echo '<h3>' . _("Children Complaint") . "</h3>\n";
    echo '<p>' . nl2br($history->getChildrenComplaint()) . "</p>\n";
  }

  if ($history->getVenerealDisease())
  {
    echo '<h3>' . _("Venereal Disease") . "</h3>\n";
    echo '<p>' . nl2br($history->getVenerealDisease()) . "</p>\n";
  }

  if ($history->getAccidentSurgicalOperation())
  {
    echo '<h3>' . _("Accidents and Surgical Operations") . "</h3>\n";
    echo '<p>' . nl2br($history->getAccidentSurgicalOperation()) . "</p>\n";
  }

  if ($history->getMedicinalIntolerance())
  {
    echo '<h3>' . _("Medicinal Intolerance") . "</h3>\n";
    echo '<p>' . nl2br($history->getMedicinalIntolerance()) . "</p>\n";
  }

  if ($history->getMentalIllness())
  {
    echo '<h3>' . _("Mental Illness") . "</h3>\n";
    echo '<p>' . nl2br($history->getMentalIllness()) . "</p>\n";
  }

  require_once("../shared/footer.php");
?>
