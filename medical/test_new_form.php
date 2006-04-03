<?php
/**
 * test_new_form.php
 *
 * Addition screen of a medical test
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: test_new_form.php,v 1.15 2006/04/03 18:59:30 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || !is_numeric($_GET["pat"]))
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
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError

  /**
   * Retrieving get vars
   */
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);

  // after clean (get_form_vars.php)
  $formVar["id_problem"] = $idProblem;
  $formVar["id_patient"] = $idPatient;

  /**
   * Show page
   */
  $title = _("Add Medical Test");
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

  //Error::debug($formVar);

  require_once("../shared/form_errors_msg.php");

  /**
   * New form
   */
  echo '<form method="post" action="../medical/test_new.php" enctype="multipart/form-data" onsubmit="document.forms[0].upload_file.value = document.forms[0].path_filename.value; return true;">' . "\n";

  Form::hidden("id_problem", $idProblem);
  Form::hidden("id_patient", $idPatient);
  Form::hidden("upload_file");

  require_once("../medical/test_fields.php");

  echo "</form>\n";

  HTML::message('* ' . _("Note: The fields with * are required."));

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  require_once("../shared/footer.php");
?>
