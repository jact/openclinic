<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: index.php,v 1.5 2004/08/02 10:07:57 jact Exp $
 */

/**
 * index.php
 ********************************************************************
 * Summary page of the Home tab
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "home";
  $nav = "home";

  require_once("../shared/read_settings.php");

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Welcome to OpenClinic");
  require_once("../shared/header.php");

  echo '<h1>' . $title . "</h1>\n";

  ////////////////////////////////////////////////////////////////////
  // Display update message if coming from edit with a successful update.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["updated"]) && isset($_GET["info"]))
  {
    showMessage(sprintf(_("User, %s, has been updated."), urldecode($_GET["info"])), OPEN_MSG_INFO);
  }

  ////////////////////////////////////////////////////////////////////
  // Display login used message.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["login"]) && isset($_GET["info"]))
  {
    showMessage(sprintf(_("Login, %s, already exists. The changes have no effect."), urldecode($_GET["info"])), OPEN_MSG_INFO);
  }

  echo '<p>' . _("OpenClinic is an easy to use, open source, medical records system.") . "</p>\n";
  echo '<p>' . _("When you select any of the following tabs you will be prompted to login.") . "</p>\n";
?>

<h2>
  <a href="../medical/index.php"><img src="../images/medical.png" width="60" height="60" alt="<?php echo _("Medical Records"); ?>" title="<?php echo _("Medical Records"); ?>" /></a>
  <a href="../medical/index.php"><?php echo _("Medical Records"); ?></a>
</h2>

<p><?php echo _("Use this tab to manage your patient's medical records."); ?></p>

<p><?php echo _("Patient's Administration:"); ?></p>

<ul>
  <li><?php echo _("Search, new, delete, edit"); ?></li>
  <li><?php echo _("Social Data"); ?></li>
  <li><?php echo _("Clinic History"); ?></li>
  <li><?php echo _("Problem Reports"); ?></li>
</ul>

<hr />

<h2>
  <a href="../admin/index.php"><img src="../images/admin.png" width="60" height="60" alt="<?php echo _("Admin"); ?>" title="<?php echo _("Admin"); ?>" /></a>
  <a href="../admin/index.php"><?php echo _("Admin"); ?></a>
</h2>

<p><?php echo _("Use this tab to manage administrative options."); ?></p>

<ul>
  <li><?php echo _("Staff members"); ?></li>
  <li><?php echo _("Config settings"); ?></li>
  <li><?php echo _("User profiles"); ?></li>
  <li><?php echo _("Clinic themes editor"); ?></li>
  <li><?php echo _("System users"); ?></li>
  <li><?php echo _("Dumps"); ?></li>
  <li><?php echo _("Logs"); ?></li>
</ul>

<?php require_once("../shared/footer.php"); ?>
