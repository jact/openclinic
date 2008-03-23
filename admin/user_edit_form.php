<?php
/**
 * user_edit_form.php
 *
 * Edition screen of user data
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_edit_form.php,v 1.39 2008/03/23 11:58:57 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for get vars. Go back to users list if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["id_user"]))
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
    /**
     * Checking permissions
     */
    include_once("../auth/login_check.php");
    loginCheck(OPEN_PROFILE_ADMINISTRATOR);
  }

  /**
   * Retrieving get vars
   */
  $idUser = intval($_GET["id_user"]);

  /**
   * Checking for $formError to read data from database
   */
  if ( !isset($formError) )
  {
    include_once("../model/Query/User.php");

    /**
     * Search database
     */
    $userQ = new Query_User();
    if ( !$userQ->select($idUser) )
    {
      $userQ->close();

      FlashMsg::add(_("That user does not exist."), OPEN_MSG_ERROR);
      header("Location: " . $returnLocation);
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
   * Breadcrumb
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
  echo HTML::breadcrumb($links, "icon icon_user");
  unset($links);

  echo Form::errorMsg();

  /**
   * Edit form
   */
  echo HTML::start('form',
    array(
      'id' => 'userEdit',
      'method' => 'post',
      'action' => '../admin/user_edit.php'
    )
  );

  echo Form::hidden("referer", "edit"); // to user_validate_post.php
  echo Form::hidden("id_user", $formVar["id_user"]);
  echo Form::hidden("id_member", $formVar["id_member"]);

  if (isset($_GET["all"]))
  {
    echo Form::hidden("all", "Y");
  }

  $action = "edit";
  require_once("../admin/user_fields.php");

  echo HTML::end('form');

  echo Msg::hint('* ' . _("Note: The fields with * are required."));

  if (isset($_GET["all"]))
  {
    echo Msg::hint(_("Fill password fields only if you want to change it."));
  }

  echo HTML::para(HTML::link(_("Return"), $returnLocation));

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  require_once("../layout/footer.php");
?>
