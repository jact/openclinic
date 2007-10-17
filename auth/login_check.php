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
 * @version   CVS: $Id: login_check.php,v 1.4 2007/10/17 19:15:16 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  /**
   * Checking to see if we are in demo mode and if we should not execute this page
   */
  if (isset($restrictInDemo) && $restrictInDemo && OPEN_DEMO)
  {
    FlashMsg::add(_("This function is not available in this demo version of OpenClinic."));
    header("Location: ../home/index.php");
    exit();
  }

  require_once("../model/Session_Query.php");

  /**
   * Disabling users control for demo
   */
  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    //works in PHP >= 4.1
    $_SESSION['returnPage'] = urlencode($_SERVER['REQUEST_URI']);

    /**
     * Checking to see if session variables exist
     */
    if ( !isset($_SESSION['loginSession']) || ($_SESSION['loginSession'] == "") )
    {
      header("Location: ../auth/login_form.php");
      exit();
    }

    if ( !isset($_SESSION['token']) || $_SESSION['token'] == "" )
    {
      header("Location: ../auth/login_form.php");
      exit();
    }

    /**
     * Checking if the request is from a different IP to previously
     */
    if (isset($_SESSION['loginIP']) && $_SESSION['loginIP'] != $_SERVER['REMOTE_ADDR'])
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
    $sessQ = new Session_Query();
    $sessQ->connect();

    if ( !$sessQ->validToken($_SESSION['loginSession'], $_SESSION['token']) )
    {
      $sessQ->close();

      $_SESSION['invalidToken'] = true;
      header("Location: ../auth/login_form.php?ret=" . $_SESSION['returnPage']);
      exit();
    }
    $sessQ->close();

    if (isset($_SESSION['invalidToken']))
    {
      unset($_SESSION['invalidToken']);
    }

    /**
     * Checking authorization for this tab
     * The session authorization flags were set at login in login.php
     */
    if (isset($tab))
    {
      if ($tab == "medical")
      {
        if ( !$_SESSION['hasMedicalAuth'] && (isset($onlyDoctor) && !$onlyDoctor) )
        {
          FlashMsg::add(sprintf(_("You are not authorized to use %s tab."), _("Medical Records")));
          header("Location: ../home/index.php");
          exit();
        }
      }
      /*elseif ($tab == "stats")
      {
        if ( !$_SESSION['hasStatsAuth'] )
        {
          FlashMsg::add(sprintf(_("You are not authorized to use %s tab."), _("Stats")));
          header("Location: ../home/index.php");
          exit();
        }
      }*/
      elseif ($tab == "admin")
      {
        if ( !$_SESSION['hasAdminAuth'] )
        {
          FlashMsg::add(sprintf(_("You are not authorized to use %s tab."), _("Admin")));
          header("Location: ../home/index.php");
          exit();
        }
      }
    }

    if ( !$_SESSION['hasAdminAuth'] && !$_SESSION['hasMedicalAuth'] )
    {
      $hasMedicalAdminAuth = (isset($onlyDoctor) ? !($onlyDoctor) : true);
    }
    else
    {
      $hasMedicalAdminAuth = true;
    }
  }
  else
  {
    $hasMedicalAdminAuth = true;
  }
?>
