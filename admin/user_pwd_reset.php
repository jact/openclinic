<?php
/**
 * user_pwd_reset.php
 *
 * Password's user reset process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_pwd_reset.php,v 1.13 2007/10/25 21:58:08 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  //$restrictInDemo = true;
  $returnLocation = "../admin/user_list.php";

  /**
   * Checking for post vars. Go back to $returnLocation if none found.
   */
  if (count($_POST) == 0 || !is_numeric($_POST["id_user"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/User_Query.php");

  /**
   * Validate data
   */
  $errorLocation = "../admin/user_pwd_reset_form.php?key=" . intval($_POST["id_user"]); // controlling var
  $user = new User();

  $user->setIdUser($_POST["id_user"]);

  $user->setLogin($_POST["login"]);

  $user->setPwd($_POST["md5"]);
  $_POST["pwd"] = "";

  $user->setPwd2($_POST["md5_confirm"]);
  $_POST["pwd2"] = "";

  if ( !$user->validatePwd() )
  {
    $formError["pwd"] = $user->getPwdError();

    $_SESSION["formVar"] = $_POST;
    $_SESSION["formError"] = $formError;

    header("Location: " . $errorLocation);
    exit();
  }

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  /**
   * Update user
   */
  $userQ = new User_Query();
  $userQ->connect();

  $userQ->resetPwd($user);

  FlashMsg::add(sprintf(_("Password of user, %s, has been reset."), $user->getLogin()));

  $userQ->close();
  unset($userQ);
  unset($user);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  header("Location: " . $returnLocation);
?>
