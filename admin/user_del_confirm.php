<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_del_confirm.php,v 1.5 2004/10/03 10:27:03 jact Exp $
 */

/**
 * user_del_confirm.php
 ********************************************************************
 * Confirmation screen of an user deletion process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "users";
  $returnLocation = "../admin/user_list.php";

  ////////////////////////////////////////////////////////////////////
  // Checking for query string. Go back to users list if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || empty($_GET["login"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/validator_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idUser = intval($_GET["key"]);
  $login = safeText($_GET["login"]);

  ////////////////////////////////////////////////////////////////////
  // Show confirm page
  ////////////////////////////////////////////////////////////////////
  $title = _("Delete User");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Users") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "users.png");
  unset($links);
?>

<form method="post" action="../admin/user_del.php">
  <h3><?php echo _("Delete User"); ?></h3>

  <?php showMessage(sprintf(_("Are you sure you want to delete user, %s?"), $login)); ?>

  <p>
    <?php
      showInputHidden("id_user", $idUser);
      showInputHidden("login", $login);
      showInputButton("delete", _("Delete"));
      //showInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"');
    ?>
  </p>
</form>

<?php require_once("../shared/footer.php"); ?>
