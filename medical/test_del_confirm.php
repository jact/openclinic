<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: test_del_confirm.php,v 1.7 2005/07/20 20:25:24 jact Exp $
 */

/**
 * test_del_confirm.php
 *
 * Confirmation screen of a medical test deletion process
 *
 * Author: jact <jachavar@gmail.com>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || !is_numeric($_GET["test"]) || !is_numeric($_GET["pat"]) || empty($_GET["file"]))
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
  $restrictInDemo = true; // To prevent users' malice

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/Check.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idProblem = intval($_GET["key"]);
  $idTest = intval($_GET["test"]);
  $idPatient = intval($_GET["pat"]);
  $file = Check::safeText($_GET["file"]);

  ////////////////////////////////////////////////////////////////////
  // Show confirm page
  ////////////////////////////////////////////////////////////////////
  $title = _("Delete Medical Test");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");
  require_once("../medical/problem_header.php");

  $returnLocation = "../medical/test_list.php?key=" . $idProblem . "&amp;pat=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Medical Problems Report") => "../medical/problem_list.php?key=" . $idPatient,
    _("View Medical Problem") => "../medical/problem_view.php?key=" . $idProblem . "&amp;pat=" . $idPatient,
    _("View Medical Tests") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  showPatientHeader($idPatient);
  showProblemHeader($idProblem);
  echo "<br />\n"; // @todo: should be deleted
?>

<form method="post" action="../medical/test_del.php?key=<?php echo $idProblem; ?>&amp;test=<?php echo $idTest; ?>&amp;pat=<?php echo $idPatient; ?>&amp;file=<?php echo $file; ?>">
  <h3><?php echo $title; ?></h3>

  <?php showMessage(sprintf(_("Are you sure you want to delete medical test, %s, from list?"), $file)); ?>

  <p>
    <?php
      showInputHidden("id_problem", $idProblem);
      showInputHidden("id_test", $idTest);
      showInputHidden("id_patient", $idPatient);
      showInputHidden("file", $file);

      showInputButton("delete", _("Delete"));
      //showInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"');
    ?>
  </p>
</form>

<?php require_once("../shared/footer.php"); ?>
