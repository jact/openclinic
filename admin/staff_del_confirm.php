<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_del_confirm.php,v 1.3 2004/04/24 16:45:46 jact Exp $
 */

/**
 * staff_del_confirm.php
 ********************************************************************
 * Confirmation screen of a staff member deletion process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "staff";
  $returnLocation = "../admin/staff_list.php";

  ////////////////////////////////////////////////////////////////////
  // Checking for query string. Go back to staff list if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || empty($_GET["sur1"]) || empty($_GET["sur2"]) || empty($_GET["first"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idMember = intval($_GET["key"]);
  $surname1 = $_GET["sur1"];
  $surname2 = $_GET["sur2"];
  $firstName = $_GET["first"];

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Show confirm page
  ////////////////////////////////////////////////////////////////////
  $title = _("Delete Staff Member");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Staff Members") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "staff.png");
  unset($links);
?>

<form method="post" action="../admin/staff_del.php">
  <div class="center">
    <?php showInputHidden("id_member", $idMember); ?>

    <table>
      <thead>
        <tr>
          <th>
            <?php echo _("Delete Staff Member"); ?>
          </th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>
          <?php echo sprintf(_("Are you sure you want to delete staff member, %s %s %s?"), $firstName, $surname1, $surname2); ?>
          </td>
        </tr>

        <tr>
          <td class="center">
            <?php
              showInputButton("delete", _("Delete"));
              showInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"');
            ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</form>

<?php
  echo '<p class="small">* ' . _("Note: The del function will delete the related user too (if exists).") . "</p>\n";

  require_once("../shared/footer.php");
?>
