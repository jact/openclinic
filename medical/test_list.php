<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: test_list.php,v 1.11 2005/07/19 19:51:14 jact Exp $
 */

/**
 * test_list.php
 *
 * Medical tests screen
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
  $restrictInDemo = true; // To prevent users' malice

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Test_Query.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/validator_lib.php");
  require_once("../lib/misc_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);
  $info = (isset($_GET["info"]) ? urldecode(safeText($_GET["info"])) : "");

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("View Medical Tests");
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
  if (isset($_GET["added"]) && !empty($info))
  {
    showMessage(sprintf(_("Medical test, %s, has been added."), $info), OPEN_MSG_INFO);
  }

  ////////////////////////////////////////////////////////////////////
  // Display update message if coming from edit with a successful update.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["updated"]) && !empty($info))
  {
    showMessage(sprintf(_("Medical test, %s, has been updated."), $info), OPEN_MSG_INFO);
  }

  ////////////////////////////////////////////////////////////////////
  // Display deletion message if coming from del with a successful delete.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["deleted"]) && !empty($info))
  {
    showMessage(sprintf(_("Medical test, %s, has been deleted."), $info), OPEN_MSG_INFO);
  }

  if ($hasMedicalAdminAuth)
  {
    echo '<p><a href="../medical/test_new_form.php?key=' . $idProblem . '&amp;pat=' . $idPatient . '&amp;reset=Y">' . _("Add New Medical Test") . "</a></p>\n";
    echo "<hr />\n";
  }

  $testQ = new Test_Query;
  $testQ->connect();
  if ($testQ->isError())
  {
    Error::query($testQ);
  }

  $count = $testQ->select($idProblem);
  if ($testQ->isError())
  {
    $testQ->close();
    Error::query($testQ);
  }

  if ($count == 0)
  {
    $testQ->close();
    showMessage(_("No medical tests defined for this medical problem."), OPEN_MSG_INFO);
    include_once("../shared/footer.php");
    exit();
  }

  echo '<h3>' . _("Medical Tests List:") . "</h3>\n";

  $thead = array(
    _("Function") => array('colspan' => ($hasMedicalAdminAuth ? 3 : 1)),
    _("Document Type"),
    _("Path Filename")
  );

  $tbody = array();
  while ($test = $testQ->fetch())
  {
    $temp = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
    $temp = substr($temp, 0, strrpos($temp, "/")) . "/tests/" . translateBrowser($test->getPathFilename(false));

    $row = '<a href="' . $temp . '" onclick="return popSecondary(\'' . $temp . '\')">' . _("view") . '</a>';
    $row .= OPEN_SEPARATOR;

    if ($hasMedicalAdminAuth)
    {
      $row .= '<a href="../medical/test_edit_form.php?key=' . $test->getIdProblem() . '&amp;pat=' . $idPatient . '&amp;test=' . $test->getIdTest() . '&amp;reset=Y">' . _("edit") . '</a>';
      $row .= OPEN_SEPARATOR;

      $row .= '<a href="../medical/test_del_confirm.php?key=' . $idProblem . '&amp;test=' . $test->getIdTest() . '&amp;pat=' . $idPatient . '&amp;file=' . $test->getPathFilename() . '">' . _("del") . '</a>';
      $row .= OPEN_SEPARATOR;
    } // end if

    $row .= $test->getDocumentType();
    $row .= OPEN_SEPARATOR;

    $row .= $test->getPathFilename();

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end while
  $testQ->freeResult();
  $testQ->close();
  unset($testQ);
  unset($test);

  showTable($thead, $tbody);

  require_once("../shared/footer.php");
?>
