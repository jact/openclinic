<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: index.php,v 1.4 2004/07/26 18:44:47 jact Exp $
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

  showMessage(_("OpenClinic is an easy to use, open source, medical records system. When you select any of the following tabs you will be prompted to login."), OPEN_MSG_INFO);
?>

<table>
  <thead>
    <tr>
      <th>
        <?php echo _("Tab"); ?>
      </th>

      <th>
        <?php echo _("Description"); ?>
      </th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td class="center">
        <a href="../medical/index.php"><?php echo _("Medical Records"); ?></a>

        <p><a href="../medical/index.php"><img src="../images/medical.png" width="60" height="60" alt="<?php echo _("Medical Records"); ?>" title="<?php echo _("Medical Records"); ?>" /></a></p>
      </td>

      <td>
        <?php echo _("Use this tab to manage your patient's medical records."); ?>

        <ul>
          <li>
            <p><?php echo _("Patient's Administration:"); ?></p>

            <ul>
              <li><?php echo _("Search, new, delete, edit"); ?></li>
              <li><?php echo _("Social Data"); ?></li>
              <li><?php echo _("Clinic History"); ?></li>
              <li><?php echo _("Problem Reports"); ?></li>
            </ul>
          </li>
        </ul>
      </td>
    </tr>

    <!--tr>
      <td class="center">
        <a href=""><?php //echo _("Stats"); ?></a>

        <p><a href=""><img src="../images/stats.png" width="60" height="60" alt="<?php //echo _("Stats"); ?>" title="<?php //echo _("Stats"); ?>" /></a></p>
      </td>

      <td>
        <?php //echo _("indexStatsDesc1"); ?>

        <ul>
          <li><?php //echo _("indexStatsDesc2"); ?></li>
        </ul>
      </td>
    </tr-->

    <tr>
      <td class="center">
        <a href="../admin/index.php"><?php echo _("Admin"); ?></a>

        <p><a href="../admin/index.php"><img src="../images/admin.png" width="60" height="60" alt="<?php echo _("Admin"); ?>" title="<?php echo _("Admin"); ?>" /></a></p>
      </td>

      <td>
        <?php echo _("Use this tab to manage administrative options."); ?>

        <ul>
          <li><?php echo _("Staff members"); ?></li>
          <li><?php echo _("Config settings"); ?></li>
          <li><?php echo _("User profiles"); ?></li>
          <li><?php echo _("Clinic themes editor"); ?></li>
          <li><?php echo _("System users"); ?></li>
          <li><?php echo _("Dumps"); ?></li>
          <li><?php echo _("Logs"); ?></li>
        </ul>
      </td>
    </tr>
  </tbody>
</table>

<?php require_once("../shared/footer.php"); ?>
