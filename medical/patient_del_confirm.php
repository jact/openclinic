<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_del_confirm.php,v 1.9 2005/07/31 11:08:13 jact Exp $
 */

/**
 * patient_del_confirm.php
 *
 * Confirmation screen of a patient deletion process
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || empty($_GET["name"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "social";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");

  /**
   * Retrieving get vars
   */
  $idPatient = intval($_GET["key"]);
  $patName = Check::safeText($_GET["name"]);

  /**
   * Show page
   */
  $title = _("Delete Patient");
  require_once("../shared/header.php");

  $returnLocation = "../medical/patient_view.php?key=" . $idPatient . "&amp;reset=Y";

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Social Data") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);
?>

<form method="post" action="../medical/patient_del.php">
  <h3><?php echo $title; ?></h3>

  <?php HTML::message(sprintf(_("Are you sure you want to delete patient, %s?"), $patName)); ?>

  <p>
    <?php
      Form::hidden("id_patient", "id_patient", $idPatient);
      Form::hidden("name", "name", $patName);

      Form::button("delete", "delete", _("Delete"));
      //Form::button("return", "return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"');
    ?>
  </p>
</form>

<?php require_once("../shared/footer.php"); ?>
