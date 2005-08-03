<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: index.php,v 1.4 2005/08/03 18:02:40 jact Exp $
 */

/**
 * index.php
 *
 * Summary page of the Medical Records tab
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "summary";
  $onlyDoctor = true;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");

  /**
   * Show page
   */
  $title = _("Medical Records");
  require_once("../shared/header.php");
?>

<h1>
  <img src="../images/medical.png" width="60" height="60" alt="" />
  <?php echo $title; ?>
</h1>

<p><?php echo _("Use the following functions located in the left hand navigation area to manage your medical records."); ?></p>

<h2>
  <a href="../medical/patient_search_form.php"><img src="../images/search.png" width="40" height="40" alt="<?php echo _("Search Patient"); ?>" title="<?php echo _("Search Patient"); ?>" /></a>
  <a href="../medical/patient_search_form.php"><?php echo _("Search Patient"); ?></a>
</h2>

<p><?php echo _("Search and view patients. Once a patient is selected you can:"); ?></p>

<ul>
  <li><?php echo _("manage social data"); ?></li>
  <li><?php echo _("manage clinic history"); ?></li>
  <li><?php echo _("manage problems report"); ?></li>
  <li><?php echo _("print medical record"); ?></li>
</ul>

<?php
  if ($hasMedicalAdminAuth)
  {
?>

<hr />

<h2>
  <a href="../medical/patient_new_form.php?reset=Y"><img src="../images/patient.png" width="40" height="40" alt="<?php echo _("New Patient"); ?>" title="<?php echo _("New Patient"); ?>" /></a>
  <a href="../medical/patient_new_form.php?reset=Y"><?php echo _("New Patient"); ?></a>
</h2>

<p><?php echo _("Build a new patient information in medical records system."); ?></p>

<?php
  } // end if

  require_once("../shared/footer.php");
?>
