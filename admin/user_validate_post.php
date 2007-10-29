<?php
/**
 * user_validate_post.php
 *
 * Validate post data of an user
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_validate_post.php,v 1.13 2007/10/29 20:03:54 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.6
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../lib/Form.php");
  Form::compareToken($errorLocation);

  $user->setIdMember($_POST["id_member"]);
  $_POST["id_member"] = $user->getIdMember();

  $user->setLogin($_POST["login"]);
  $_POST["login"] = $user->getLogin();

  if (isset($_POST["md5"]))
  {
    $user->setPwd($_POST["md5"]);
    $_POST["pwd"] = "";
  }

  if (isset($_POST["md5_confirm"]))
  {
    $user->setPwd2($_POST["md5_confirm"]);
    $_POST["pwd2"] = "";
  }

  $user->setEmail($_POST["email"]);
  $_POST["email"] = $user->getEmail();

  $user->setActived(isset($_POST["actived"]));

  $user->setIdTheme($_POST["id_theme"]);
  $_POST["id_theme"] = $user->getIdTheme();

  $user->setIdProfile($_POST["id_profile"]);
  $_POST["id_profile"] = $user->getIdProfile();

  $validData = $user->validateData();
  if ($_POST["referer"] == "edit")
  {
    $aux = md5("");
    $changePwd = (isset($_POST["all"]) && !($aux == $_POST["md5_old"] && $aux == $_POST["md5"] && $aux == $_POST["md5_confirm"]));
    $validPwd = ($changePwd ? $user->validatePwd() : true);
  }
  else
  {
    $validPwd = $user->validatePwd();
  }
  if ( !($validData && $validPwd) )
  {
    $formError["login"] = $user->getLoginError();
    $formError["pwd"] = $user->getPwdError();
    $formError["email"] = $user->getEmailError();

    Form::setSession($_POST, $formError);

    header("Location: " . $errorLocation);
    exit();
  }
?>
