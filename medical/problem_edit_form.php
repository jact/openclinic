<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_edit_form.php,v 1.15 2005/07/31 11:10:51 jact Exp $
 */

/**
 * problem_edit_form.php
 *
 * Edition screen of a medical problem
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || empty($_GET["key"]) || empty($_GET["pat"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Problem_Page_Query.php");
  require_once("../classes/Staff_Query.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  /**
   * Retrieving get vars
   */
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);

  /**
   * Search database
   */
  $problemQ = new Problem_Page_Query();
  $problemQ->connect();
  if ($problemQ->isError())
  {
    Error::query($problemQ);
  }

  $numRows = $problemQ->select($idProblem);
  if ($problemQ->isError())
  {
    $problemQ->close();
    Error::query($problemQ);
  }

  if ( !$numRows )
  {
    $problemQ->close();
    include_once("../shared/header.php");

    HTML::message(_("That medical problem does not exist."), OPEN_MSG_ERROR);

    include_once("../shared/footer.php");
    exit();
  }

  $problem = $problemQ->fetch();
  if ($problemQ->isError())
  {
    Error::fetch($problemQ, false);
  }
  else
  {
    $postVars["id_problem"] = $idProblem;
    $postVars["id_patient"] = $idPatient;
    $postVars["order_number"] = $problem->getOrderNumber();
    $postVars["opening_date"] = $problem->getOpeningDate();
    if (isset($_GET["reset"]))
    {
      $postVars["last_update_date"] = $problem->getLastUpdateDate();
      $postVars["id_member"] = $problem->getIdMember();
      $postVars["closed_problem"] = ((I18n::localDate($problem->getClosingDate()) != "") ? "checked" : "");
      $postVars["meeting_place"] = $problem->getMeetingPlace();
      $postVars["wording"] = $problem->getWording();
      $postVars["subjective"] = $problem->getSubjective();
      $postVars["objective"] = $problem->getObjective();
      $postVars["appreciation"] = $problem->getAppreciation();
      $postVars["action_plan"] = $problem->getActionPlan();
      $postVars["prescription"] = $problem->getPrescription();
    }
  }
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);

  /**
   * Show page
   */
  $title = _("Edit Medical Problem");
  // to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "wording";
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  $returnLocation = "../medical/problem_view.php?key=" . $idProblem . "&amp;pat=" . $idPatient;

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Medical Problems Report") => "../medical/problem_list.php?key=" . $idPatient,
    _("View Medical Problem") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  showPatientHeader($idPatient);
  echo "<br />\n"; // @fixme should be deleted

  require_once("../shared/form_errors_msg.php");

  /**
   * Edit form
   */
  echo '<form method="post" action="../medical/problem_edit.php">' . "\n";
  echo "<div>\n";

  Form::hidden("id_problem", "id_problem", $postVars["id_problem"]);
  Form::hidden("last_update_date", "last_update_date", $postVars["last_update_date"]);
  Form::hidden("id_patient", "id_patient", $postVars["id_patient"]);

  require_once("../medical/problem_fields.php");

  echo "</div>\n</form>\n";

  HTML::message('* ' . _("Note: The fields with * are required."));

  require_once("../shared/footer.php");
?>
