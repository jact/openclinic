<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: login_suspended.php,v 1.1 2004/02/15 12:39:50 jact Exp $
 */

/**
 * login_suspended.php
 ********************************************************************
 * Screen with user login suspended message
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 15/02/04 13:39
 */

  $tab = "home";
  $nav = "";

  require_once("../shared/read_settings.php");

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("User account suspended");
  require_once("../shared/header.php");

  echo '<h1>' . $title . "</h1>\n";
  echo '<p>' . _("Your user account has been suspended. Contact with administrator to resolve this problem.") . "</p>\n";
  debug($user);

  ////////////////////////////////////////////////////////////////////
  // Destroy session values
  ////////////////////////////////////////////////////////////////////
  $_SESSION = array(); // works in PHP >= 4.0.6
  //$HTTP_SESSION_VARS = array(); // works in PHP < 4.0.6
  session_destroy();

  require_once("../shared/footer.php");
  exit();
?>
