<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_new.php,v 1.9 2005/08/15 16:33:41 jact Exp $
 */

/**
 * user_new.php
 *
 * User addition process
 *
 * Author: jact <jachavar@gmail.com>
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
  if ($userQ->isError())
  {
    Error::query($userQ);
  }

  if ($userQ->existLogin($user->getLogin(), $user->getIdMember()))
  {
    $loginUsed = true;
  }
  else
  {
    $userQ->insert($user);
    if ($userQ->isError())
    {
      $userQ->close();
      Error::query($userQ);
    }
  }
  $userQ->close();
  unset($userQ);

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  $info = urlencode($user->getLogin());
  $returnLocation .= ((isset($loginUsed) && $loginUsed) ? "?login" : "?added") . "=Y&info=" . $info;
  unset($user);
  header("Location: " . $returnLocation);
?>
