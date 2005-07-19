<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_edit_form.php,v 1.14 2005/07/19 19:50:04 jact Exp $
 */

/**
 * user_edit_form.php
 *
 * Edition screen of user data
 *
 * Author: jact <jachavar@gmail.com>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to users list if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || empty($_GET["key"]))
  {
    header("Location: ../admin/user_list.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = ((isset($_GET["all"])) ? "home" : "admin");
  $nav = "users";

  require_once("../shared/read_settings.php");
  if ( !isset($_GET["all"]) )
  {
    include_once("../shared/login_check.php");
  }
  require_once("../lib/input_lib.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  // after login_check inclusion to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "email";

  ////////////////////////////////////////////////////////////////////
  // Checking for query string flag to read data from database.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["key"]))
  {
    $idUser = intval($_GET["key"]);
    $postVars["id_user"] = $idUser;

    include_once("../classes/User_Query.php");

    $userQ = new User_Query();
    $userQ->connect();
    if ($userQ->isError())
    {
      Error::query($userQ);
    }

    $numRows = $userQ->select($idUser);
    if ($userQ->isError())
    {
      $userQ->close();
      Error::query($userQ);
    }

    if ( !$numRows )
    {
      $userQ->close();
      include_once("../shared/header.php");

      showMessage(_("That user does not exist."), OPEN_MSG_ERROR);

      include_once("../shared/footer.php");
      exit();
    }

    $user = $userQ->fetch();
    if ($userQ->isError())
    {
      Error::fetch($userQ, false);
    }
    else
    {
      $postVars["id_member"] = $user->getIdMember();
      $postVars["login"] = $user->getLogin();
      $postVars["email"] = $user->getEmail();
      $postVars["actived"] = ($user->isActived() ? "checked" : "");
      $postVars["id_theme"] = $user->getIdTheme();
      $postVars["id_profile"] = $user->getIdProfile();
    }
    $userQ->freeResult();
    $userQ->close();
    unset($userQ);
    unset($user);
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = ((isset($_GET["all"])) ? _("Change User Data") : _("Edit User"));
  require_once("../shared/header.php");

  $returnLocation = ((isset($_GET["all"])) ? "../home/index.php" : "../admin/user_list.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  if ( !isset($_GET["all"]) )
  {
    $links = array(
      _("Admin") => "../admin/index.php",
      _("Users") => $returnLocation,
      $title => ""
    );
  }
  else
  {
    $links = array(
      _("Home") => $returnLocation,
      $title => ""
    );
  }
  showNavLinks($links, "users.png");
  unset($links);

  require_once("../shared/form_errors_msg.php");
?>

<script src="../scripts/md5.js" type="text/javascript"></script>

<script src="../scripts/password.php" type="text/javascript"></script>

<form method="post" action="../admin/user_edit.php" onsubmit="return md5Login(this);">
  <div>
<?php
  showInputHidden("referer", "edit"); // to user_validate_post.php
  showInputHidden("id_user", $postVars["id_user"]);
  showInputHidden("id_member", $postVars["id_member"]);

  if (isset($_GET["all"]))
  {
    showInputHidden("all", "Y");
  }

  $action = "edit";
  require_once("../admin/user_fields.php");
?>
  </div>
</form>

<?php
  showMessage('* ' . _("Note: The fields with * are required."));

  if (isset($_GET["all"]))
  {
    showMessage(_("Fill password fields only if you want to change it."), OPEN_MSG_INFO);
  }

  require_once("../shared/footer.php");
?>
