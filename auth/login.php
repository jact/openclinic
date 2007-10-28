<?php
/**
 * login.php
 *
 * User login process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: login.php,v 1.6 2007/10/28 11:32:27 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: ../auth/login_form.php");
    exit();
  }

  require_once("../config/environment.php");
  require_once("../lib/Form.php");

  Form::compareToken('../auth/login_form.php');

  require_once("../model/User_Query.php");
  require_once("../model/Session_Query.php");
  require_once("../model/Access_Page_Query.php");

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
    $pwdSession = Check::safeText($_POST["pwd_session"]); // JavaScript disabled?
    if ($pwdSession == "")
    {
      $errorFound = true;
      $formError["pwd_session"] = _("This is a required field.");
    }
    else
    {
      $pwdSession = md5($pwdSession); // JavaScript disabled!
    }
  }

  if ( !isset($formError["pwd_session"]) )
  {
    $userQ = new User_Query();
    $userQ->connect();

    if ( !$userQ->existLogin($loginSession) )
    {
      $errorFound = true;
      $formError["login_session"] = _("Login unknown.");
    }
    else
    {
      if ( !$userQ->isActivated($loginSession) )
      {
        $userQ->close();

        $_SESSION = array(); // deregister all current session variables

        FlashMsg::add(_("Your user account has been suspended. Contact with administrator to resolve this problem."));
        header("Location: ../home/index.php");
        exit();
      }

      $formSession = Form::getSession();
      $lastLogin = isset($formSession['var']['login_session']) ? $formSession['var']['login_session'] : '';
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

          $_SESSION = array(); // deregister all current session variables

          FlashMsg::add(_("Your user account has been suspended. Contact with administrator to resolve this problem."),
            OPEN_MSG_WARNING
          );
          header("Location: ../home/index.php");
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
    Form::setSession(Check::safeArray($_POST), $formError);
    if (isset($sessLoginAttempts))
    {
      $_SESSION["loginAttempts"] = $sessLoginAttempts;
    }

    header("Location: ../auth/login_form.php");
    exit();
  }

  /**
   * Redirect to index page if suspended
   */
  if ( !$user->isActived() )
  {
    $_SESSION = array(); // deregister all current session variables

    FlashMsg::add(_("Your user account has been suspended. Contact with administrator to resolve this problem."),
      OPEN_MSG_WARNING
    );
    header("Location: ../home/index.php");
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
  Form::unsetSession();

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
  //$_SESSION["hasStatsAuth"] = ($user->getIdProfile() <= OPEN_PROFILE_DOCTOR); // @todo?
  $_SESSION["userTheme"] = $user->getIdTheme();
  $_SESSION["userId"] = $user->getIdUser();
  $_SESSION["loginIP"] = $_SERVER["REMOTE_ADDR"];

  if ( !isset($_SESSION["returnPage"]) )
  {
    $_SESSION["returnPage"] = "../home/index.php";
  }

  /**
   * Redirect to return page
   */
  header("Location: " . urldecode($_SESSION["returnPage"]));
?>
