<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: login.php,v 1.13 2005/06/14 18:55:04 jact Exp $
 */

/**
 * login.php
 *
 * User login process
 *
 * Author: jact <jachavar@gmail.com>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: ../shared/login_form.php");
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../classes/User_Query.php");
  require_once("../classes/Session_Query.php");
  require_once("../classes/Access_Page_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../lib/validator_lib.php");

  unset($pageErrors); // to clean previous errors

  ////////////////////////////////////////////////////////////////////
  // Login edits
  ////////////////////////////////////////////////////////////////////
  $errorFound = false;
  $loginSession = urlencode(safeText($_POST["login_session"]));
  if ($loginSession == "")
  {
    $errorFound = true;
    $pageErrors["login_session"] = _("This is a required field.");
  }

  ////////////////////////////////////////////////////////////////////
  // Password edits
  ////////////////////////////////////////////////////////////////////
  $pwdSession = safeText($_POST["md5"]);
  if ($pwdSession == "")
  {
    $errorFound = true;
    $pageErrors["pwd_session"] = _("This is a required field.");
  }
  else
  {
    $userQ = new User_Query();
    $userQ->connect();
    if ($userQ->isError())
    {
      showQueryError($userQ);
    }

    $result = $userQ->existLogin($loginSession);
    if ($userQ->isError())
    {
      $userQ->close();
      showQueryError($userQ);
    }

    if ( !$result )
    {
      $errorFound = true;
      $pageErrors["login_session"] = _("Login unknown.");
    }
    else
    {
      if ( !$userQ->isActivated($loginSession) )
      {
        $userQ->close();

        header("Location: ../shared/login_suspended.php");
        exit();
      }

      $lastLogin = (isset($_SESSION["postVars"]["login_session"])) ? $_SESSION["postVars"]["login_session"] : "";
      $userQ->verifySignOn($loginSession, $pwdSession);
      if ($userQ->isError())
      {
        $userQ->close();
        showQueryError($userQ);
      }

      $user = $userQ->fetch();
      if ($userQ->isError())
      {
        // Invalid password. Add one to login attempts.
        $errorFound = true;
        $pageErrors["pwd_session"] = _("Invalid sign on.");
        if ( !isset($_SESSION["loginAttempts"]) || ($_SESSION["loginAttempts"] == "") )
        {
          $sessLoginAttempts = 1;
        }
        else
        {
          if ($loginSession == $lastLogin)
          {
            $sessLoginAttempts = $_SESSION["loginAttempts"] + 1;
          }
          else
          {
            $sessLoginAttempts = 1;
          }
        }
        $userQ->clearErrors(); // needed after empty fetch(), from verifySigOn()

        // Suspend user login if loginAttempts >= OPEN_MAX_LOGIN_ATTEMPTS
        if (OPEN_MAX_LOGIN_ATTEMPTS && $sessLoginAttempts >= OPEN_MAX_LOGIN_ATTEMPTS)
        {
          $userQ->deactivate($loginSession);
          if ($userQ->isError())
          {
            $userQ->close();
            showQueryError($userQ);
          }
          $userQ->close();

          header("Location: ../shared/login_suspended.php");
          exit();
        }
      }
    }
    $userQ->close();
  }

  ////////////////////////////////////////////////////////////////////
  // Redirect back to form if an error occurred
  ////////////////////////////////////////////////////////////////////
  if ($errorFound)
  {
    $_SESSION["postVars"] = safeArray($_POST);
    $_SESSION["pageErrors"] = $pageErrors;
    if (isset($sessLoginAttempts))
    {
      $_SESSION["loginAttempts"] = $sessLoginAttempts;
    }

    header("Location: ../shared/login_form.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Redirect to suspended message if suspended
  ////////////////////////////////////////////////////////////////////
  if ( !$user->isActived() )
  {
    header("Location: ../shared/login_suspended.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Insert new session row with random token
  ////////////////////////////////////////////////////////////////////
  $sessionQ = new Session_Query();
  $sessionQ->connect();
  if ($sessionQ->isError())
  {
    showQueryError($sessionQ);
  }

  $token = $sessionQ->getToken($user->getLogin());
  if ($sessionQ->isError())
  {
    $sessionQ->close();
    showQueryError($sessionQ);
  }
  $sessionQ->close();
  unset($sessionQ);

  ////////////////////////////////////////////////////////////////////
  // Insert new user access
  ////////////////////////////////////////////////////////////////////
  $accessQ = new Access_Page_Query();
  $accessQ->connect();
  if ($accessQ->isError())
  {
    showQueryError($accessQ);
  }

  $accessQ->insert($user);
  if ($accessQ->isError())
  {
    $accessQ->close();
    showQueryError($accessQ);
  }
  $accessQ->close();
  unset($accessQ);

  ////////////////////////////////////////////////////////////////////
  // Destroy form values and errors and reset sign on variables
  ////////////////////////////////////////////////////////////////////
  $_SESSION["postVars"] = null; // for safety's sake
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  // works in PHP >= 4.1.0
  $_SESSION["memberUser"] = $user->getIdMember();
  $_SESSION["loginSession"] = $user->getLogin();
  $_SESSION["token"] = $token;
  if (isset($sessLoginAttempts))
  {
    $_SESSION["loginAttempts"] = $sessLoginAttempts;
  }
  $_SESSION["hasAdminAuth"] = ($user->getIdProfile() <= OPEN_PROFILE_ADMINISTRATOR);
  $_SESSION["hasMedicalAuth"] = ($user->getIdProfile() <= OPEN_PROFILE_ADMINISTRATIVE);
  $_SESSION["hasStatsAuth"] = ($user->getIdProfile() <= OPEN_PROFILE_DOCTOR);
  $_SESSION["userTheme"] = $user->getIdTheme();
  $_SESSION["userId"] = $user->getIdUser();
  $_SESSION["loginIP"] = $_SERVER["REMOTE_ADDR"];

  if ( !isset($_SESSION["returnPage"]) )
  {
    $_SESSION["returnPage"] = urlencode("../home/index.php");
  }

  ////////////////////////////////////////////////////////////////////
  // Redirect to return page
  ////////////////////////////////////////////////////////////////////
  header("Location: " . urldecode($_SESSION["returnPage"]));
?>
