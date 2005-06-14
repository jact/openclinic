<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: connection_list.php,v 1.10 2005/06/14 18:56:57 jact Exp $
 */

/**
 * connection_list.php
 *
 * List of defined connection between medical problems screen
 *
 * Author: jact <jachavar@gmail.com>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || empty($_GET["key"]) || empty($_GET["pat"]))
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
  require_once("../classes/Connection_Query.php");
  require_once("../classes/Problem_Page_Query.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/misc_lib.php");
  require_once("../lib/validator_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);
  $info = (isset($_GET["info"]) ? urldecode(safeText($_GET["info"])) : "");

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("View Connection Problems");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");
  require_once("../medical/problem_header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Medical Problems Report") => "../medical/problem_list.php?key=" . $idPatient,
    _("View Medical Problem") => "../medical/problem_view.php?key=" . $idProblem . "&amp;pat=" . $idPatient,
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  showPatientHeader($idPatient);
  showProblemHeader($idProblem);

  ////////////////////////////////////////////////////////////////////
  // Display insertion message if coming from new with a successful insert.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["added"]))
  {
    showMessage(_("Connection problems have been added."), OPEN_MSG_INFO);
  }

  ////////////////////////////////////////////////////////////////////
  // Display deletion message if coming from del with a successful delete.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["deleted"]) && !empty($info))
  {
    showMessage(sprintf(_("Connection with medical problem, %s, has been deleted."), $info), OPEN_MSG_INFO);
  }

  if ($hasMedicalAdminAuth)
  {
    echo '<p><a href="../medical/connection_new_form.php?key=' . $idProblem . '&amp;pat=' . $idPatient . '">' . _("Add New Connection Problems") . "</a></p>\n";
  }

  $connQ = new Connection_Query;
  $connQ->connect();
  if ($connQ->isError())
  {
    showQueryError($connQ);
  }

  $numRows = $connQ->select($idProblem);
  if ($connQ->isError())
  {
    $connQ->close();
    showQueryError($connQ);
  }

  $connArray = array();
  if ($numRows)
  {
    while ($conn = $connQ->fetch())
    {
      $connArray[] = $conn[1];
    }
    $connQ->freeResult();
  }
  $connQ->close();
  unset($connQ);

  if (count($connArray) == 0)
  {
    showMessage(_("No connections defined for this medical problem."), OPEN_MSG_INFO);
    include_once("../shared/footer.php");
    exit();
  }

  echo '<h3>' . _("Connection Problems List:") . "</h3>\n";

  $thead = array(
    _("Function") => array('colspan' => ($hasMedicalAdminAuth ? 2 : 1)),
    _("Opening Date"),
    _("Wording")
  );

  $problemQ = new Problem_Page_Query();
  $problemQ->connect();
  if ($problemQ->isError())
  {
    showQueryError($problemQ);
  }

  $tbody = array();
  for ($i = 0; $i < count($connArray); $i++)
  {
    $problemQ->select($connArray[$i]);
    if ($problemQ->isError())
    {
      showQueryError($problemQ, false);
      continue;
    }

    $problem = $problemQ->fetch();
    if ($problemQ->isError())
    {
      $problemQ->close();
      showFetchError($problemQ);
    }

    $row = '<a href="../medical/problem_view.php?key=' . $problem->getIdProblem() . '&amp;pat=' . $idPatient . '">' . _("view") . '</a>';
    $row .= OPEN_SEPARATOR;

    if ($hasMedicalAdminAuth)
    {
      $row .= '<a href="../medical/connection_del_confirm.php?key=' . $idProblem . '&amp;conn=' . $problem->getIdProblem() . '&amp;pat=' . $idPatient . '&amp;wording=' . fieldPreview($problem->getWording()) . '">' . _("del") . '</a>';
      $row .= OPEN_SEPARATOR;
    } // end if

    $row .= $problem->getOpeningDate();
    $row .= OPEN_SEPARATOR;

    $row .= fieldPreview($problem->getWording());

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end for
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);

  showTable($thead, $tbody, null);

  require_once("../shared/footer.php");
?>
