<?php
/**
 * user_new.php
 *
 * User addition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_new.php,v 1.16 2007/10/25 21:58:08 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  //$restrictInDemo = true;
  $errorLocation = "../admin/user_new_form.php";
  $returnLocation = "../admin/user_list.php";

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: " . $errorLocation);
    exit();
  }

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/User_Query.php");

  /**
   * Validate data
   */
  $user = new User();

  require_once("../admin/user_validate_post.php");

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  /**
   * Insert new user
   */
  $userQ = new User_Query();
  $userQ->connect();

  if ($userQ->existLogin($user->getLogin(), $user->getIdMember()))
  {
    FlashMsg::add(sprintf(_("Login, %s, already exists. The changes have no effect."), $user->getLogin()),
      OPEN_MSG_WARNING
    );
  }
  else
  {
    $userQ->insert($user);
    FlashMsg::add(sprintf(_("User, %s, has been added."), $user->getLogin()));
  }
  $userQ->close();
  unset($userQ);
  unset($user);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  header("Location: " . $returnLocation);
?>
