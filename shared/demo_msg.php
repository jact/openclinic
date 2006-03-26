<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: demo_msg.php,v 1.8 2006/03/26 15:25:04 jact Exp $
 */

/**
 * demo_msg.php
 *
 * Screen with demo message
 *
 * @author jact <jachavar@gmail.com>
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
