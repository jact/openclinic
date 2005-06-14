<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_list.php,v 1.9 2005/06/14 18:58:11 jact Exp $
 */

/**
 * problem_list.php
 *
 * Medical problems screen
 *
 * Author: jact <jachavar@gmail.com>
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
  $nav = "problems";
  $onlyDoctor = true;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Problem_Page_Query.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/misc_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_GET["key"]);
  $info = (isset($_GET["info"]) ? urldecode(safeText($_GET["info"])) : "");

  $problemQ = new Problem_Page_Query();
  $problemQ->connect();
  if ($problemQ->isError())
  {
    showQueryError($problemQ);
  }

  $lastOrderNumber = $problemQ->getLastOrderNumber($idPatient);

  $count = $problemQ->selectProblems($idPatient);
  if ($problemQ->isError())
  {
    $problemQ->close();
    showQueryError($problemQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Medical Problems Report");
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

  ////////////////////////////////////////////////////////////////////
  // Display insertion message if coming from new with a successful insert.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["added"]) && !empty($info))
  {
    if (isset($_GET["closed"]) && $_GET["closed"])
    {
      showMessage(sprintf(_("Medical problem, %s, has been added to closed medical problems list."), $info), OPEN_MSG_INFO);
    }
    else
    {
      showMessage(sprintf(_("Medical problem, %s, has been added."), $info), OPEN_MSG_INFO);
    }
  }

  ////////////////////////////////////////////////////////////////////
  // Display update message if coming from edit with a successful update.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["updated"]) && !empty($info))
  {
    if (isset($_GET["closed"]) && $_GET["closed"])
    {
      showMessage(sprintf(_("Medical problem, %s, has been added to closed medical problems list."), $info), OPEN_MSG_INFO);
    }
    else
    {
      showMessage(sprintf(_("Medical problem, %s, has been updated."), $info), OPEN_MSG_INFO);
    }
  }

  ////////////////////////////////////////////////////////////////////
  // Display deletion message if coming from del with a successful delete.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["deleted"]) && !empty($info))
  {
    showMessage(sprintf(_("Medical problem, %s, has been deleted."), $info), OPEN_MSG_INFO);
  }

  if ($hasMedicalAdminAuth)
  {
    echo '<p><a href="../medical/problem_new_form.php?key=' . $idPatient . '&amp;num=' . $lastOrderNumber . '&amp;reset=Y">' . _("Add New Medical Problem") . "</a></p>\n";
  }

  echo "<hr />\n";

  echo '<h3>' . _("Medical Problems List:") . "</h3>\n";

  if ($count == 0)
  {
    $problemQ->close();
    showMessage(_("No medical problems defined for this patient."), OPEN_MSG_INFO);
    include_once("../shared/footer.php");
    exit();
  }

  $thead = array(
    _("Order Number"),
    _("Function") => array('colspan' => ($hasMedicalAdminAuth ? 5 : 3)),
    _("Wording"),
    _("Opening Date"),
    _("Last Update Date")
  );

  $options = array(
    0 => array('align' => 'right')
  );

  $tbody = array();
  while ($problem = $problemQ->fetch())
  {
    $row = $problem->getOrderNumber();
    $row .= OPEN_SEPARATOR;

    if ($hasMedicalAdminAuth)
    {
      $row .= '<a href="../medical/problem_edit_form.php?key=' . $problem->getIdProblem() . '&amp;pat=' . $problem->getIdPatient() . '&amp;reset=Y">' . _("edit") . '</a>';
      $row .= OPEN_SEPARATOR;

      $row .= '<a href="../medical/problem_del_confirm.php?key=' . $problem->getIdProblem() . '&amp;pat=' . $problem->getIdPatient() . '&amp;wording=' . fieldPreview($problem->getWording()) . '">' . _("del") . '</a>';
      $row .= OPEN_SEPARATOR;
    } // end if

    $row .= '<a href="../medical/problem_view.php?key=' . $problem->getIdProblem() . '&amp;pat=' . $problem->getIdPatient() . '">' . _("view") . '</a>';
    $row .= OPEN_SEPARATOR;

    $row .= '<a href="../medical/test_list.php?key=' . $problem->getIdProblem() . '&amp;pat=' . $problem->getIdPatient() . '">' . _("tests") . '</a>';
    $row .= OPEN_SEPARATOR;

    $row .= '<a href="../medical/connection_list.php?key=' . $problem->getIdProblem() . '&amp;pat=' . $problem->getIdPatient() . '">' . _("connect") . '</a>';
    $row .= OPEN_SEPARATOR;

    $row .= fieldPreview($problem->getWording());
    $row .= OPEN_SEPARATOR;

    $row .= localDate($problem->getOpeningDate());
    $row .= OPEN_SEPARATOR;

    $row .= localDate($problem->getLastUpdateDate());

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end while
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);

  showTable($thead, $tbody, null, $options);

  require_once("../shared/footer.php");
?>
