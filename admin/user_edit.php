<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_edit.php,v 1.1 2004/03/24 19:56:56 jact Exp $
 */

/**
 * user_edit.php
 ********************************************************************
 * User edition process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 24/03/04 20:56
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "users";
  //$restrictInDemo = true;
  $returnLocation = "../admin/user_list.php";

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to users list if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: " . $returnLocation);
    exit();
  }

  $errorLocation = "../admin/user_edit_form.php" . ((isset($_POST["all"])) ? "?all=Y" : "");

  require_once("../shared/read_settings.php");
  if ( !isset($_POST["all"]) )
  {
    include_once("../shared/login_check.php");
  }
  require_once("../classes/User_Query.php");
  require_once("../lib/error_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Validate data
  ////////////////////////////////////////////////////////////////////
  $user = new User();

  $user->setIdUser($_POST["id_user"]);

  require_once("../admin/user_validate_post.php");

  ////////////////////////////////////////////////////////////////////
  // Update user
  ////////////////////////////////////////////////////////////////////
  $userQ = new User_Query();
  $userQ->connect();
  if ($userQ->errorOccurred())
  {
    showQueryError($userQ);
  }

  if ($userQ->existLogin($user->getLogin(), $user->getIdUser()))
  {
    $loginUsed = true;
  }
  else
  {
    if ( !$userQ->update($user) )
    {
      $userQ->close();
      showQueryError($userQ);
    }
  }

  if ($changePwd && !$loginUsed)
  {
    if ( !$userQ->verifySignOn($_POST["login"], $_POST["md5_old"], true) )
    {
      $userQ->close();

      unset($pageErrors);
      $pageErrors["old_pwd"] = ((trim($_POST["md5_old"]) == "") ? _("This is a required field.") : _("This field is not correct."));

      $_SESSION["postVars"] = $_POST;
      $_SESSION["pageErrors"] = $pageErrors;

      header("Location: " . $errorLocation);
      exit();
    }
    if ( !$userQ->resetPwd($user) )
    {
      $userQ->close();
      showQueryError($userQ);
    }
  }
  $userQ->close();
  unset($userQ);

  ////////////////////////////////////////////////////////////////////
  // Destroy form values and errors
  ////////////////////////////////////////////////////////////////////
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  ////////////////////////////////////////////////////////////////////
  // Show success page
  ////////////////////////////////////////////////////////////////////
  $title = ((isset($_POST["all"])) ? _("Change User Data") : _("Edit User"));
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  if ( !isset($_POST["all"]) )
  {
    $links = array(
      _("Admin") => "../admin/index.php",
      _("Users") => $returnLocation,
      $title => ""
    );
  }
  else
  {
    $links = array(
      _("Home") => $returnLocation,
      $title => ""
    );
  }
  showNavLinks($links, "users.png");
  unset($links);

  echo (isset($loginUsed) && $loginUsed)
    ? '<p>' . sprintf(_("Login, %s, already exists. The changes have no effect."), $user->getLogin()) . "</p>\n"
    : '<p>' . sprintf(_("User, %s, has been updated."), $user->getLogin()) . "</p>\n";

  unset($user);

  echo (isset($_POST["all"]))
    ? '<p><a href="../home/index.php">' . _("Return to home page") . "</a></p>\n"
    : '<p><a href="' . $returnLocation . '">' . _("Return to users list") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
