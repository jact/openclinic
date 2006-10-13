<?php
/**
 * login_suspended.php
 *
 * Screen with user login suspended message
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: login_suspended.php,v 1.1 2006/10/13 19:55:56 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controllign vars
   */
  $tab = "home";
  $nav = "";

  require_once("../config/environment.php");

  /**
   * Show page
   */
  $title = _("User account suspended");
  require_once("../layout/header.php");

  HTML::section(1, $title);

  HTML::message(_("Your user account has been suspended."));
  HTML::message(_("Contact with administrator to resolve this problem."), OPEN_MSG_INFO);

  //Error::debug($_SESSION);
  //Error::debug($user);

  /**
   * Destroy session values
   */
  $_SESSION = array(); // deregister all current session variables
  session_destroy(); // clean up session ID

  require_once("../layout/footer.php");
?>
