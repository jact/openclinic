<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_new_form.php,v 1.3 2004/04/24 18:02:18 jact Exp $
 */

/**
 * problem_new_form.php
 ********************************************************************
 * Addition screen of a medical problem
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || empty($_GET["key"])) // $_GET["num"] can be empty
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
  $idPatient = $_GET["key"];
  $orderNumber = $_GET["num"];

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");
  require_once("../classes/Staff_Query.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  // after login_check inclusion to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "wording";

  // after clean (get_form_vars.php)
  $postVars["id_patient"] = $idPatient;
  //$postVars["collegiate_number"] = ???; // si no está vacía y es la primera vez que se accede aquí es igual al médico que le corresponde por cupo
  $postVars["order_number"] = $orderNumber + 1;
  $postVars["opening_date"] = date("d-m-Y"); //date("Y-m-d");
  $postVars["last_update_date"] = date("d-m-Y"); //date("Y-m-d");

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Add New Medical Problem");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  $returnLocation = "../medical/problem_list.php?key=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Medical Problems Report") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  if ( !showPatientHeader($idPatient) )
  {
    echo _("That patient does not exist.");

    include_once("../shared/footer.php");
    exit();
  }
  echo "<br />\n";

  debug($postVars);

  require_once("../shared/form_errors_msg.php");
?>

<form method="post" action="../medical/problem_new.php">
  <div>
<?php
  showInputHidden("last_update_date", $postVars['last_update_date']);
  showInputHidden("id_patient", $idPatient);
  showInputHidden("opening_date", $postVars['opening_date']);
  showInputHidden("order_number", $postVars['order_number']);

  require_once("../medical/problem_fields.php");
?>
  </div>
</form>

<?php
  echo '<p class="advice">* ' . _("Note: The fields with * are required.") . "</p>\n";

  require_once("../shared/footer.php");
?>
