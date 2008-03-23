<?php
/**
 * log_stats.php
 *
 * Log stats screen (access logins or record operations)
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: log_stats.php,v 1.19 2008/03/23 11:58:56 jact Exp $
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
   * Breadcrumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_log");
  unset($links);

  echo HTML::section(2, HTML::link(_("Access Logs"), '../admin/log_list.php', array('table' => 'access')),
    array('class' => 'icon icon_log')
  );

  require_once("../lib/LogStats.php");
  LogStats::summary('access');

  echo HTML::rule();

  echo HTML::section(2, HTML::link(_("Record Logs"), '../admin/log_list.php', array('table' => 'record')),
    array('class' => 'icon icon_log')
  );
  LogStats::summary('record');

  require_once("../layout/footer.php");
?>
