<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_access_log.php,v 1.1 2004/05/31 19:45:39 jact Exp $
 */

/**
 * user_access_log.php
 ********************************************************************
 * List of user's accesses
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  $locationError = "../admin/user_list.php";

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0)
  {
    header("Location: " . $locationError);
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "users";
  $restrictInDemo = true; // There are not logs in demo version

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idUser = intval($_GET["key"]);
  $login = $_GET["login"];

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Access_Query.php");
  require_once("../lib/error_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Search user accesses
  ////////////////////////////////////////////////////////////////////
  $accessQ = new Access_Query();
  $accessQ->connect();
  if ($accessQ->errorOccurred())
  {
    $accessQ->close();
    showQueryError($accessQ);
  }

  $total = $accessQ->selectUser($idUser);
  if ($accessQ->errorOccurred())
  {
    $accessQ->close();
    showQueryError($accessQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Show success page
  ////////////////////////////////////////////////////////////////////
  $title = _("Access Logs");
  require_once("../shared/header.php");

  $returnLocation = "../admin/user_list.php";

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

  echo '<h3>' . sprintf(_("Access Logs List for user %s"), $login) . "</h3>\n";

  if ($total == 0)
  {
    echo '<p>' . _("No logs for this user.") . "</p>\n";
  }
  else
  {
    echo '<p><strong>' . sprintf(_("%d accesses."), $total) . "</strong></p>\n";
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
        <?php echo _("Profile"); ?>
      </th>
    </tr>
  </thead>

  <tbody>
<?php
    $rowClass = "odd";
    while ($access = $accessQ->fetchAccess())
    {
      echo '<tr class="' . $rowClass . '">';
      echo '<td>' . $access["access_date"] . "</td>\n";
      echo '<td class="center">' . $access["login"] . "</td>\n";
      echo '<td class="center">' . $access["profile"] . "</td>\n";
      echo "</tr>\n";
      // swap row color
      ($rowClass == "even") ? $rowClass = "odd" : $rowClass = "even";
    }
?>
  </tbody>
</table>
<?php
  } // end if-else
  $accessQ->freeResult();
  $accessQ->close();
  unset($accessQ);

  echo '<p><a href="' . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '../index.php') . '">';
  echo _("Back return") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
