<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: index.php,v 1.2 2004/04/23 20:36:50 jact Exp $
 */

/**
 * index.php
 ********************************************************************
 * Summary page of the Admin tab
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "summary";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Admin");
  require_once("../shared/header.php");
?>

<h1>
  <img src="../images/admin.png" width="60" height="60" alt="" />
  <?php echo $title; ?>
</h1>

<p><?php echo _("Use the following functions located in the left hand navigation area to manage your clinic's staff and administrative records."); ?></p>

<table>
  <thead>
    <tr>
      <th>
        <?php echo _("Option"); ?>
      </th>

      <th>
        <?php echo _("Description"); ?>
      </th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td class="center">
        <a href="../admin/setting_edit_form.php?reset=Y"><?php echo _("Config settings"); ?></a>

        <p><a href="../admin/setting_edit_form.php?reset=Y"><img src="../images/config_clinic.png" width="40" height="40" alt="<?php echo _("Config settings"); ?>" title="<?php echo _("Config settings"); ?>" /></a></p>
      </td>

      <td>
        <?php echo _("Update config settings."); ?>
      </td>
    </tr>

    <tr>
      <td class="center">
        <a href="../admin/theme_list.php"><?php echo _("Themes"); ?></a>

        <p><a href="../admin/theme_list.php"><img src="../images/themes.png" width="40" height="40" alt="<?php echo _("Themes"); ?>" title="<?php echo _("Themes"); ?>" /></a></p>
      </td>

      <td>
        <?php echo _("View the list of site look and feel themes."); ?>

        <p><?php echo _("From this list you can:"); ?></p>

        <ul>
          <li><?php echo _("set the theme in use by default for your site"); ?></li>
          <li><?php echo _("build a new site theme"); ?></li>
          <li><?php echo _("edit an existing theme"); ?></li>
          <li><?php echo _("delete a theme"); ?></li>
        </ul>
      </td>
    </tr>

    <tr>
      <td class="center">
        <a href="../admin/staff_list.php"><?php echo _("Staff Members"); ?></a>

        <p><a href="../admin/staff_list.php"><img src="../images/staff.png" width="40" height="40" alt="<?php echo _("Staff Members"); ?>" title="<?php echo _("Staff Members"); ?>" /></a></p>
      </td>

      <td>
        <?php echo _("View the clinic staff member list."); ?>

        <p><?php echo _("From this list you can:"); ?></p>

        <ul>
          <li><?php echo _("build a new staff member"); ?></li>
          <li><?php echo _("edit the staff member information"); ?></li>
          <li><?php echo _("build or edit the clinic user associated"); ?></li>
          <li><?php echo _("delete a staff member"); ?></li>
        </ul>
      </td>
    </tr>

    <tr>
      <td class="center">
        <a href="../admin/user_list.php"><?php echo _("Users"); ?></a>

        <p><a href="../admin/user_list.php"><img src="../images/users.png" width="40" height="40" alt="<?php echo _("Users"); ?>" title="<?php echo _("Users"); ?>" /></a></p>
      </td>

      <td>
        <?php echo _("View the list of clinic users."); ?>

        <p><?php echo _("From this list you can:"); ?></p>

        <ul>
          <li><?php echo _("build a new clinic user"); ?></li>
          <li><?php echo _("edit a existing clinic user"); ?></li>
          <li><?php echo _("edit the staff member information"); ?></li>
          <li><?php echo _("reset a user's password"); ?></li>
          <li><?php echo _("delete a clinic user"); ?></li>
        </ul>
      </td>
    </tr>

    <tr>
      <td class="center">
        <a href="../admin/profile_list.php"><?php echo _("Profiles"); ?></a>

        <p><a href="../admin/profile_list.php"><img src="../images/profiles.png" width="40" height="40" alt="<?php echo _("Profiles"); ?>" title="<?php echo _("Profiles"); ?>" /></a></p>
      </td>

      <td>
        <?php echo _("View the list of user profiles."); ?>

        <p><?php echo _("A profile describes the set of permissions for a particular user."); ?></p>

        <p><?php echo _("From this list you can:"); ?></p>

        <ul>
          <li><?php echo _("edit a profile to translate it"); ?></li>
        </ul>
      </td>
    </tr>

    <tr>
      <td class="center">
        <a href="../admin/dump_view_form.php"><?php echo _("Dumps"); ?></a>

        <p><a href="../admin/dump_view_form.php"><img src="../images/dumps.png" width="40" height="40" alt="<?php echo _("Dumps"); ?>" title="<?php echo _("Dumps"); ?>" /></a></p>
      </td>

      <td>
        <?php echo _("From this option you can:"); ?>

        <ul>
          <li><?php echo _("install dump from file"); ?></li>
          <li><?php echo sprintf(_("export database dump to %s format"), "MySQL"); ?></li>
          <li><?php echo sprintf(_("export database dump to %s format"), "XML"); ?></li>
          <li><?php echo sprintf(_("export database dump to %s format"), "CSV"); ?></li>
          <li><?php echo _("optimize database"); ?></li>
        </ul>
      </td>
    </tr>

    <tr>
      <td class="center">
        <a href="../admin/log_stats.php"><?php echo _("Logs"); ?></a>

        <p><a href="../admin/log_stats.php"><img src="../images/logs.png" width="40" height="40" alt="<?php echo _("Logs"); ?>" title="<?php echo _("Logs"); ?>" /></a></p>
      </td>

      <td>
        <?php echo _("View logs generated by the program:"); ?>

        <ul>
          <li><?php echo _("user's accesses logs"); ?></li>
          <li><?php echo _("operations with medical records logs"); ?></li>
          <li><?php echo _("statistics for years, months, days and hours"); ?></li>
        </ul>
      </td>
    </tr>
  </tbody>
</table>

<?php require_once("../shared/footer.php"); ?>
