<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_del_confirm.php,v 1.5 2004/10/03 10:26:49 jact Exp $
 */

/**
 * problem_del_confirm.php
 ********************************************************************
 * Confirmation screen of a medical problem deletion process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for query string. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || !is_numeric($_GET["pat"]) || empty($_GET["wording"]))
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

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/validator_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);
  $wording = safeText($_GET["wording"]);

  ////////////////////////////////////////////////////////////////////
  // Show confirm page
  ////////////////////////////////////////////////////////////////////
  $title = _("Delete Medical Problem");
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
    _("View Medical Problem") => "../medical/problem_view.php?key=" . $idProblem . "&amp;pat=" . $idPatient,
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  showPatientHeader($idPatient);
  echo "<br />\n";
?>

<form method="post" action="../medical/problem_del.php">
  <h3><?php echo _("Delete Medical Problem"); ?></h3>

  <?php showMessage(sprintf(_("Are you sure you want to delete medical problem, %s, from list?"), $wording)); ?>

  <p>
    <?php
      showInputHidden("id_problem", $idProblem);
      showInputHidden("id_patient", $idPatient);
      showInputHidden("wording", $wording);

      showInputButton("delete", _("Delete"));
      //showInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"');
    ?>
  </p>
</form>

<?php require_once("../shared/footer.php"); ?>
