<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: test_edit_form.php,v 1.8 2004/10/04 21:41:56 jact Exp $
 */

/**
 * test_edit_form.php
 ********************************************************************
 * Edition screen of a medical test
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || empty($_GET["key"]) || empty($_GET["pat"]) || empty($_GET["test"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = false;
  $restrictInDemo = true; // To prevent users' malice

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Test_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../lib/input_lib.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  // after login_check inclusion to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "document_type";

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);
  $idTest = intval($_GET["test"]);

  $testQ = new Test_Query();
  $testQ->connect();
  if ($testQ->isError())
  {
    showQueryError($testQ);
  }

  $numRows = $testQ->select($idProblem, $idTest);
  if ($testQ->isError())
  {
    $testQ->close();
    showQueryError($testQ);
  }

  if ( !$numRows )
  {
    $testQ->close();
    include_once("../shared/header.php");

    showMessage(_("That medical test does not exist"), OPEN_MSG_ERROR);

    include_once("../shared/footer.php");
    exit();
  }

  $test = $testQ->fetch();
  if ($testQ->isError())
  {
    showFetchError($testQ, false);
  }
  else
  {
    $postVars["document_type"] = $test->getDocumentType();
    $postVars["path_filename"] = $test->getPathFilename();
  }
  $testQ->freeResult();
  $testQ->close();
  unset($testQ);
  unset($test);

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Edit Medical Test");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");
  require_once("../medical/problem_header.php");

  $returnLocation = "../medical/test_list.php?key=" . $idProblem . "&amp;pat=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Medical Problems Report") => "../medical/problem_list.php?key=" . $idPatient,
    _("View Medical Problem") => "../medical/problem_view.php?key=" . $idProblem . "&amp;pat=" . $idPatient,
    _("View Medical Tests") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  showPatientHeader($idPatient);
  showProblemHeader($idProblem);
  echo "<br />\n"; // should be deleted

  require_once("../shared/form_errors_msg.php");
?>

<form method="post" action="../medical/test_edit.php" enctype="multipart/form-data" onsubmit="document.forms[0].upload_file.value = document.forms[0].path_filename.value; return true;">
  <div>
<?php
  showInputHidden("id_problem", $idProblem);
  showInputHidden("id_patient", $idPatient);
  showInputHidden("id_test", $idTest);
  showInputHidden("upload_file", $postVars["path_filename"]);

  require_once("../medical/test_fields.php");
?>
  </div>
</form>

<?php
  showMessage('* ' . _("Note: The fields with * are required."));

  require_once("../shared/footer.php");
?>
