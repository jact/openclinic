<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_new_form.php,v 1.2 2004/04/24 14:52:14 jact Exp $
 */

/**
 * patient_new_form.php
 ********************************************************************
 * Addition screen of a patient
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "new";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Staff_Query.php");
  require_once("../lib/input_lib.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  // after clean (get_form_vars.php)
  //$postVars["last_update_date"] = date("d-m-Y"); //date("Y-m-d");

  // after login_check inclusion to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "nif";

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Add New Patient");
  require_once("../shared/header.php");

  $returnLocation = "../medical/index.php";

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => $returnLocation,
    _("New Patient") => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  require_once("../shared/form_errors_msg.php");
?>

<form method="post" action="../medical/patient_new.php">
  <div>
<?php
  //showInputHidden("last_update_date", $postVars['last_update_date']);

  require_once("../medical/patient_fields.php");
?>
  </div>
</form>

<?php
  echo '<p class="small">* ' . _("Note: The fields with * are required.") . "</p>\n";

  require_once("../shared/footer.php");
?>
