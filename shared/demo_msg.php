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
 * @version   CVS: $Id: demo_msg.php,v 1.10 2006/09/30 17:25:50 jact Exp $
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

  HTML::section(1, $title);

  HTML::message(_("This function is not available in this demo version of OpenClinic."), OPEN_MSG_INFO);

  require_once("../shared/footer.php");
?>
