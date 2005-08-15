<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_edit.php,v 1.10 2005/08/15 10:44:46 jact Exp $
 */

/**
 * user_edit.php
 *
 * User edition process
 *
 * Author: jact <jachavar@gmail.com>
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

  /**
   * Controlling vars
   */
  $errorLocation = "../admin/user_edit_form.php?key=" . intval($_POST["id_user"]) . ((isset($_POST["all"])) ? "&all=Y" : "");
  // Redefinition if it is needed after count($_POST)
  $returnLocation = ((isset($_POST["all"])) ? "../home/index.php" : "../admin/user_list.php");

  require_once("../shared/read_settings.php");
  if ( !isset($_POST["all"]) )
  {
    include_once("../shared/login_check.php");
  }
  require_once("../classes/User_Query.php");

  /**
   * Validate data
   */
  $user = new User();

  $user->setIdUser($_POST["id_user"]);

  require_once("../admin/user_validate_post.php");

  /**
   * Update user
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
    $userQ->update($user);
    if ($userQ->isError())
    {
      $userQ->close();
      Error::query($userQ);
    }

    /**
     * updating session variables if user is current user
     */
    if (isset($_POST["all"]))
    {
      $_SESSION['loginSession'] = $user->getLogin();
      $_SESSION['userTheme'] = $user->getIdTheme();
    }
  }

  if ($changePwd && !$loginUsed)
  {
    $userQ->verifySignOn($_POST["login"], $_POST["md5_old"], true);
    if ($userQ->isError())
    {
      $userQ->close();

      unset($pageErrors);
      $pageErrors["old_pwd"] = ((trim($_POST["md5_old"]) == "") ? _("This is a required field.") : _("This field is not correct."));

      $_SESSION["postVars"] = $_POST;
      $_SESSION["pageErrors"] = $pageErrors;

      header("Location: " . $errorLocation);
      exit();
    }

    $userQ->resetPwd($user);
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
  $returnLocation .= ((isset($loginUsed) && $loginUsed) ? "?login" : "?updated") . "=Y&info=" . $info;
  unset($user);
  header("Location: " . $returnLocation);
?>
