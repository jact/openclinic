<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_del_confirm.php,v 1.8 2005/07/28 17:46:27 jact Exp $
 */

/**
 * user_del_confirm.php
 *
 * Confirmation screen of an user deletion process
 *
 * Author: jact <jachavar@gmail.com>
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
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idUser = intval($_GET["key"]);
  $login = Check::safeText($_GET["login"]);

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

  <?php HTML::message(sprintf(_("Are you sure you want to delete user, %s?"), $login)); ?>

  <p>
    <?php
      Form::hidden("id_user", "id_user", $idUser);
      Form::hidden("login", "login", $login);
      Form::button("delete", "delete", _("Delete"));
      //Form::button("return", "return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"');
    ?>
  </p>
</form>

<?php require_once("../shared/footer.php"); ?>
