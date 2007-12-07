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
 * @version   CVS: $Id: user_pwd_reset.php,v 1.18 2007/12/07 16:50:50 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $returnLocation = "../admin/user_list.php";

  /**
   * Checking for post vars. Go back to $returnLocation if none found.
   */
  if (count($_POST) == 0 || !is_numeric($_POST["id_user"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR);

  require_once("../lib/Form.php");

  /**
   * Validate data
   */
  $errorLocation = "../admin/user_pwd_reset_form.php?id_user=" . intval($_POST["id_user"]); // controlling var
  require_once("../model/Query/User.php");
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

    Form::setSession($_POST, $formError);

    header("Location: " . $errorLocation);
    exit();
  }

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  /**
   * Update user
   */
  $userQ = new Query_User();
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
