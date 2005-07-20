<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_new_form.php,v 1.10 2005/07/20 20:24:33 jact Exp $
 */

/**
 * user_new_form.php
 *
 * Addition screen of an user
 *
 * Author: jact <jachavar@gmail.com>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "users";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors
  require_once("../lib/Check.php");

  // after login_check inclusion to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "pwd";

  ////////////////////////////////////////////////////////////////////
  // Checking for post or get vars
  ////////////////////////////////////////////////////////////////////
  if (isset($_POST["id_member_login"]))
  {
    $array = explode(OPEN_SEPARATOR, Check::safeText($_POST["id_member_login"]), 2);
    $idMember = $array[0];
    $postVars["id_member"] = $idMember;
    $login = $array[1];
    $postVars["login"] = $login;
    unset($array);
  }
  elseif (isset($_GET["id_member"]) && isset($_GET["login"]))
  {
    $idMember = intval($_GET["id_member"]);
    $postVars["id_member"] = $idMember;
    $login = Check::safeText($_GET["login"]);
    $postVars["login"] = $login;
  }
  else
  {
    $postVars["id_member"] = $_SESSION["postVars"]["id_member"];
    $postVars["login"] = $_SESSION["postVars"]["login"];
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Add New User");
  require_once("../shared/header.php");

  $returnLocation = "../admin/user_list.php";

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

  require_once("../shared/form_errors_msg.php");
?>

<script src="../scripts/md5.js" type="text/javascript"></script>

<script src="../scripts/password.php" type="text/javascript"></script>

<form method="post" action="../admin/user_new.php" onsubmit="return md5Login(this);">
  <div>
<?php
  showInputHidden("referer", "new"); // to user_validate_post.php
  showInputHidden("id_member", $postVars["id_member"]);
  showInputHidden("login", $postVars["login"]);

  $action = "new";
  require_once("../admin/user_fields.php");
?>
  </div>
</form>

<?php
  showMessage('* ' . _("Note: The fields with * are required."));

  require_once("../shared/footer.php");
?>
