<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_edit_form.php,v 1.1 2004/03/24 19:57:14 jact Exp $
 */

/**
 * user_edit_form.php
 ********************************************************************
 * Edition screen of user data
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 24/03/04 20:57
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
    $idUser = $_GET["key"];
    $postVars["id_user"] = $idUser;

    include_once("../classes/User_Query.php");
    include_once("../lib/error_lib.php");

    $userQ = new User_Query();
    $userQ->connect();
    if ($userQ->errorOccurred())
    {
      showQueryError($userQ);
    }

    $numRows = $userQ->select($idUser);
    if ($userQ->errorOccurred())
    {
      $userQ->close();
      showQueryError($userQ);
    }

    if ( !$numRows )
    {
      $userQ->close();
      include_once("../shared/header.php");

      echo '<p>' . _("That user does not exist.") . "</p>\n";

      include_once("../shared/footer.php");
      exit();
    }

    $user = $userQ->fetchUser();
    if ( !$user )
    {
      showQueryError($userQ, false);
    }
    else
    {
      $postVars["login"] = $user->getLogin();
      $postVars["email"] = $user->getEmail();
      $postVars["actived"] = ($user->isActived() ? "CHECKED" : "");
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

<script type="text/javascript">
<!--
function md5Login(f)
{
  if (f['md5_old'] != null)
  {
    f['md5_old'].value = hex_md5(f['old_pwd'].value);
    f['old_pwd'].value = '';
  }

  if (f['md5'] != null)
  {
    f['md5'].value = hex_md5(f['pwd'].value);
    f['pwd'].value = '';
  }

  if (f['md5_confirm'] != null)
  {
    f['md5_confirm'].value = hex_md5(f['pwd2'].value);
    f['pwd2'].value = '';
  }

  return true;
}
//-->
</script>

<form method="post" action="../admin/user_edit.php" onsubmit="return md5Login(this);">
  <div>
<?php
  showInputHidden("referer", "edit"); // to user_validate_post.php
  showInputHidden("id_user", $postVars["id_user"]);

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
  echo '<p class="small">* ' . _("Note: The changes in the fields * will be visible in the next session.") . "</p>\n";

  if (isset($_GET["all"]))
  {
    echo '<p class="small">' . _("Fill password fields only if you want to change it.") . "</p>\n";
  }

  require_once("../shared/footer.php");
?>
