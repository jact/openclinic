<?php
/**
 * log_stats.php
 *
 * Log stats screen (access logins or record operations)
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: log_stats.php,v 1.10 2006/03/28 19:15:32 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.4
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "logs";
  $restrictInDemo = true; // There are not logs in demo version

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/log_lib.php");
  require_once("../lib/Check.php");

  /**
   * Retrieving get vars
   */
  if (count($_GET) == 0 || empty($_GET['table']))
  {
    $table = "access";
  }
  else
  {
    $table = Check::safeText($_GET['table']);
  }
  if ($table != "access" && $table != "record")
  {
    $table = "access";
  }
  $option = (isset($_GET["option"])) ? Check::safeText($_GET["option"]) : "";

  /**
   * Show page
   */
  $title = _("Logs");
  require_once("../shared/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon logIcon");
  unset($links);

  echo '<p>';
  if ($table != 'access')
  {
    HTML::link(_("Access Logs"), '../admin/log_stats.php', array('table' => 'access'));
  }
  else
  {
    echo _("Access Logs");
  }
  echo ' | ';
  if ($table != 'record')
  {
    HTML::link(_("Record Logs"), '../admin/log_stats.php', array('table' => 'record'));
  }
  else
  {
    echo _("Record Logs");
  }
  echo "</p>\n";

  echo "<hr />\n";

  switch ($option)
  {
    case "yearly":
      showMonthStats($table, intval($_GET['year']));
      showLinks($table);
      break;

    case "monthly":
      showDailyStats($table, intval($_GET['year']), intval($_GET['month']));
      showLinks($table);
      break;

    case "daily":
      showHourlyStats($table, intval($_GET['year']), intval($_GET['month']), intval($_GET['day']));
      showLinks($table);
      break;

    default:
      stats($table);
      break;
  }

  require_once("../shared/footer.php");
?>
