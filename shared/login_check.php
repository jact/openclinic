<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: login_check.php,v 1.6 2004/08/09 14:21:42 jact Exp $
 */

/**
 * login_check.php
 ********************************************************************
 * Used to verify sign on token on every secured page.
 * Redirects to the login page if token not valid.
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Checking to see if we are in demo mode and if we should not execute this page.
  ////////////////////////////////////////////////////////////////////
  if (isset($restrictInDemo) && $restrictInDemo && OPEN_DEMO)
  {
    include_once("../shared/demo_msg.php");
    exit();
  }

  require_once("../classes/Session_Query.php");
  require_once("../lib/error_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Disabling users control for demo
  ////////////////////////////////////////////////////////////////////
  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    //works in PHP >= 4.1
    $_SESSION['returnPage'] = urlencode($_SERVER['REQUEST_URI']);

    ////////////////////////////////////////////////////////////////////
    // Checking to see if session variables exist
    ////////////////////////////////////////////////////////////////////
    if ( !isset($_SESSION['loginSession']) || ($_SESSION['loginSession'] == "") )
    {
      header("Location: ../shared/login_form.php");
      exit();
    }

    if ( !isset($_SESSION['token']) || $_SESSION['token'] == "" )
    {
      header("Location: ../shared/login_form.php");
      exit();
    }

    ////////////////////////////////////////////////////////////////////
    // Checking if the request is from a different IP to previously
    ////////////////////////////////////////////////////////////////////
    if (isset($_SESSION['loginIP']) && $_SESSION['loginIP'] != $_SERVER['REMOTE_ADDR'])
    {
      // This is possibly a session hijack attempt
      //$_SESSION = array(); // deregister all current session variables
      //session_destroy(); // clean up session ID

      //header("Location: ../shared/login_form.php");
      include_once("../shared/logout.php");
      exit();
    }

    ////////////////////////////////////////////////////////////////////
    // Checking session table to see if token has timed out
    ////////////////////////////////////////////////////////////////////
    $sessQ = new Session_Query();
    $sessQ->connect();
    if ($sessQ->isError())
    {
      showQueryError($sessQ);
    }

    if ( !$sessQ->validToken($_SESSION['loginSession'], $_SESSION['token']) )
    {
      if ($sessQ->isError())
      {
        $sessQ->close();
        showQueryError($sessQ);
      }
      $sessQ->close();

      $_SESSION['invalidToken'] = true;
      header("Location: ../shared/login_form.php?ret=" . $_SESSION['returnPage']);
      exit();
    }
    $sessQ->close();

    if (isset($_SESSION['invalidToken']))
    {
      unset($_SESSION['invalidToken']);
    }

    ////////////////////////////////////////////////////////////////////
    // Checking authorization for this tab
    // The session authorization flags were set at login in login.php
    ////////////////////////////////////////////////////////////////////
    if (isset($tab))
    {
      if ($tab == "medical")
      {
        if ( !$_SESSION['hasMedicalAuth'] && (isset($onlyDoctor) && !$onlyDoctor) )
        {
          header("Location: ../medical/no_authorization.php");
          exit();
        }
      }
      /*elseif ($tab == "stats")
      {
        if ( !$_SESSION['hasStatsAuth'] )
        {
          header("Location: ../stats/no_authorization.php");
          exit();
        }
      }*/
      elseif ($tab == "admin")
      {
        if ( !$_SESSION['hasAdminAuth'] )
        {
          header("Location: ../admin/no_authorization.php");
          exit();
        }
      }
    }

    //if ( !$_SESSION['hasAdminAuth'] )
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
