<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: test_new_form.php,v 1.8 2005/07/28 17:47:34 jact Exp $
 */

/**
 * test_new_form.php
 ********************************************************************
 * Addition screen of a medical test
 ********************************************************************
 * Author: jact <jachavar@terra.es>
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
  $onlyDoctor = false;
  $restrictInDemo = true; // To prevent users' malice

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  // after login_check inclusion to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "document_type";

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);

  // after clean (get_form_vars.php)
  $postVars["id_problem"] = $idProblem;
  $postVars["id_patient"] = $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Add Medical Test");
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
  echo "<br />\n"; // @fixme should be deleted

  //Error::debug($postVars);

  require_once("../shared/form_errors_msg.php");
?>

<form method="post" action="../medical/test_new.php" enctype="multipart/form-data" onsubmit="document.forms[0].upload_file.value = document.forms[0].path_filename.value; return true;">
  <div>
<?php
  Form::hidden("id_problem", "id_problem", $postVars["id_problem"]);
  Form::hidden("id_patient", "id_patient", $idPatient);
  Form::hidden("upload_file", "upload_file");

  require_once("../medical/test_fields.php");
?>
  </div>
</form>

<?php
  HTML::message('* ' . _("Note: The fields with * are required."));

  require_once("../shared/footer.php");
?>
