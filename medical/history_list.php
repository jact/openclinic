<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_list.php,v 1.5 2004/07/31 17:04:32 jact Exp $
 */

/**
 * history_list.php
 ********************************************************************
 * Closed medical problems screen
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || empty($_GET["key"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "history";
  $onlyDoctor = true;

  ////////////////////////////////////////////////////////////////////
  // Retrieving get var
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_GET["key"]);

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Problem_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/misc_lib.php");

  $problemQ = new Problem_Query();
  $problemQ->connect();
  if ($problemQ->isError())
  {
    showQueryError($problemQ);
  }

  $count = $problemQ->selectProblems($idPatient, true);
  if ($problemQ->isError())
  {
    $problemQ->close();
    showQueryError($problemQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Clinic History");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  if ( !showPatientHeader($idPatient) )
  {
    $problemQ->close();
    showMessage(_("That patient does not exist."), OPEN_MSG_ERROR);

    include_once("../shared/footer.php");
    exit();
  }

  echo '<p><a href="../medical/history_personal_view.php?key=' . $idPatient . '">' . _("View Personal Antecedents") . '</a> | ';
  echo '<a href="../medical/history_family_view.php?key=' . $idPatient . '">' . _("View Family Antecedents") . "</a></p>\n";

  echo '<h3>' . _("Closed Medical Problems List:") . "</h3>\n";

  if ($count == 0)
  {
    $problemQ->close();
    showMessage(_("No closed medical problems defined for this patient."), OPEN_MSG_INFO);
    include_once("../shared/footer.php");
    exit();
  }

  $thead = array(
    _("Order Number"),
    _("Function") => array('colspan' => ($hasMedicalAdminAuth ? 4 : 3)),
    _("Wording"),
    _("Opening Date"),
    _("Closing Date")
  );

  $options = array(
    0 => array('align' => 'right')
  );

  $tbody = array();
  while ($problem = $problemQ->fetch())
  {
    $row = $problem->getOrderNumber();
    $row .= OPEN_SEPARATOR;

    // a closed medical problem is not editable

    $row .= '<a href="../medical/problem_view.php?key=' . $problem->getIdProblem() . '&amp;pat=' . $problem->getIdPatient() . '">' . _("view") . '</a>';
    $row .= OPEN_SEPARATOR;

    if ($hasMedicalAdminAuth)
    {
      $row .= '<a href="../medical/problem_del_confirm.php?key=' . $problem->getIdProblem() . '&amp;pat=' . $problem->getIdPatient() . '&amp;wording=' . fieldPreview($problem->getWording()) . '">' . _("del") . '</a>';
      $row .= OPEN_SEPARATOR;
    } // end if

    $row .= '<a href="../medical/test_list.php?key=' . $problem->getIdProblem() . '&amp;pat=' . $problem->getIdPatient() . '">' . _("tests") . '</a>';
    $row .= OPEN_SEPARATOR;

    $row .= '<a href="../medical/connection_list.php?key=' . $problem->getIdProblem() . '&amp;pat=' . $problem->getIdPatient() . '">' . _("connect") . '</a>';
    $row .= OPEN_SEPARATOR;

    $row .= fieldPreview($problem->getWording());
    $row .= OPEN_SEPARATOR;

    $row .= $problem->getOpeningDate();
    $row .= OPEN_SEPARATOR;

    $row .= $problem->getClosingDate();

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end while
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);

  showTable($thead, $tbody, null, $options);

  require_once("../shared/footer.php");
?>
