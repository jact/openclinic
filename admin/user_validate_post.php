<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_validate_post.php,v 1.4 2004/10/17 14:56:03 jact Exp $
 */

/**
 * user_validate_post.php
 ********************************************************************
 * Validate post data of an user
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * @since 0.6
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['PATH_TRANSLATED'])
  {
    header("Location: ../index.php");
    exit();
  }

  $user->setIdMember($_POST["id_member"]);
  $_POST["id_member"] = $user->getIdMember();

  $user->setLogin($_POST["login"]);
  $_POST["login"] = $user->getLogin();

  $user->setPwd($_POST["md5"]);
  $_POST["pwd"] = "";

  $user->setPwd2($_POST["md5_confirm"]);
  $_POST["pwd2"] = "";

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
    $pageErrors["login"] = $user->getLoginError();
    $pageErrors["pwd"] = $user->getPwdError();
    $pageErrors["email"] = $user->getEmailError();

    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;

    header("Location: " . $errorLocation);
    exit();
  }
?>
