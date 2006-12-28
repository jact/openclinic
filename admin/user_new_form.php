<?php
/**
 * user_new_form.php
 *
 * Addition screen of an user
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_new_form.php,v 1.25 2006/12/28 16:19:05 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "users";
  $returnLocation = "../admin/user_list.php";
  $isMd5 = true;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError
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
  else
  {
    $formVar["id_member"] = $_SESSION["formVar"]["id_member"];
    $formVar["login"] = $_SESSION["formVar"]["login"];
  }

  /**
   * Show page
   */
  $title = _("Add New User");
  $focusFormField = "pwd"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Users") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon userIcon");
  unset($links);

  require_once("../shared/form_errors_msg.php");

  /**
   * Edit form
   */
  HTML::start('form',
    array(
      'id' => 'userNew',
      'method' => 'post',
      'action' => '../admin/user_new.php'
    )
  );

  Form::hidden("referer", "new"); // to user_validate_post.php
  Form::hidden("id_member", $formVar["id_member"]);
  Form::hidden("login", $formVar["login"]);

  $action = "new";
  require_once("../admin/user_fields.php");

  HTML::end('form');

  HTML::message('* ' . _("Note: The fields with * are required."));

  HTML::para(HTML::strLink(_("Return"), $returnLocation));

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  require_once("../layout/footer.php");
?>
