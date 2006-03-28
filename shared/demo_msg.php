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
 * @version   CVS: $Id: demo_msg.php,v 1.9 2006/03/28 19:20:42 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once("../shared/read_settings.php");

  /**
   * Controlling vars
   */
  $tab = "home";
  $nav = "";

  /**
   * Show page
   */
  $title = _("This is a demo version");
  require_once("../shared/header.php");

  echo '<h1>' . $title . "</h1>\n";

  HTML::message(_("This function is not available in this demo version of OpenClinic."), OPEN_MSG_INFO);

  require_once("../shared/footer.php");
?>
