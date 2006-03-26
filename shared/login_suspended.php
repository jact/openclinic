<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: login_suspended.php,v 1.7 2006/03/26 15:25:04 jact Exp $
 */

/**
 * login_suspended.php
 *
 * Screen with user login suspended message
 *
 * @author jact <jachavar@gmail.com>
 */

  /**
   * Controllign vars
   */
  $tab = "home";
  $nav = "";

  require_once("../shared/read_settings.php");

  /**
   * Show page
   */
  $title = _("User account suspended");
  require_once("../shared/header.php");

  echo '<h1>' . $title . "</h1>\n";

  HTML::message(_("Your user account has been suspended."));
  HTML::message(_("Contact with administrator to resolve this problem."), OPEN_MSG_INFO);

  //Error::debug($_SESSION);
  //Error::debug($user);

  /**
   * Destroy session values
   */
  $_SESSION = array(); // deregister all current session variables
  session_destroy(); // clean up session ID

  require_once("../shared/footer.php");
?>
