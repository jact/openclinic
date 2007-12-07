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
 * @version   CVS: $Id: test_edit_form.php,v 1.36 2007/12/07 16:51:45 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "problems";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_DOCTOR, false); // Not in DEMO to prevent users' malice

  require_once("../model/Patient.php");
  require_once("../model/Problem.php");
  require_once("../model/Test.php");

  /**
   * Retrieving vars (PGS)
   */
  $idProblem = Check::postGetSessionInt('id_problem');
  $idPatient = Check::postGetSessionInt('id_patient');
  $idTest = Check::postGetSessionInt('id_test');

  $patient = new Patient($idPatient);
  if ($patient->getName() == '')
  {
    FlashMsg::add(_("That patient does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  $problem = new Problem($idProblem);
  if ( !$problem )
  {
    FlashMsg::add(_("That medical problem does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  $test = new Test($idProblem, $idTest);
  if ( !$test )
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
  $titlePage = $patient->getName() . ' [' . $problem->getWordingPreview() . '] (' . $title . ')';
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
    $problem->getWordingPreview() => "../medical/problem_view.php",
    _("View Medical Tests") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon icon_patient");
  unset($links);

  echo $patient->getHeader();
  echo $problem->getHeader();

  Form::errorMsg();

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
