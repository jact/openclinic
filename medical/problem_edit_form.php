<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_edit_form.php,v 1.22 2006/03/26 15:20:49 jact Exp $
 */

/**
 * problem_edit_form.php
 *
 * Edition screen of a medical problem
 *
 * @author jact <jachavar@gmail.com>
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

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Problem_Page_Query.php");
  require_once("../classes/Staff_Query.php");
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
    include_once("../shared/header.php");

    HTML::message(_("That medical problem does not exist."), OPEN_MSG_ERROR);

    include_once("../shared/footer.php");
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
      $formVar["closed_problem"] = (($problem->getClosingDate() != "") ? "checked" : "");
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
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  $returnLocation = "../medical/problem_view.php?key=" . $idProblem . "&amp;pat=" . $idPatient;

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
  echo '<form method="post" action="../medical/problem_edit.php">' . "\n";

  Form::hidden("id_problem", $formVar["id_problem"]);
  Form::hidden("last_update_date", $formVar["last_update_date"]);
  Form::hidden("id_patient", $formVar["id_patient"]);

  require_once("../medical/problem_fields.php");

  echo "</form>\n";

  HTML::message('* ' . _("Note: The fields with * are required."));

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  require_once("../shared/footer.php");
?>
