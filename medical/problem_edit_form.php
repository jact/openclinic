<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_edit_form.php,v 1.6 2004/07/07 17:22:58 jact Exp $
 */

/**
 * problem_edit_form.php
 ********************************************************************
 * Edition screen of a medical problem
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

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Problem_Query.php");
  require_once("../classes/Staff_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../lib/input_lib.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  // after login_check inclusion to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "wording";

  // after clean (get_form_vars.php)
  $postVars["id_problem"] = $idProblem;
  $postVars["id_patient"] = $idPatient;

  $problemQ = new problem_Query();
  $problemQ->connect();
  if ($problemQ->isError())
  {
    showQueryError($problemQ);
  }

  $numRows = $problemQ->select($idProblem);
  if ($problemQ->isError())
  {
    $problemQ->close();
    showQueryError($problemQ);
  }

  if ( !$numRows )
  {
    $problemQ->close();
    include_once("../shared/header.php");

    echo '<p>' . _("That medical problem does not exist.") . "</p>\n";

    include_once("../shared/footer.php");
    exit();
  }

  $problem = $problemQ->fetch();
  if ($problemQ->isError())
  {
    showFetchError($problemQ, false);
  }
  else
  {
    $postVars["last_update_date"] = date("d-m-Y"); //date("Y-m-d");
    $postVars["order_number"] = $problem->getOrderNumber();
    $postVars["opening_date"] = $problem->getOpeningDate();
    if (isset($_GET["reset"]))
    {
      $postVars["collegiate_number"] = $problem->getCollegiateNumber();
      $postVars["closed_problem"] = (($problem->getClosingDate() != "") ? "checked" : "");
      $postVars["meeting_place"] = $problem->getMeetingPlace();
      $postVars["wording"] = $problem->getWording();
      $postVars["subjective"] = $problem->getSubjective();
      $postVars["objective"] = $problem->getObjective();
      $postVars["appreciation"] = $problem->getAppreciation();
      $postVars["action_plan"] = $problem->getActionPlan();
      $postVars["prescription"] = $problem->getPrescription();
    }
  }
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Edit Medical Problem");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  $returnLocation = "../medical/problem_view.php?key=" . $idProblem . "&amp;pat=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Medical Problems Report") => "../medical/problem_list.php?key=" . $idPatient,
    _("View Medical Problem") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  showPatientHeader($idPatient);
  echo "<br />\n";

  require_once("../shared/form_errors_msg.php");
?>

<form method="post" action="../medical/problem_edit.php">
  <div>
<?php
  showInputHidden("id_problem", $postVars["id_problem"]);
  showInputHidden("last_update_date", $postVars["last_update_date"]);
  showInputHidden("id_patient", $postVars["id_patient"]);

  require_once("../medical/problem_fields.php");
?>
  </div>
</form>

<?php
  echo '<p class="advice">* ' . _("Note: The fields with * are required.") . "</p>\n";

  require_once("../shared/footer.php");
?>
