<?php
/**
 * demo_msg.php
 *
 * Screen with demo message
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: demo_msg.php,v 1.11 2006/10/13 19:48:17 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once("../config/environment.php");

  /**
   * Controlling vars
   */
  $tab = "home";
  $nav = "";

  /**
   * Show page
   */
  $title = _("This is a demo version");
  require_once("../layout/header.php");

  HTML::section(1, $title);

  HTML::message(_("This function is not available in this demo version of OpenClinic."), OPEN_MSG_INFO);

  require_once("../layout/footer.php");
?>
