<?php
/**
 * log_record_list.php
 *
 * List of record's logs in a date
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: log_record_list.php,v 1.22 2006/10/13 19:49:46 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.4
 */

  /**
   * Checking for get vars. Go back to log statistics if none found.
   */
  if (count($_GET) == 0)
  {
    header("Location: ../admin/log_stats.php?table=record");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "logs";
  $restrictInDemo = true; // There are not logs in demo version

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Record_Page_Query.php");

  /**
   * Retrieving get vars
   */
  $year = (isset($_GET["year"])) ? intval($_GET["year"]) : 0;
  $month = (isset($_GET["month"])) ? intval($_GET["month"]) : 0;
  $day = (isset($_GET["day"])) ? intval($_GET["day"]) : 0;
  $hour = (isset($_GET["hour"])) ? intval($_GET["hour"]) : 0;

  /**
   * Show page
   */
  $title = _("Record Logs");
  require_once("../layout/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Logs") => "../admin/log_stats.php?table=record",
    $title => ""
  );
  HTML::breadCrumb($links, "icon logIcon");
  unset($links);

  $recordQ = new Record_Page_Query();
  $recordQ->connect();

  $total = $recordQ->select($year, $month, $day, $hour);
  if ($total == 0)
  {
    $recordQ->close();
    HTML::message(_("No logs in this date."), OPEN_MSG_INFO);
    include_once("../layout/footer.php");
    exit();
  }

  HTML::section(3, _("Record Logs List:"));
  HTML::para(HTML::strTag('strong', sprintf(_("%d transactions."), $total)));

  $thead = array(
    _("Access Date") => array('colspan' => 2),
    _("Login"),
    _("Table"),
    _("Operation"),
    _("Data")
  );

  $options = array(
    0 => array('align' => 'right'),
    1 => array('align' => 'center'),
    2 => array('align' => 'center'),
    3 => array('align' => 'center'),
    4 => array('align' => 'center')
  );

  $tbody = array();
  for ($i = 1; $record = $recordQ->fetch(); $i++)
  {
    $row = $i . '.';
    $row .= OPEN_SEPARATOR;
    $row .= I18n::localDate($record["access_date"]);
    $row .= OPEN_SEPARATOR;
    $row .= $record["login"];
    $row .= OPEN_SEPARATOR;
    $row .= $record["table_name"];
    $row .= OPEN_SEPARATOR;
    $row .= $record["operation"];
    $row .= OPEN_SEPARATOR;
    $row .= htmlspecialchars(var_export(unserialize($record["affected_row"]), true));

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end for

  $recordQ->freeResult();
  $recordQ->close();
  unset($recordQ);
  unset($record);

  HTML::table($thead, $tbody, null, $options);

  HTML::para(
    HTML::strLink(_("Back return"), (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '../index.php'))
  );

  require_once("../layout/footer.php");
?>
