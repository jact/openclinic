<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: login_suspended.php,v 1.4 2004/07/26 18:45:47 jact Exp $
 */

/**
 * login_suspended.php
 ********************************************************************
 * Screen with user login suspended message
 ********************************************************************
 * Author: jact <jachavar@terra.es>
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

  showMessage(_("Your user account has been suspended."));
  showMessage(_("Contact with administrator to resolve this problem."), OPEN_MSG_INFO);

  //debug($_SESSION);
  //debug($user);

  ////////////////////////////////////////////////////////////////////
  // Destroy session values
  ////////////////////////////////////////////////////////////////////
  $_SESSION = array(); // deregister all current session variables
  session_destroy(); // clean up session ID

  require_once("../shared/footer.php");
?>
