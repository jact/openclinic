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
 * @version   CVS: $Id: log_stats.php,v 1.13 2007/01/15 22:33:47 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.4
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "logs";
  $restrictInDemo = true; // There are not logs in demo version

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
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
  require_once("../layout/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon logIcon");
  unset($links);

  $relatedLinks = "";
  if ($table != 'access')
  {
    $relatedLinks .= HTML::strLink(_("Access Logs"), '../admin/log_stats.php', array('table' => 'access'));
  }
  else
  {
    $relatedLinks .= _("Access Logs");
  }
  $relatedLinks .= ' | ';
  if ($table != 'record')
  {
    $relatedLinks .= HTML::strLink(_("Record Logs"), '../admin/log_stats.php', array('table' => 'record'));
  }
  else
  {
    $relatedLinks .= _("Record Logs");
  }
  HTML::para($relatedLinks);

  HTML::rule();

  switch ($option)
  {
    case "monthly":
      showMonthlyStats($table, intval($_GET['year']));
      showLinks($table);
      break;

    case "daily":
      showDailyStats($table, intval($_GET['year']), intval($_GET['month']));
      showLinks($table);
      break;

    case "hourly":
      showHourlyStats($table, intval($_GET['year']), intval($_GET['month']), intval($_GET['day']));
      showLinks($table);
      break;

    default:
      stats($table);
      break;
  }

  require_once("../layout/footer.php");
?>
