<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: log_stats.php,v 1.6 2004/10/03 11:16:33 jact Exp $
 */

/**
 * log_stats.php
 ********************************************************************
 * Log stats screen (access logins or record operations)
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * @since 0.4
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
  require_once("../lib/validator_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || empty($_GET['table']))
  {
    $table = "access";
  }
  else
  {
    $table = safeText($_GET['table']);
  }
  if ($table != "access" && $table != "record")
  {
    $table = "access";
  }
  $option = (isset($_GET["option"])) ? safeText($_GET["option"]) : "";

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
  if ($table != 'access')
  {
    echo '<a href="../admin/log_stats.php?table=access">';
  }
  echo _("Access Logs");
  if ($table != 'access')
  {
    echo '</a>';
  }
  echo ' | ';
  if ($table != 'record')
  {
    echo '<a href="../admin/log_stats.php?table=record">';
  }
  echo _("Record Logs");
  if ($table != 'record')
  {
    echo '</a>';
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
