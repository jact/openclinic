<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_del_confirm.php,v 1.3 2004/04/24 16:46:06 jact Exp $
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

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idUser = intval($_GET["key"]);
  $login = $_GET["login"];

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");

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
  <div class="center">
    <?php
      showInputHidden("id_user", $idUser);
      showInputHidden("login", $login);
    ?>

    <table>
      <thead>
        <tr>
          <th>
            <?php echo _("Delete User"); ?>
          </th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>
            <?php echo sprintf(_("Are you sure you want to delete user, %s?"), $login); ?>
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

<?php require_once("../shared/footer.php"); ?>
