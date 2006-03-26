<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_edit_form.php,v 1.23 2006/03/26 14:47:45 jact Exp $
 */

/**
 * user_edit_form.php
 *
 * Edition screen of user data
 *
 * @author jact <jachavar@gmail.com>
 */

  /**
   * Checking for get vars. Go back to users list if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]))
  {
    header("Location: ../admin/user_list.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = ((isset($_GET["all"])) ? "home" : "admin");
  $nav = "users";
  $returnLocation = ((isset($_GET["all"])) ? "../home/index.php" : "../admin/user_list.php");

  require_once("../shared/read_settings.php");
  if ( !isset($_GET["all"]) )
  {
    include_once("../shared/login_check.php");
  }
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError

  /**
   * Retrieving get vars
   */
  $idUser = intval($_GET["key"]);

  /**
   * Checking for $formError to read data from database
   */
  if ( !isset($formError) )
  {
    include_once("../classes/User_Query.php");

    /**
     * Search database
     */
    $userQ = new User_Query();
    $userQ->connect();

    if ( !$userQ->select($idUser) )
    {
      $userQ->close();
      include_once("../shared/header.php");

      HTML::message(_("That user does not exist."), OPEN_MSG_ERROR);

      include_once("../shared/footer.php");
      exit();
    }

    $user = $userQ->fetch();
    if ($user)
    {
      $formVar["id_user"] = $idUser;
      $formVar["id_member"] = $user->getIdMember();
      $formVar["login"] = $user->getLogin();
      $formVar["email"] = $user->getEmail();
      $formVar["actived"] = ($user->isActived() ? "checked" : "");
      $formVar["id_theme"] = $user->getIdTheme();
      $formVar["id_profile"] = $user->getIdProfile();
    }
    else
    {
      Error::fetch($userQ, false);
    }
    $userQ->freeResult();
    $userQ->close();
    unset($userQ);
    unset($user);
  }

  /**
   * Show page
   */
  $title = ((isset($_GET["all"])) ? _("Change User Data") : _("Edit User"));
  $focusFormField = "email"; // to avoid JavaScript mistakes in demo version
  require_once("../shared/header.php");

  /**
   * Bread Crumb
   */
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
  HTML::breadCrumb($links, "icon userIcon");
  unset($links);

  require_once("../shared/form_errors_msg.php");
?>

<script src="../scripts/md5.js" type="text/javascript"></script>

<script src="../scripts/password.php" type="text/javascript"></script>

<?php
  /**
   * Edit form
   */
  echo '<form method="post" action="../admin/user_edit.php" onsubmit="return md5Login(this);">' . "\n";

  Form::hidden("referer", "edit"); // to user_validate_post.php
  Form::hidden("id_user", $formVar["id_user"]);
  Form::hidden("id_member", $formVar["id_member"]);

  if (isset($_GET["all"]))
  {
    Form::hidden("all", "Y");
  }

  $action = "edit";
  require_once("../admin/user_fields.php");

  echo "</form>\n";

  HTML::message('* ' . _("Note: The fields with * are required."));

  if (isset($_GET["all"]))
  {
    HTML::message(_("Fill password fields only if you want to change it."), OPEN_MSG_INFO);
  }

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  require_once("../shared/footer.php");
?>
