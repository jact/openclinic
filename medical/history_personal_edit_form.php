<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_personal_edit_form.php,v 1.6 2005/07/21 16:56:58 jact Exp $
 */

/**
 * history_personal_edit_form.php
 *
 * Edition screen of a patient personal antecedents
 *
 * Author: jact <jachavar@gmail.com>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "history";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  // after login_check inclusion to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "birth_growth";

  ////////////////////////////////////////////////////////////////////
  // Checking for query string flag to read data from database.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["key"]))
  {
    $idPatient = intval($_GET["key"]);

    include_once("../classes/History_Query.php");

    $historyQ = new History_Query();
    $historyQ->connect();
    if ($historyQ->isError())
    {
      Error::query($historyQ);
    }

    $numRows = $historyQ->selectPersonal($idPatient);
    if ($historyQ->isError())
    {
      $historyQ->close();
      Error::query($historyQ);
    }

    if ( !$numRows )
    {
      $historyQ->close();
      include_once("../shared/header.php");

      HTML::message(_("That patient does not exist."), OPEN_MSG_ERROR);

      include_once("../shared/footer.php");
      exit();
    }

    $history = $historyQ->fetchPersonal();
    if ($historyQ->isError())
    {
      Error::fetch($historyQ, false);
    }
    else
    {
      $postVars["id_patient"] = $history->getIdPatient();
      $postVars["birth_growth"] = $history->getBirthGrowth();
      $postVars["growth_sexuality"] = $history->getGrowthSexuality();
      $postVars["feed"] = $history->getFeed();
      $postVars["habits"] = $history->getHabits();
      $postVars["peristaltic_conditions"] = $history->getPeristalticConditions();
      $postVars["psychological"] = $history->getPsychological();
      $postVars["children_complaint"] = $history->getChildrenComplaint();
      $postVars["venereal_disease"] = $history->getVenerealDisease();
      $postVars["accident_surgical_operation"] = $history->getAccidentSurgicalOperation();
      $postVars["medicinal_intolerance"] = $history->getMedicinalIntolerance();
      $postVars["mental_illness"] = $history->getMentalIllness();
    }
    $historyQ->freeResult();
    $historyQ->close();
    unset($historyQ);
    unset($history);
  }
  else
  {
    $idPatient = $postVars["id_patient"];
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Edit Personal Antecedents");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  $returnLocation = "../medical/history_personal_view.php?key=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Clinic History") => "../medical/history_list.php?key=" . $idPatient,
    _("View Personal Antecedents") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  showPatientHeader($idPatient);
  echo "<br />\n"; // @fixme should be deleted

  require_once("../shared/form_errors_msg.php");

  ////////////////////////////////////////////////////////////////////
  // Display update message if coming from setting_edit with a successful update.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["updated"]))
  {
    HTML::message(_("Personal Antecedents have been updated."), OPEN_MSG_INFO);
  }
?>

<form method="post" action="../medical/history_personal_edit.php">
  <div>
<?php
  showInputHidden("id_patient", $idPatient);

  require_once("../medical/history_personal_fields.php");
?>
  </div>
</form>

<?php require_once("../shared/footer.php"); ?>
