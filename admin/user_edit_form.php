<?php
/**
 * user_edit_form.php
 *
 * Edition screen of user data
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_edit_form.php,v 1.27 2006/10/13 19:49:47 jact Exp $
 * @author    jact <jachavar@gmail.com>
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
  $isMd5 = true;

  require_once("../config/environment.php");
  if ( !isset($_GET["all"]) )
  {
    include_once("../auth/login_check.php");
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
    include_once("../model/User_Query.php");

    /**
     * Search database
     */
    $userQ = new User_Query();
    $userQ->connect();

    if ( !$userQ->select($idUser) )
    {
      $userQ->close();
      include_once("../layout/header.php");

      HTML::message(_("That user does not exist."), OPEN_MSG_ERROR);

      include_once("../layout/footer.php");
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
  require_once("../layout/header.php");

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

  /**
   * Edit form
   */
  HTML::start('form',
    array(
      'method' => 'post',
      'action' => '../admin/user_edit.php',
      'onclick' => 'return md5Login(this);'
    )
  );

  Form::hidden("referer", "edit"); // to user_validate_post.php
  Form::hidden("id_user", $formVar["id_user"]);
  Form::hidden("id_member", $formVar["id_member"]);

  if (isset($_GET["all"]))
  {
    Form::hidden("all", "Y");
  }

  $action = "edit";
  require_once("../admin/user_fields.php");

  HTML::end('form');

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

  require_once("../layout/footer.php");
?>
