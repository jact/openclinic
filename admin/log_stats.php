<?php
/**
 * log_stats.php
 *
 * Log stats screen (access logins or record operations)
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: log_stats.php,v 1.18 2007/12/07 16:50:50 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.4
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "logs";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR, false); // There are not logs in demo version

  /**
   * Show page
   */
  $title = _("Log Statistics");
  require_once("../layout/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon icon_log");
  unset($links);

  HTML::section(2, HTML::strLink(_("Access Logs"), '../admin/log_list.php', array('table' => 'access')),
    array('class' => 'icon icon_log')
  );

  require_once("../lib/LogStats.php");
  LogStats::summary('access');

  HTML::rule();

  HTML::section(2, HTML::strLink(_("Record Logs"), '../admin/log_list.php', array('table' => 'record')),
    array('class' => 'icon icon_log')
  );
  LogStats::summary('record');

  require_once("../layout/footer.php");
?>
