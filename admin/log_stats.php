<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: log_stats.php,v 1.3 2004/07/14 18:24:33 jact Exp $
 */

/**
 * log_stats.php
 ********************************************************************
 * Log stats screen (access logins or record operations)
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "logs";
  $restrictInDemo = true; // There are not logs in demo version

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/log_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || empty($_GET['table']))
  {
    $table = "access";
  }
  else
  {
    $table = $_GET['table'];
  }
  $option = (isset($_GET["option"])) ? $_GET["option"] : "";

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Logs");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  showNavLinks($links, "logs.png");
  unset($links);

  echo '<p>';
  if (isset($_GET["table"]) && $_GET["table"] != 'access')
  {
    echo '<a href="../admin/log_stats.php?table=access">';
  }
  echo _("Access Logs");
  if (isset($_GET["table"]) && $_GET["table"] != 'access')
  {
    echo '</a>';
  }
  echo ' | ';
  if ( !isset($_GET["table"]) || $_GET["table"] != 'record' )
  {
    echo '<a href="../admin/log_stats.php?table=record">';
  }
  echo _("Record Logs");
  if ( !isset($_GET["table"]) || $_GET["table"] != 'record' )
  {
    echo '</a>';
  }
  echo "</p>\n";

  switch ($option)
  {
    case "yearly":
      showMonthStats($table, $_GET['year']);
      showLinks($table);
      break;

    case "monthly":
      showDailyStats($table, $_GET['year'], $_GET['month']);
      showLinks($table);
      break;

    case "daily":
      showHourlyStats($table, $_GET['year'], $_GET['month'], $_GET['day']);
      showLinks($table);
      break;

    default:
      stats($table);
      break;
  }

  require_once("../shared/footer.php");
?>
