<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_del_confirm.php,v 1.8 2005/07/21 16:55:57 jact Exp $
 */

/**
 * staff_del_confirm.php
 *
 * Confirmation screen of a staff member deletion process
 *
 * Author: jact <jachavar@gmail.com>
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

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/Check.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idMember = intval($_GET["key"]);
  $surname1 = Check::safeText($_GET["sur1"]);
  $surname2 = Check::safeText($_GET["sur2"]);
  $firstName = Check::safeText($_GET["first"]);

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
  <h3><?php echo _("Delete Staff Member"); ?></h3>

  <?php HTML::message(sprintf(_("Are you sure you want to delete staff member, %s %s %s?"), $firstName, $surname1, $surname2)); ?>

  <p>
    <?php
      showInputHidden("id_member", $idMember);
      showInputButton("delete", _("Delete"));
      //showInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"');
    ?>
  </p>
</form>

<hr />

<?php
  HTML::message('* ' . _("Note: The del function will delete the related user too (if exists)."));

  require_once("../shared/footer.php");
?>
