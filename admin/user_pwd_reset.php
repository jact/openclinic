<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_pwd_reset.php,v 1.3 2004/07/07 17:21:53 jact Exp $
 */

/**
 * user_pwd_reset.php
 ********************************************************************
 * Password's user reset process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
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

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/User_Query.php");
  require_once("../lib/error_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Validate data
  ////////////////////////////////////////////////////////////////////
  $user = new User();

  $user->setIdUser($_POST["id_user"]);

  $user->setLogin($_POST["login"]);

  $user->setPwd($_POST["md5"]);
  $_POST["pwd"] = "";

  $user->setPwd2($_POST["md5_confirm"]);
  $_POST["pwd2"] = "";

  if ( !$user->validatePwd() )
  {
    $pageErrors["pwd"] = $user->getPwdError();

    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;

    header("Location: ../admin/user_pwd_reset_form.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Update user
  ////////////////////////////////////////////////////////////////////
  $userQ = new User_Query();
  $userQ->connect();
  if ($userQ->isError())
  {
    showQueryError($userQ);
  }

  $userQ->resetPwd($user);
  if ($userQ->isError())
  {
    $userQ->close();
    showQueryError($userQ);
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
  $title = _("Reset User Password");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Users") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "users.png");
  unset($links);

  echo '<p>' . sprintf(_("Password of user, %s, has been reset."), $user->getLogin()) . "</p>\n";
  unset($user);

  echo '<p><a href="' . $returnLocation . '">' . _("Return to users list") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
