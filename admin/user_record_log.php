<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_record_log.php,v 1.7 2004/07/07 17:21:53 jact Exp $
 */

/**
 * user_record_log.php
 ********************************************************************
 * List of record's logs for an user
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
  require_once("../lib/input_lib.php");
  require_once("../lib/search_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving post vars and scrubbing the data
  ////////////////////////////////////////////////////////////////////
  if (isset($_POST["page"]))
  {
    $currentPageNmbr = $_POST["page"];
  }
  else
  {
    $currentPageNmbr = 1;
  }
  $limit = $_POST["limit"];

  ////////////////////////////////////////////////////////////////////
  // Search user operations
  ////////////////////////////////////////////////////////////////////
  $recordQ = new Record_Query();
  $recordQ->setItemsPerPage(OPEN_ITEMS_PER_PAGE);
  $recordQ->connect();
  if ($recordQ->isError())
  {
    $recordQ->close();
    showQueryError($recordQ);
  }

  $recordQ->searchUser($idUser, $currentPageNmbr, $limit);
  if ($recordQ->isError())
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

  echo '<h3>' . sprintf(_("Record Logs List for user %s"), $login) . ":</h3>\n";

  if ($recordQ->getRowCount() == 0)
  {
    echo '<p>' . _("No logs for this user.") . "</p>\n";
  }
  else
  {
    // Printing result stats and page nav
    echo '<p><strong>' . sprintf(_("%d transactions."), $recordQ->getRowCount()) . "</strong></p>\n";

    $pageCount = $recordQ->getPageCount();
    showResultPages($currentPageNmbr, $pageCount);
?>

<!-- JavaScript to post back to this page -->
<script type="text/javascript">
<!--/*--><![CDATA[/*<!--*/
function changePage(page)
{
  document.forms[0].page.value = page;
  document.forms[0].submit();

  return false;
}
/*]]>*///-->
</script>

<!-- Form used by javascript to post back to this page -->
<form method="post" action="../admin/user_record_log.php?key=<?php echo $idUser; ?>&amp;login=<?php echo $login; ?>">
  <div>
<?php
  showInputHidden("page", $currentPageNmbr);
  showInputHidden("limit", $_POST["limit"]);
?>
  </div>
</form>

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
    while ($row = $recordQ->fetch())
    {
      echo '<tr class="' . $rowClass . ' center">';
      echo '<td class="number">' . $recordQ->getCurrentRow() . ".</td>\n";
      echo '<td>' . localDate($row["access_date"]) . "</td>\n";
      echo '<td>' . $row["login"] . "</td>\n";
      echo '<td>' . $row["table_name"] . "</td>\n";
      echo '<td>' . $row["operation"] . "</td>\n";
      echo '<td>' . $row["id_key1"] . "</td>\n";
      echo '<td>' . $row["id_key2"] . "</td>\n";
      echo "</tr>\n";
      // swap row color
      ($rowClass == "even") ? $rowClass = "odd" : $rowClass = "even";
    }
    $recordQ->freeResult();
    $recordQ->close();
?>
  </tbody>
</table>
<?php
    showResultPages($currentPageNmbr, $pageCount);
  } // end if-else
  unset($recordQ);

  echo '<p><a href="' . $returnLocation . '">' . _("Return to users list") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
