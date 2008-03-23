<?php
/**
 * user_new_form.php
 *
 * Addition screen of an user
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_new_form.php,v 1.33 2008/03/23 11:58:57 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "users";
  $returnLocation = "../admin/user_list.php";
  $isMd5 = true;

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR);

  require_once("../lib/Check.php");

  /**
   * Checking for post or get vars
   */
  if (isset($_POST["id_member_login"]))
  {
    Form::compareToken($returnLocation);

    $array = explode(OPEN_SEPARATOR, Check::safeText($_POST["id_member_login"]), 2);
    $idMember = $array[0];
    $formVar["id_member"] = $idMember;
    $login = $array[1];
    $formVar["login"] = $login;
    unset($array);
  }
  elseif (isset($_GET["id_member"]) && isset($_GET["login"]))
  {
    $idMember = intval($_GET["id_member"]);
    $formVar["id_member"] = $idMember;
    $login = Check::safeText($_GET["login"]);
    $formVar["login"] = $login;
  }
  else // something wrong in fields...
  {
    $formSession = Form::getSession();
    $formVar["id_member"] = $formSession['var']['id_member'];
    $formVar["login"] = $formSession['var']['login'];
  }

  /**
   * Show page
   */
  $title = _("Add New User");
  $focusFormField = "pwd"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");

  /**
   * Breadcrumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Users") => $returnLocation,
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_user");
  unset($links);

  echo Form::errorMsg();

  /**
   * Edit form
   */
  echo HTML::start('form',
    array(
      'id' => 'userNew',
      'method' => 'post',
      'action' => '../admin/user_new.php'
    )
  );

  echo Form::hidden("referer", "new"); // to user_validate_post.php
  echo Form::hidden("id_member", $formVar["id_member"]);
  echo Form::hidden("login", $formVar["login"]);

  $action = "new";
  require_once("../admin/user_fields.php");

  echo HTML::end('form');

  echo Msg::hint('* ' . _("Note: The fields with * are required."));

  echo HTML::para(HTML::link(_("Return"), $returnLocation));

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  require_once("../layout/footer.php");
?>
