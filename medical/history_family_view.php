<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_family_view.php,v 1.11 2006/03/12 18:49:30 jact Exp $
 */

/**
 * history_family_view.php
 *
 * Family antecedents screen
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

  if ( !$historyQ->selectFamily($idPatient) )
  {
    $historyQ->close();
    include_once("../shared/header.php");

    HTML::message(_("That patient does not exist."), OPEN_MSG_ERROR);

    include_once("../shared/footer.php");
    exit();
  }

  $history = $historyQ->fetch();
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
  $title = _("View Family Antecedents");
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
    echo '<p><a href="../medical/history_family_edit_form.php?key=' . $idPatient . '&amp;reset=Y">' . _("Edit Family Antecedents") . "</a></p>\n";
  }

  /**
   * Show family antecedents
   */
  echo '<h2>' . _("Family Antecedents") . "</h2>\n";

  if ($history->getParentsStatusHealth())
  {
    echo '<h3>' . _("Parents Status Health") . "</h3>\n";
    echo '<p>' . nl2br($history->getParentsStatusHealth()) . "</p>\n";
  }

  if ($history->getBrothersStatusHealth())
  {
    echo '<h3>' . _("Brothers and Sisters Status Health") . "</h3>\n";
    echo '<p>' . nl2br($history->getBrothersStatusHealth()) . "</p>\n";
  }

  if ($history->getSpouseChildsStatusHealth())
  {
    echo '<h3>' . _("Spouse and Childs Status Health") . "</h3>\n";
    echo '<p>' . nl2br($history->getSpouseChildsStatusHealth()) . "</p>\n";
  }

  if ($history->getFamilyIllness())
  {
    echo '<h3>' . _("Family Illness") . "</h3>\n";
    echo '<p>' . nl2br($history->getFamilyIllness()) . "</p>\n";
  }

  require_once("../shared/footer.php");
?>
