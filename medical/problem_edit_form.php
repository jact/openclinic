<?php
/**
 * problem_edit_form.php
 *
 * Edition screen of a medical problem
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_edit_form.php,v 1.26 2006/10/13 19:53:16 jact Exp $
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

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Problem_Page_Query.php");
  require_once("../model/Staff_Query.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError

  /**
   * Retrieving get vars
   */
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);

  /**
   * Search database
   */
  $problemQ = new Problem_Page_Query();
  $problemQ->connect();

  if ( !$problemQ->select($idProblem) )
  {
    $problemQ->close();
    include_once("../layout/header.php");

    HTML::message(_("That medical problem does not exist."), OPEN_MSG_ERROR);

    include_once("../layout/footer.php");
    exit();
  }

  $problem = $problemQ->fetch();
  if ($problem)
  {
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
  }
  else
  {
    Error::fetch($problemQ, false);
  }
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);

  /**
   * Show page
   */
  $title = _("Edit Medical Problem");
  $focusFormField = "wording"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");
  require_once("../medical/patient_header.php");

  $returnLocation = "../medical/problem_view.php?key=" . $idProblem . "&pat=" . $idPatient;

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Medical Problems Report") => "../medical/problem_list.php?key=" . $idPatient,
    _("View Medical Problem") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  showPatientHeader($idPatient);

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

  HTML::message('* ' . _("Note: The fields with * are required."));

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  require_once("../layout/footer.php");
?>
