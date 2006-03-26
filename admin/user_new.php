<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_new.php,v 1.12 2006/03/26 14:47:45 jact Exp $
 */

/**
 * user_new.php
 *
 * User addition process
 *
 * @author jact <jachavar@gmail.com>
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

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/User_Query.php");

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
