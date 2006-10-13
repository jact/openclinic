<?php
/**
 * user_new.php
 *
 * User addition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_new.php,v 1.14 2006/10/13 19:49:47 jact Exp $
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
   * Insert new user
   */
  $userQ = new User_Query();
  $userQ->connect();

  if ($userQ->existLogin($user->getLogin(), $user->getIdMember()))
  {
    $loginUsed = true;
  }
  else
  {
    $userQ->insert($user);
  }
  $userQ->close();
  unset($userQ);

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  $info = urlencode($user->getLogin());
  $returnLocation .= ((isset($loginUsed) && $loginUsed) ? "?login" : "?added") . "=Y&info=" . $info;
  unset($user);
  header("Location: " . $returnLocation);
?>
