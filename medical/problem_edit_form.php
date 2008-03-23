<?php
/**
 * problem_edit_form.php
 *
 * Edition screen of a medical problem
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_edit_form.php,v 1.41 2008/03/23 12:00:17 jact Exp $
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
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE);

  require_once("../model/Patient.php");
  require_once("../model/Problem.php");

  /**
   * Retrieving vars (PGS)
   */
  $idProblem = Check::postGetSessionInt('id_problem');
  $idPatient = Check::postGetSessionInt('id_patient');

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

  $formVar["id_problem"] = $idProblem;
  $formVar["id_patient"] = $idPatient;
  $formVar["order_number"] = $problem->getOrderNumber();
  $formVar["opening_date"] = $problem->getOpeningDate();
  if ( !isset($formError) )
  {
    $formVar["last_update_date"] = $problem->getLastUpdateDate();
    $formVar["id_member"] = $problem->getIdMember();
    $formVar["closed_problem"] = (($problem->getClosingDate() != "" && $problem->getClosingDate() != "0000-00-00") ? "checked" : "");
    $formVar["meeting_place"] = $problem->getMeetingPlace();
    $formVar["wording"] = $problem->getWording();
    $formVar["subjective"] = $problem->getSubjective();
    $formVar["objective"] = $problem->getObjective();
    $formVar["appreciation"] = $problem->getAppreciation();
    $formVar["action_plan"] = $problem->getActionPlan();
    $formVar["prescription"] = $problem->getPrescription();
  }

  /**
   * Show page
   */
  $title = _("Edit Medical Problem");
  $titlePage = $patient->getName() . ' [' . $problem->getWordingPreview() . '] (' . $title . ')';
  $focusFormField = "wording"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");

  //$returnLocation = "../medical/problem_view.php?id_problem=" . $idProblem . "&id_patient=" . $idPatient;
  $returnLocation = "../medical/problem_view.php";

  /**
   * Breadcrumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/problem_view.php",
    _("Medical Problems Report") => "../medical/problem_list.php",
    $problem->getWordingPreview() => $returnLocation,
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_patient");
  unset($links);

  echo $patient->getHeader();

  echo Form::errorMsg();

  /**
   * Edit form
   */
  echo HTML::start('form', array('method' => 'post', 'action' => '../medical/problem_edit.php'));

  echo Form::hidden("id_problem", $formVar["id_problem"]);
  echo Form::hidden("last_update_date", $formVar["last_update_date"]);
  echo Form::hidden("id_patient", $formVar["id_patient"]);

  require_once("../medical/problem_fields.php");

  echo HTML::end('form');

  echo Msg::hint('* ' . _("Note: The fields with * are required."));

  echo HTML::para(HTML::link(_("Return"), $returnLocation));

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  require_once("../layout/footer.php");
?>
