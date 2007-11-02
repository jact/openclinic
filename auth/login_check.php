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
 * @version   CVS: $Id: login_check.php,v 1.9 2007/11/02 20:40:47 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  /**
   * Checking to see if we are in demo mode and if we should not execute this page
   */
  if (isset($restrictInDemo) && $restrictInDemo && OPEN_DEMO)
  {
    FlashMsg::add(_("This function is not available in this demo version of OpenClinic."));
    header("Location: ../home/index.php");
    exit();
  }

  /**
   * Disabling users control for demo
   */
  if (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    $hasMedicalAdminAuth = true;

    return;
  }

  //works in PHP >= 4.1
  $_SESSION['auth']['return_page'] = /*urlencode(*/$_SERVER['REQUEST_URI']/*)*/;

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
    //$_SESSION = array(); // deregister all current session variables
    //session_destroy(); // clean up session ID

    //header("Location: ../auth/login_form.php");
    include_once("../auth/logout.php");
    exit();
  }

  /**
   * Checking session table to see if token has timed out
   */
  require_once("../model/Query/Session.php");

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

  /**
   * Checking authorization for this tab
   * The session authorization flags were set at login in login.php
   */
  if (isset($tab))
  {
    if ($tab == "medical")
    {
      if ( !$_SESSION['auth']['is_medical'] && (isset($onlyDoctor) && !$onlyDoctor) )
      {
        FlashMsg::add(sprintf(_("You are not authorized to use %s tab."), _("Medical Records")));
        header("Location: ../home/index.php");
        exit();
      }
    }
    /*elseif ($tab == "stats")
    {
      if ( !$_SESSION['auth']['is_stats'] )
      {
        FlashMsg::add(sprintf(_("You are not authorized to use %s tab."), _("Stats")));
        header("Location: ../home/index.php");
        exit();
      }
    }*/
    elseif ($tab == "admin")
    {
      if ( !$_SESSION['auth']['is_admin'] )
      {
        FlashMsg::add(sprintf(_("You are not authorized to use %s tab."), _("Admin")));
        header("Location: ../home/index.php");
        exit();
      }
    }
  }

  if ( !$_SESSION['auth']['is_admin'] && !$_SESSION['auth']['is_medical'] )
  {
    $hasMedicalAdminAuth = (isset($onlyDoctor) ? !($onlyDoctor) : true);
  }
  else
  {
    $hasMedicalAdminAuth = true;
  }
?>
