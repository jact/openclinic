<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: test_edit_form.php,v 1.15 2006/01/24 20:08:23 jact Exp $
 */

/**
 * test_edit_form.php
 *
 * Edition screen of a medical test
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || !is_numeric($_GET["pat"]) || !is_numeric($_GET["test"]))
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
  $restrictInDemo = true; // To prevent users' malice

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Test_Query.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  /**
   * Retrieving get vars
   */
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);
  $idTest = intval($_GET["test"]);

  /**
   * Search database
   */
  $testQ = new Test_Query();
  $testQ->connect();

  if ( !$testQ->select($idProblem, $idTest) )
  {
    $testQ->close();
    include_once("../shared/header.php");

    HTML::message(_("That medical test does not exist"), OPEN_MSG_ERROR);

    include_once("../shared/footer.php");
    exit();
  }

  $test = $testQ->fetch();
  if ($test)
  {
    $postVars["document_type"] = $test->getDocumentType();
    $postVars["path_filename"] = $test->getPathFilename();
  }
  else
  {
    Error::fetch($testQ, false);
  }
  $testQ->freeResult();
  $testQ->close();
  unset($testQ);
  unset($test);

  /**
   * Show page
   */
  $title = _("Edit Medical Test");
  $focusFormField = "document_type"; // to avoid JavaScript mistakes in demo version
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");
  require_once("../medical/problem_header.php");

  $returnLocation = "../medical/test_list.php?key=" . $idProblem . "&amp;pat=" . $idPatient;

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Medical Problems Report") => "../medical/problem_list.php?key=" . $idPatient,
    _("View Medical Problem") => "../medical/problem_view.php?key=" . $idProblem . "&amp;pat=" . $idPatient,
    _("View Medical Tests") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  showPatientHeader($idPatient);
  showProblemHeader($idProblem);

  require_once("../shared/form_errors_msg.php");

  /**
   * Edit form
   */
  echo '<form method="post" action="../medical/test_edit.php" enctype="multipart/form-data" onsubmit="document.forms[0].upload_file.value = document.forms[0].path_filename.value; return true;">' . "\n";
  echo "<div>\n";

  Form::hidden("id_problem", "id_problem", $idProblem);
  Form::hidden("id_patient", "id_patient", $idPatient);
  Form::hidden("id_test", "id_test", $idTest);
  Form::hidden("upload_file", "upload_file", $postVars["path_filename"]);

  require_once("../medical/test_fields.php");

  echo "</div>\n</form>\n";

  HTML::message('* ' . _("Note: The fields with * are required."));

  require_once("../shared/footer.php");
?>
