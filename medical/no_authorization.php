<?php
/**
 * no_authorization.php
 *
 * No authorization screen for Medical Records tab
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: no_authorization.php,v 1.8 2006/09/30 17:11:28 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "";

  require_once("../shared/read_settings.php");

  /**
   * Show page
   */
  $title = _("Not Authorization");
  require_once("../shared/header.php");

  HTML::section(1, $title);

  HTML::message(sprintf(_("You are not authorized to use %s tab."), _("Medical Records")));

  require_once("../shared/footer.php");
?>
