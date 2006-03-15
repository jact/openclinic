<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_view.php,v 1.16 2006/03/15 20:46:49 jact Exp $
 */

/**
 * problem_view.php
 *
 * View medical problem data screen
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = true;

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || !is_numeric($_GET["pat"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Problem_Page_Query.php");
  require_once("../classes/Staff_Query.php");

  /**
   * Retrieving get vars
   */
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);

  /**
   * Search database for problem
   */
  $problemQ = new Problem_Page_Query();
  $problemQ->connect();

  if ( !$problemQ->select($idProblem) )
  {
    $problemQ->close();
    include_once("../shared/header.php");

    HTML::message(_("That medical problem does not exist."), OPEN_MSG_ERROR);

    include_once("../shared/footer.php");
    exit();
  }

  $problem = $problemQ->fetch();
  if ( !$problem )
  {
    $problemQ->close();
    Error::fetch($problemQ);
  }

  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);

  if (I18n::localDate($problem->getClosingDate()) != "")
  {
    $nav = "history";
  }

  /**
   * Update session variables
   */
  require_once("../medical/visited_list.php");
  addPatient($idPatient);

  /**
   * Show page
   */
  $title = _("View Medical Problem");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    (($nav == "problems") ? _("Medical Problems Report") : _("Clinic History")) => (($nav == "problems") ? "../medical/problem_list.php?key=" . $idPatient : "../medical/history_list.php?key=" . $idPatient),
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  showPatientHeader($idPatient);

  echo '<p>';
  if ($hasMedicalAdminAuth)
  {
    if (I18n::localDate($problem->getClosingDate()) == "")
    {
      echo '<a href="../medical/problem_edit_form.php?key=' . $idProblem . '&amp;pat=' . $idPatient . '">' . _("Edit Medical Problem Data") . '</a> | ';
    }
    echo '<a href="../medical/problem_del_confirm.php?key=' . $idProblem . '&amp;pat=' . $idPatient . '&amp;wording=' . urlencode($problem->getWording()) . '">' . _("Delete Medical Problem") . '</a> | ';
  }
  echo '<a href="../medical/connection_list.php?key=' . $idProblem . '&amp;pat=' . $idPatient . '">' . _("View Connection Problems") . '</a> | ';
  echo '<a href="../medical/test_list.php?key=' . $idProblem . '&amp;pat=' . $idPatient . '">' . _("View Medical Tests") . '</a>';
  echo "</p>\n";

  echo '<h2>' . _("Medical Problem Data") . "</h2>\n";

  if ($problem->getIdMember())
  {
    $staffQ = new Staff_Query();
    $staffQ->connect();

    if ($staffQ->select($problem->getIdMember()))
    {
      $staff = $staffQ->fetch();
      if ($staff)
      {
        echo '<h3>' . _("Attending Physician") . "</h3>\n";
        echo '<p>' . $staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName() . "</p>\n";
      }
      $staffQ->freeResult();
    }
    $staffQ->close();
    unset($staffQ);
    unset($staff);
  }

  echo '<h3>' . _("Opening Date") . "</h3>\n";
  echo '<p>' . I18n::localDate($problem->getOpeningDate()) . "</p>\n";

  if (I18n::localDate($problem->getLastUpdateDate()) != "") // backwards compatibility
  {
    echo '<h3>' . _("Last Update Date") . "</h3>\n";
    echo '<p>' . I18n::localDate($problem->getLastUpdateDate()) . "</p>\n";
  }

  if (I18n::localDate($problem->getClosingDate()) != "")
  {
    echo '<h3>' . _("Closing Date") . "</h3>\n";
    echo '<p>' . I18n::localDate($problem->getClosingDate()) . "</p>\n";
  }

  if ($problem->getMeetingPlace())
  {
    echo '<h3>' . _("Meeting Place") . "</h3>\n";
    echo '<p>' . $problem->getMeetingPlace() . "</p>\n";
  }

  echo '<h3>' . _("Wording") . "</h3>\n";
  echo '<p>' . nl2br($problem->getWording()) . "</p>\n";

  if ($problem->getSubjective())
  {
    echo '<h3>' . _("Subjective") . "</h3>\n";
    echo '<p>' . nl2br($problem->getSubjective()) . "</p>\n";
  }

  if ($problem->getObjective())
  {
    echo '<h3>' . _("Objective") . "</h3>\n";
    echo '<p>' . nl2br($problem->getObjective()) . "</p>\n";
  }

  if ($problem->getAppreciation())
  {
    echo '<h3>' . _("Appreciation") . "</h3>\n";
    echo '<p>' . nl2br($problem->getAppreciation()) . "</p>\n";
  }

  if ($problem->getActionPlan())
  {
    echo '<h3>' . _("Action Plan") . "</h3>\n";
    echo '<p>' . nl2br($problem->getActionPlan()) . "</p>\n";
  }

  if ($problem->getPrescription())
  {
    echo '<h3>' . _("Prescription") . "</h3>\n";
    echo '<p>' . nl2br($problem->getPrescription()) . "</p>\n";
  }

  require_once("../shared/footer.php");
?>
