<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: log_access_list.php,v 1.6 2004/07/07 17:21:52 jact Exp $
 */

/**
 * log_access_list.php
 ********************************************************************
 * List of user's accesses in a date
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to log statistics if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0)
  {
    header("Location: ../admin/log_stats.php?table=access");
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
  require_once("../classes/Access_Query.php");
  require_once("../lib/error_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $year = intval($_GET["year"]);
  $month = intval($_GET["month"]);
  $day = intval($_GET["day"]);
  $hour = intval($_GET["hour"]);

  $accessQ = new Access_Query();
  $accessQ->connect();
  if ($accessQ->isError())
  {
    showQueryError($accessQ);
  }

  $total = $accessQ->select($year, $month, $day, $hour);
  if ($accessQ->isError())
  {
    $accessQ->close();
    showQueryError($accessQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Access Logs");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Logs") => "../admin/log_stats.php?table=access",
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
    echo '<h3>' . _("Access Logs List:") . "</h3>\n";
    echo '<p><strong>' . sprintf(_("%d accesses."), $total) . "</strong></p>\n";
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
        <?php echo _("Profile"); ?>
      </th>
    </tr>
  </thead>

  <tbody>
<?php
    $profiles = array(
      OPEN_PROFILE_ADMINISTRATOR => _("Administrator"),
      OPEN_PROFILE_ADMINISTRATIVE => _("Administrative"),
      OPEN_PROFILE_DOCTOR => _("Doctor")
    );

    $rowClass = "odd";
    for ($i = 1; $access = $accessQ->fetch(); $i++)
    {
?>
    <tr class="<?php echo $rowClass; ?>">
      <td class="number">
        <?php echo $i . "."; ?>
      </td>

      <td>
        <?php echo localDate($access["access_date"]); ?>
      </td>

      <td class="center">
        <?php echo $access["login"]; ?>
      </td>

      <td class="center">
        <?php echo $profiles[$access["id_profile"]]; ?>
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
  $accessQ->freeResult();
  $accessQ->close();
  unset($accessQ);
  unset($access);
  unset($profiles);

  echo '<p><a href="' . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '../index.php') . '">';
  echo _("Back return") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
