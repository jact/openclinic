<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_record_log.php,v 1.10 2004/07/28 18:09:48 jact Exp $
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
  $currentPageNmbr = (isset($_POST["page"])) ? $_POST["page"] : 1;
  $limit = (isset($_POST["limit"])) ? $_POST["limit"] : 0;

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
    $recordQ->close();
    showMessage(_("No logs for this user."), OPEN_MSG_INFO);
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
  showInputHidden("limit", $limit);
?>
  </div>
</form>

<?php
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
      4 => array('align' => 'center'),
      5 => array('align' => 'center')
    );

    $tbody = array();
    while ($record = $recordQ->fetch())
    {
      $row = $recordQ->getCurrentRow() . ".";
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
    }
    $recordQ->freeResult();
    $recordQ->close();

    showTable($thead, $tbody, null, $options);

    showResultPages($currentPageNmbr, $pageCount);
  } // end if-else
  unset($recordQ);

  echo '<p><a href="' . $returnLocation . '">' . _("Return to users list") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
