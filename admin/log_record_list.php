<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: log_record_list.php,v 1.6 2004/06/16 19:33:19 jact Exp $
 */

/**
 * log_record_list.php
 ********************************************************************
 * List of record's logs in a date
 ********************************************************************
 * Author: jact <jachavar@terra.es>
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
  $year = intval($_GET["year"]);
  $month = intval($_GET["month"]);
  $day = intval($_GET["day"]);
  $hour = intval($_GET["hour"]);

  $recordQ = new Record_Query();
  $recordQ->connect();
  if ($recordQ->errorOccurred())
  {
    showQueryError($recordQ);
  }

  $total = $recordQ->select($year, $month, $day, $hour);
  if ($recordQ->errorOccurred())
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
    echo '<p>' . _("No logs in this date.") . "</p>\n";
  }
  else
  {
    echo '<h3>' . _("Record Logs List:") . "</h3>\n";
    echo '<p><strong>' . sprintf(_("%d transactions."), $total) . "</strong></p>\n";
?>

<table>
  <thead>
    <tr>
      <th colspan="2">
        <?php echo _("Access Date"); ?>
      </th>

      <th>
        <?php echo _("Login"); ?>
      </th>

      <th>
        <?php echo _("Table"); ?>
      </th>

      <th>
        <?php echo _("Operation"); ?>
      </th>

      <th>
        <?php echo sprintf(_("Key %d"), 1); ?>
      </th>

      <th>
        <?php echo sprintf(_("Key %d"), 2); ?>
      </th>
    </tr>
  </thead>

  <tbody>
<?php
    $rowClass = "odd";
    for ($i = 1; $record = $recordQ->fetch(); $i++)
    {
?>
    <tr class="<?php echo $rowClass; ?> center">
      <td class="number">
        <?php echo $i . "."; ?>
      </td>

      <td>
        <?php echo localDate($record["access_date"]); ?>
      </td>

      <td>
        <?php echo $record["login"]; ?>
      </td>

      <td>
        <?php echo $record["table_name"]; ?>
      </td>

      <td>
        <?php echo $record["operation"]; ?>
      </td>

      <td>
        <?php echo $record["id_key1"]; ?>
      </td>

      <td>
        <?php echo (($record["id_key2"]) ? $record["id_key2"] : ""); ?>
      </td>
    </tr>
<?php
      // swap row color
      ($rowClass == "odd") ? $rowClass = "even" : $rowClass = "odd";
    } // end for
?>
  </tbody>
</table>

<?php
  } // end if-else
  $recordQ->freeResult();
  $recordQ->close();
  unset($recordQ);
  unset($record);

  echo '<p><a href="' . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '../index.php') . '">';
  echo _("Back return") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
