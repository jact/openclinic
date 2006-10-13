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
 * @version   CVS: $Id: no_authorization.php,v 1.9 2006/10/13 19:53:16 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "";

  require_once("../config/environment.php");

  /**
   * Show page
   */
  $title = _("Not Authorization");
  require_once("../layout/header.php");

  HTML::section(1, $title);

  HTML::message(sprintf(_("You are not authorized to use %s tab."), _("Medical Records")));

  require_once("../layout/footer.php");
?>
