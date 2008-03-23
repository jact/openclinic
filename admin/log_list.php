<?php
/**
 * log_list.php
 *
 * List of user's accesses or record's logs in a date
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: log_list.php,v 1.5 2008/03/23 11:58:56 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.8
 * @todo      resultset pagination
 */

  /**
   * Checking for get vars. Go back to log statistics if none found.
   */
  if (count($_GET) == 0)
  {
    header("Location: ../admin/log_stats.php");
    exit();
  }

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

  require_once("../lib/LogStats.php");

  /**
   * Retrieving get vars
   */
  $table = isset($_GET['table']) ? Check::safeText($_GET['table']) : 'access';
  $year = (isset($_GET["year"])) ? intval($_GET["year"]) : 0;
  $month = (isset($_GET["month"])) ? intval($_GET["month"]) : 0;
  $day = (isset($_GET["day"])) ? intval($_GET["day"]) : 0;
  //$hour = (isset($_GET["hour"])) ? intval($_GET["hour"]) : 0; // @todo ?

  /**
   * Show page
   */
  if ($table == 'record')
  {
    $title = _("Record Logs");
  }
  else
  {
    $title = _("Access Logs");
  }
  $titlePage = $tempTitle = $title;

  $links = array(
    _("Admin") => "../admin/index.php",
    _("Log Statistics") => "../admin/log_stats.php",
    $title => "../admin/log_list.php?table=" . $table
  );
  if ($year)
  {
    $title = sprintf(_("Year %d"), $year);
    $titlePage = $tempTitle . ' (' . $title . ')';
    $links[$title] = "../admin/log_list.php?table=" . $table . "&year=" . $year;
  }
  if ($month)
  {
    $title = LogStats::getMonthName($month);
    $titlePage = $tempTitle . ' (' . $title . ' ' . $year . ')';
    $links[$title] = "../admin/log_list.php?table=" . $table . "&year=" . $year . "&month=" . $month;
  }
  if ($day)
  {
    $title = sprintf(_("Day %d"), $day);
    $titlePage = $tempTitle . ' (' . sprintf('%d-%02d-%02d', $year, $month, $day) . ')';
    $links[$title] = "";
  }
  require_once("../layout/header.php");

  /**
   * Breadcrumb
   */
  echo HTML::breadcrumb($links, "icon icon_log");
  unset($links);

  if ($day)
  {
    LogStats::hourly($table, $year, $month, $day);
  }
  elseif ($month)
  {
    LogStats::daily($table, $year, $month);
  }
  elseif ($year)
  {
    LogStats::monthly($table, $year);
  }
  else
  {
    LogStats::yearly($table);
  }

  if ($table == 'record')
  {
    include_once("../model/Query/Page/Record.php");
    $logQ = new Query_Page_Record();
  }
  else
  {
    include_once("../model/Query/Page/Access.php");
    $logQ = new Query_Page_Access();

    $profiles = array(
      OPEN_PROFILE_ADMINISTRATOR => _("Administrator"),
      OPEN_PROFILE_ADMINISTRATIVE => _("Administrative"),
      OPEN_PROFILE_DOCTOR => _("Doctor")
    );
  }

  if ( !$logQ->select($year, $month, $day/*, $hour*/) )
  {
    $logQ->close();

    echo Msg::info(_("No logs in this date."));
    include_once("../layout/footer.php");
    exit();
  }

  $thead = array(
    _("#"),
    _("Access Date"),
    _("Login")
  );
  if ($table == 'record')
  {
    $thead[] = _("Table");
    $thead[] =_("Operation");
    $thead[] = _("Data");
  }
  else
  {
    $thead[] = _("Profile");
  }

  $options = array(
    'align' => 'center',
    0 => array('align' => 'right')
  );

  $tbody = array();
  for ($i = 1; $log = $logQ->fetch(); $i++)
  {
    $row = $i . '.';
    $row .= OPEN_SEPARATOR;

    $row .= I18n::localDate($log["access_date"]);
    $row .= OPEN_SEPARATOR;

    $row .= $log["login"];
    $row .= OPEN_SEPARATOR;

    if ($table == 'record')
    {
      $row .= $log["table_name"];
      $row .= OPEN_SEPARATOR;

      $row .= $log["operation"];
      $row .= OPEN_SEPARATOR;

      $row .= htmlspecialchars(var_export(unserialize($log["affected_row"]), true));
    }
    else
    {
      $row .= $profiles[$log["id_profile"]];
    }

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end for

  $logQ->freeResult();
  $logQ->close();
  unset($logQ);
  unset($log);

  echo HTML::rule();
  echo HTML::table($thead, $tbody, null, $options);

  require_once("../layout/footer.php");
?>
