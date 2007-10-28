<?php
/**
 * problem_edit_form.php
 *
 * Edition screen of a medical problem
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_edit_form.php,v 1.33 2007/10/28 20:16:25 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = false;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/PatientInfo.php");
  require_once("../lib/ProblemInfo.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError

  /**
   * Retrieving vars (PGS)
   */
  $idProblem = Check::postGetSessionInt('id_problem');
  $idPatient = Check::postGetSessionInt('id_patient');

  $patient = new PatientInfo($idPatient);
  if ($patient->getName() == '')
  {
    FlashMsg::add(_("That patient does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  $problem = new ProblemInfo($idProblem);
  $problem = $problem->getObject();
  if ($problem == null)
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
  $titlePage = $patient->getName() . ' [' . fieldPreview($problem->getWording()) . '] (' . $title . ')';
  $focusFormField = "wording"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");

  //$returnLocation = "../medical/problem_view.php?id_problem=" . $idProblem . "&id_patient=" . $idPatient;
  $returnLocation = "../medical/problem_view.php";

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/problem_view.php",
    _("Medical Problems Report") => "../medical/problem_list.php",
    $problem->getWording() => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  $patient->showHeader();

  require_once("../shared/form_errors_msg.php");

  /**
   * Edit form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../medical/problem_edit.php'));

  Form::hidden("id_problem", $formVar["id_problem"]);
  Form::hidden("last_update_date", $formVar["last_update_date"]);
  Form::hidden("id_patient", $formVar["id_patient"]);

  require_once("../medical/problem_fields.php");

  HTML::end('form');

  Msg::hint('* ' . _("Note: The fields with * are required."));

  HTML::para(HTML::strLink(_("Return"), $returnLocation));

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  require_once("../layout/footer.php");
?>
