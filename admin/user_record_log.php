<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_record_log.php,v 1.2 2004/06/01 17:55:44 jact Exp $
 */

/**
 * user_record_log.php
 ********************************************************************
 * Listado de operaciones realizadas por un usuario
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "users";
  $restrictInDemo = true; // There are not logs in demo version
  $returnLocation = "../admin/user_list.php";

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0)
  {
    header("Location: " . $returnLocation);
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idUser = intval($_GET["key"]);
  $login = $_GET["login"];

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Record_Query.php");
  require_once("../lib/error_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Search user operations
  ////////////////////////////////////////////////////////////////////
  $recordQ = new Record_Query();
  $recordQ->connect();
  if ($recordQ->errorOccurred())
  {
    $recordQ->close();
    showQueryError($recordQ);
  }

  $total = $recordQ->selectUser($idUser);
  if ($recordQ->errorOccurred())
  {
    $recordQ->close();
    showQueryError($recordQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Show success page
  ////////////////////////////////////////////////////////////////////
  $title = _("Record Logs");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Users") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "users.png");
  unset($links);

  echo '<h3>' . sprintf(_("Record Logs List for user %s"), $login) . "</h3>\n";

  if ($total == 0)
  {
    echo '<p>' . _("No logs for this user.") . "</p>\n";
  }
  else
  {
    echo '<p><strong>' . sprintf(_("%d transactions."), $total) . "</strong></p>\n";
?>
<table>
  <thead>
    <tr>
      <th>
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
    while ($row = $recordQ->fetchRecord())
    {
      echo '<tr class="' . $rowClass . ' center">';
      echo '<td>' . $row["access_date"] . "</td>\n";
      echo '<td>' . $row["login"] . "</td>\n";
      echo '<td>' . $row["table_name"] . "</td>\n";
      echo '<td>' . $row["operation"] . "</td>\n";
      echo '<td>' . $row["id_key1"] . "</td>\n";
      echo '<td>' . $row["id_key2"] . "</td>\n";
      echo "</tr>\n";
      // swap row color
      ($rowClass == "even") ? $rowClass = "odd" : $rowClass = "even";
    }
?>
  </tbody>
</table>
<?php
  } // end if-else
  $recordQ->freeResult();
  $recordQ->close();
  unset($recordQ);

  echo '<p><a href="' . $returnLocation . '">' . _("Return to users list") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
