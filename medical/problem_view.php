<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_view.php,v 1.2 2004/04/24 14:52:15 jact Exp $
 */

/**
 * problem_view.php
 ********************************************************************
 * View medical problem data screen
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = true;

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || empty($_GET["key"]) || empty($_GET["pat"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");
  require_once("../classes/Problem_Query.php");
  require_once("../classes/Staff_Query.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  ////////////////////////////////////////////////////////////////////
  // Search database for problem
  ////////////////////////////////////////////////////////////////////
  $problemQ = new Problem_Query();
  $problemQ->connect();
  if ($problemQ->errorOccurred())
  {
    showQueryError($problemQ);
  }

  $numRows = $problemQ->select($idProblem);
  if ($problemQ->errorOccurred())
  {
    $problemQ->close();
    showQueryError($problemQ);
  }

  if ( !$numRows )
  {
    $problemQ->close();
    include_once("../shared/header.php");

    echo '<p>' . _("That medical problem does not exist.") . "</p>\n";

    include_once("../shared/footer.php");
    exit();
  }

  $problem = $problemQ->fetchProblem();
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);

  if ($problem->getClosingDate())
  {
    $nav = "history";
  }

  ////////////////////////////////////////////////////////////////////
  // Update session variables
  ////////////////////////////////////////////////////////////////////
  require_once("../medical/visited_list.php");
  addPatient($idPatient);

  ////////////////////////////////////////////////////////////////////
  // Show search results
  ////////////////////////////////////////////////////////////////////
  $title = _("View Medical Problem");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    (($nav == "problems") ? _("Medical Problems Report") : _("Clinic History")) => (($nav == "problems") ? "../medical/problem_list.php?key=" . $idPatient : "../medical/history_list.php?key=" . $idPatient),
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  showPatientHeader($idPatient);

  echo '<p>';
  if ($hasMedicalAdminAuth)
  {
    if ( !$problem->getClosingDate() )
    {
      echo '<a href="../medical/problem_edit_form.php?key=' . $idProblem . '&amp;pat=' . $idPatient . '&amp;reset=Y">' . _("Edit Medical Problem Data") . '</a> | ';
    }
    echo '<a href="../medical/problem_del_confirm.php?key=' . $idProblem . '&amp;pat=' . $idPatient . '&amp;wording=' . urlencode($problem->getWording()) . '">' . _("Delete Medical Problem") . '</a> | ';
  }
  echo '<a href="../medical/connection_list.php?key=' . $idProblem . '&amp;pat=' . $idPatient . '">' . _("View Connection Problems") . '</a> | ';
  echo '<a href="../medical/test_list.php?key=' . $idProblem . '&amp;pat=' . $idPatient . '">' . _("View Medical Tests") . '</a>';
  echo "</p>\n";

  echo '<h2>' . _("Medical Problem Data") . "</h2>\n";

  if ($problem->getCollegiateNumber())
  {
    $staffQ = new Staff_Query();
    $staffQ->connect();
    if ($staffQ->errorOccurred())
    {
      showQueryError($staffQ);
    }

    $numRows = $staffQ->selectDoctor($problem->getCollegiateNumber());
    if ($numRows)
    {
      $staff = $staffQ->fetchStaff();
      if ($staff)
      {
        echo '<h3>' . _("Doctor who treated you") . "</h3>\n";
        echo '<p>' . $staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName() . "</p>\n";
      }
      $staffQ->freeResult();
    }
    $staffQ->close();
    unset($staffQ);
    unset($staff);
  }

  echo '<h3>' . _("Opening Date") . "</h3>\n";
  echo '<p>' . $problem->getOpeningDate() . "</p>\n";

  echo '<h3>' . _("Last Update Date") . "</h3>\n";
  echo '<p>' . $problem->getLastUpdateDate() . "</p>\n";

  if ($problem->getClosingDate() != "")
  {
    echo '<h3>' . _("Closing Date") . "</h3>\n";
    echo '<p>' . $problem->getClosingDate() . "</p>\n";
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
