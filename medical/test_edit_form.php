<?php
/**
 * test_edit_form.php
 *
 * Edition screen of a medical test
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: test_edit_form.php,v 1.28 2007/10/28 11:31:42 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = false;
  $restrictInDemo = true; // To prevent users' malice

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Test_Query.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError
  require_once("../lib/PatientInfo.php");
  require_once("../lib/ProblemInfo.php");
  require_once("../lib/TestInfo.php");

  /**
   * Retrieving vars (PGS)
   */
  $idProblem = Check::postGetSessionInt('id_problem');
  $idPatient = Check::postGetSessionInt('id_patient');
  $idTest = Check::postGetSessionInt('id_test');

  $patient = new PatientInfo($idPatient);
  if ($patient->getName() == '')
  {
    FlashMsg::add(_("That patient does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  $problem = new ProblemInfo($idProblem);
  if ($problem->getWording() == '')
  {
    FlashMsg::add(_("That medical problem does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  $test = new TestInfo($idProblem, $idTest);
  $test = $test->getObject();
  if ($test == null)
  {
    FlashMsg::add(_("That medical test does not exist"), OPEN_MSG_ERROR);
    header("Location: ../medical/test_list.php");
    exit();
  }

  $formVar["document_type"] = $test->getDocumentType();
  $formVar["path_filename"] = $test->getPathFilename();

  /**
   * Show page
   */
  $title = _("Edit Medical Test");
  $titlePage = $patient->getName() . ' [' . $problem->getWording() . '] (' . $title . ')';
  $focusFormField = "document_type"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");

  //$returnLocation = "../medical/test_list.php?id_problem=" . $idProblem . "&id_patient=" . $idPatient;
  $returnLocation = "../medical/test_list.php";

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    _("Medical Problems Report") => "../medical/problem_list.php", //"?id_patient=" . $idPatient,
    $problem->getWording() => "../medical/problem_view.php",
    _("View Medical Tests") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  $patient->showHeader();
  $problem->showHeader();

  require_once("../shared/form_errors_msg.php");

  /**
   * Edit form
   */
  HTML::start('form',
    array(
      'method' => 'post',
      'action' => '../medical/test_edit.php',
      'enctype' => 'multipart/form-data',
      'onsubmit' => 'document.forms[0].upload_file.value = document.forms[0].path_filename.value; return true;'
    )
  );

  Form::hidden("id_problem", $idProblem);
  Form::hidden("id_patient", $idPatient);
  Form::hidden("id_test", $idTest);
  Form::hidden("upload_file", $formVar["path_filename"]);

  require_once("../medical/test_fields.php");

  HTML::end('form');

  Msg::hint('* ' . _("Note: The fields with * are required."));

  HTML::para(HTML::strLink(_("Return"), $returnLocation));

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  require_once("../layout/footer.php");
?>
