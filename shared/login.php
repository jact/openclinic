<?php
/**
 * login.php
 *
 * User login process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: login.php,v 1.21 2006/04/10 19:57:28 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: ../shared/login_form.php");
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../classes/User_Query.php");
  require_once("../classes/Session_Query.php");
  require_once("../classes/Access_Page_Query.php");

  unset($formError); // to clean previous errors

  /**
   * Login edits
   */
  $errorFound = false;
  $loginSession = urlencode(Check::safeText($_POST["login_session"]));
  if ($loginSession == "")
  {
    $errorFound = true;
    $formError["login_session"] = _("This is a required field.");
  }

  /**
   * Password edits
   */
  $pwdSession = Check::safeText($_POST["md5_session"]);
  if ($pwdSession == "")
  {
    $errorFound = true;
    $formError["pwd_session"] = _("This is a required field.");
  }
  else
  {
    $userQ = new User_Query();
    $userQ->connect();

    $result = $userQ->existLogin($loginSession);
    if ( !$result )
    {
      $errorFound = true;
      $formError["login_session"] = _("Login unknown.");
    }
    else
    {
      if ( !$userQ->isActivated($loginSession) )
      {
        $userQ->close();

        header("Location: ../shared/login_suspended.php");
        exit();
      }

      $lastLogin = (isset($_SESSION["formVar"]["login_session"])) ? $_SESSION["formVar"]["login_session"] : "";
      if ( !$userQ->verifySignOn($loginSession, $pwdSession) )
      {
        $userQ->close();
        Error::query($userQ);
      }

      $user = $userQ->fetch();
      if ( !$user )
      {
        /**
         * Invalid password. Add one to login attempts.
         */
        $errorFound = true;
        $formError["pwd_session"] = _("Invalid sign on.");
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

        /**
         * Suspend user login if loginAttempts >= OPEN_MAX_LOGIN_ATTEMPTS
         */
        if (OPEN_MAX_LOGIN_ATTEMPTS && $sessLoginAttempts >= OPEN_MAX_LOGIN_ATTEMPTS)
        {
          $userQ->deactivate($loginSession);

          $userQ->close();

          header("Location: ../shared/login_suspended.php");
          exit();
        }
      }
    }
    $userQ->close();
  }

  /**
   * Redirect back to form if an error occurred
   */
  if ($errorFound)
  {
    $_SESSION["formVar"] = Check::safeArray($_POST);
    $_SESSION["formError"] = $formError;
    if (isset($sessLoginAttempts))
    {
      $_SESSION["loginAttempts"] = $sessLoginAttempts;
    }

    header("Location: ../shared/login_form.php");
    exit();
  }

  /**
   * Redirect to suspended message if suspended
   */
  if ( !$user->isActived() )
  {
    header("Location: ../shared/login_suspended.php");
    exit();
  }

  /**
   * Insert new session row with random token
   */
  $sessionQ = new Session_Query();
  $sessionQ->connect();

  $token = $sessionQ->getToken($user->getLogin());

  $sessionQ->close();
  unset($sessionQ);

  /**
   * Insert new user access
   */
  $accessQ = new Access_Page_Query();
  $accessQ->connect();

  $accessQ->insert($user);

  $accessQ->close();
  unset($accessQ);

  /**
   * Destroy form values and errors and reset sign on variables
   */
  $_SESSION["formVar"] = null; // for safety's sake
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

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

  /**
   * Redirect to return page
   */
  header("Location: " . urldecode($_SESSION["returnPage"]));
?>
