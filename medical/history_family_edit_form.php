<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_family_edit_form.php,v 1.1 2004/03/24 19:47:10 jact Exp $
 */

/**
 * history_family_edit_form.php
 ********************************************************************
 * Edition screen of a patient family antecedents
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 24/03/04 20:47
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "history";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  // after login_check inclusion to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "parents_status_health";

  ////////////////////////////////////////////////////////////////////
  // Checking for query string flag to read data from database.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["key"]))
  {
    $idPatient = intval($_GET["key"]);

    include_once("../classes/History_Query.php");
    include_once("../lib/error_lib.php");

    $historyQ = new History_Query();
    $historyQ->connect();
    if ($historyQ->errorOccurred())
    {
      showQueryError($historyQ);
    }

    $numRows = $historyQ->selectFamily($idPatient);
    if ($historyQ->errorOccurred())
    {
      $historyQ->close();
      showQueryError($historyQ);
    }

    if ( !$numRows )
    {
      $historyQ->close();
      include_once("../shared/header.php");

      echo '<p>' . _("That patient does not exist.") . "</p>\n";

      include_once("../shared/footer.php");
      exit();
    }

    $history = $historyQ->fetchFamily();
    if ( !$history )
    {
      showQueryError($historyQ, false);
    }
    else
    {
      $postVars["id_patient"] = $history->getIdPatient();
      $postVars["parents_status_health"] = $history->getParentsStatusHealth();
      $postVars["brothers_status_health"] = $history->getBrothersStatusHealth();
      $postVars["spouse_childs_status_health"] = $history->getSpouseChildsStatusHealth();
      $postVars["family_illness"] = $history->getFamilyIllness();
    }
    $historyQ->freeResult();
    $historyQ->close();
    unset($historyQ);
    unset($history);
  }
  else
  {
    $idPatient = $postVars["id_patient"];
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Edit Family Antecedents");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  $returnLocation = "../medical/history_family_view.php?key=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Clinic History") => "../medical/history_list.php?key=" . $idPatient,
    _("View Family Antecedents") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  showPatientHeader($idPatient);
  echo "<br />\n";

  require_once("../shared/form_errors_msg.php");

  ////////////////////////////////////////////////////////////////////
  // Display update message if coming from setting_edit with a successful update.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["updated"]))
  {
    echo '<p class="error">' . _("Family Antecedents have been updated.") . "</p>\n";
  }
?>

<form method="post" action="../medical/history_family_edit.php">
  <div>
<?php
  showInputHidden("id_patient", $idPatient);

  require_once("../medical/history_family_fields.php");
?>
  </div>
</form>

<?php require_once("../shared/footer.php"); ?>
