<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: log_record_list.php,v 1.11 2004/09/22 18:18:40 jact Exp $
 */

/**
 * log_record_list.php
 ********************************************************************
 * List of record's logs in a date
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * @since 0.4
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to log statistics if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0)
  {
    header("Location: ../admin/log_stats.php?table=record");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "logs";
  $restrictInDemo = true; // There are not logs in demo version

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Record_Query.php");
  require_once("../lib/error_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $year = (isset($_GET["year"])) ? intval($_GET["year"]) : 0;
  $month = (isset($_GET["month"])) ? intval($_GET["month"]) : 0;
  $day = (isset($_GET["day"])) ? intval($_GET["day"]) : 0;
  $hour = (isset($_GET["hour"])) ? intval($_GET["hour"]) : 0;

  $recordQ = new Record_Query();
  $recordQ->connect();
  if ($recordQ->isError())
  {
    showQueryError($recordQ);
  }

  $total = $recordQ->select($year, $month, $day, $hour);
  if ($recordQ->isError())
  {
    $recordQ->close();
    showQueryError($recordQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Record Logs");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Logs") => "../admin/log_stats.php?table=record",
    $title => ""
  );
  showNavLinks($links, "logs.png");
  unset($links);

  if ($total == 0)
  {
    $recordQ->close();
    showMessage(_("No logs in this date."), OPEN_MSG_INFO);
    include_once("../shared/footer.php");
    exit();
  }

  echo '<h3>' . _("Record Logs List:") . "</h3>\n";
  echo '<p><strong>' . sprintf(_("%d transactions."), $total) . "</strong></p>\n";

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
    $row .= localDate($record["access_date"]);
    $row .= OPEN_SEPARATOR;
    $row .= $record["login"];
    $row .= OPEN_SEPARATOR;
    $row .= $record["table_name"];
    $row .= OPEN_SEPARATOR;
    $row .= $record["operation"];
    $row .= OPEN_SEPARATOR;
    $row .= var_export(unserialize($record["affected_row"]), true);

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end for

  $recordQ->freeResult();
  $recordQ->close();
  unset($recordQ);
  unset($record);

  showTable($thead, $tbody, null, $options);

  echo '<p><a href="' . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '../index.php') . '">';
  echo _("Back return") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
