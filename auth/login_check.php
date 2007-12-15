<?php
/**
 * login_check.php
 *
 * Used to verify sign on token on every secured page.
 * Redirects to the login page if token not valid.
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: login_check.php,v 1.14 2007/12/15 15:02:49 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
executionProtection(__FILE__);

require_once("../config/environment.php");
require_once("../model/Query/Session.php");

/**
 * void loginCheck(int $profilePage = OPEN_PROFILE_FREE, bool $inDemo = true)
 *
 * @param int $profilePage (optional) one of this values:
 *  OPEN_PROFILE_FREE
 *  OPEN_PROFILE_ADMINISTRATOR
 *  OPEN_PROFILE_ADMINISTRATIVE
 *  OPEN_PROFILE_DOCTOR
 * @param bool $inDemo (optional) restricted in DEMO version?
 * @return void
 * @access public
 * @see OPEN_DEMO
 * @since 0.8
 */
function loginCheck($profilePage = OPEN_PROFILE_FREE, $inDemo = true)
{
  /**
   * Checking to see if we are in demo mode and if we should not execute this page
   */
  if ( !$inDemo && (defined("OPEN_DEMO") && OPEN_DEMO) )
  {
    FlashMsg::add(_("This function is not available in this demo version of OpenClinic.")); // @fixme OPEN_APP_NAME
    header("Location: ../home/index.php");
    exit();
  }

  /**
   * Disabling users control for demo
   */
  if (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    $_SESSION['auth']['is_admin'] = true;
    $_SESSION['auth']['is_administrative'] = true;
    $_SESSION['auth']['is_doctor'] = true;

    return;
  }

  // before possible login_form.php redirections
  $_SESSION['auth']['return_page'] = $_SERVER['REQUEST_URI'];

  /**
   * Checking to see if session variables exist
   */
  if ( !isset($_SESSION['auth']['login_session']) || ($_SESSION['auth']['login_session'] == "") )
  {
    header("Location: ../auth/login_form.php");
    exit();
  }

  if ( !isset($_SESSION['auth']['token']) || $_SESSION['auth']['token'] == "" )
  {
    header("Location: ../auth/login_form.php");
    exit();
  }

  /**
   * Checking if the request is from a different IP to previously
   */
  if (isset($_SESSION['auth']['login_ip']) && $_SESSION['auth']['login_ip'] != $_SERVER['REMOTE_ADDR'])
  {
    // This is possibly a session hijack attempt
    include_once("../auth/logout.php");
    exit();
  }

  /**
   * Checking session validation
   */
  $chk = md5(
    isset($_SERVER['HTTP_ACCEPT_CHARSET']) ? $_SERVER['HTTP_ACCEPT_CHARSET'] : $_SERVER['HTTP_ACCEPT']
    . $_SERVER['HTTP_ACCEPT_ENCODING']
    . $_SERVER['HTTP_ACCEPT_LANGUAGE']
    . $_SERVER['HTTP_USER_AGENT']
  );
  if ($_SESSION['auth']['sign'] != $chk)
  {
    // This is possibly a session hijack attempt
    include_once("../auth/logout.php");
    exit();
  }

  /**
   * Checking session table to see if token has timed out
   */
  $sessQ = new Query_Session();
  if ( !$sessQ->validToken($_SESSION['auth']['login_session'], $_SESSION['auth']['token']) )
  {
    $sessQ->close();

    $_SESSION['auth']['invalid_token'] = true;
    FlashMsg::add(_("Session timeout"));
    header("Location: ../auth/login_form.php");
    exit();
  }
  $sessQ->close();
  unset($sessQ);

  /**
   * Here, the session is valid!
   */
  if (isset($_SESSION['auth']['invalid_token']))
  {
    unset($_SESSION['auth']['invalid_token']);
  }
  session_regenerate_id(); // to avoid Session Fixation

  /**
   * Checking authorization for this page
   * The session authorization flags were set at login in login.php
   */
  if ($profilePage != OPEN_PROFILE_FREE
    && $profilePage != OPEN_PROFILE_ADMINISTRATOR
    && $profilePage != OPEN_PROFILE_DOCTOR
    && $profilePage != OPEN_PROFILE_ADMINISTRATIVE)
  {
    FlashMsg::add(_("Invalid profile page"));
    header("Location: ../auth/login_form.php");
    exit();
  }

  if (($profilePage == OPEN_PROFILE_ADMINISTRATOR && !$_SESSION['auth']['is_admin'])
    || ($profilePage == OPEN_PROFILE_ADMINISTRATIVE && !$_SESSION['auth']['is_administrative'])
    || ($profilePage == OPEN_PROFILE_DOCTOR && !$_SESSION['auth']['is_doctor']))
  {
    FlashMsg::add(_("You are not authorized to use this page."));
    header("Location: ../home/index.php");
    exit();
  }
}
?>
