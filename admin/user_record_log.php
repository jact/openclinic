<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_record_log.php,v 1.21 2005/07/30 15:14:53 jact Exp $
 */

/**
 * user_record_log.php
 *
 * List of record's logs for an user
 *
 * Author: jact <jachavar@gmail.com>
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

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Record_Page_Query.php");
  require_once("../lib/Form.php");
  require_once("../lib/Search.php");
  require_once("../lib/Check.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idUser = intval($_GET["key"]);
  $login = Check::safeText($_GET["login"]);

  ////////////////////////////////////////////////////////////////////
  // Retrieving post vars and scrubbing the data
  ////////////////////////////////////////////////////////////////////
  $currentPageNmbr = (isset($_POST["page"])) ? intval($_POST["page"]) : 1;
  $limit = (isset($_POST["limit"])) ? intval($_POST["limit"]) : 0;

  ////////////////////////////////////////////////////////////////////
  // Search user operations
  ////////////////////////////////////////////////////////////////////
  $recordQ = new Record_Page_Query();
  $recordQ->setItemsPerPage(OPEN_ITEMS_PER_PAGE);
  $recordQ->connect();
  if ($recordQ->isError())
  {
    Error::query($recordQ);
  }

  $recordQ->searchUser($idUser, $currentPageNmbr, $limit);
  if ($recordQ->isError())
  {
    $recordQ->close();
    Error::query($recordQ);
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
    HTML::message(_("No logs for this user."), OPEN_MSG_INFO);
  }
  else
  {
    // Printing result stats and page nav
    echo '<p><strong>' . sprintf(_("%d transactions."), $recordQ->getRowCount()) . "</strong></p>\n";

    $pageCount = $recordQ->getPageCount();
    Search::pageLinks($currentPageNmbr, $pageCount);
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
  Form::hidden("page", "page", $currentPageNmbr);
  Form::hidden("limit", "limit", $limit);
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
      1 => array('align' => 'center', 'nowrap' => 1),
      2 => array('align' => 'center'),
      3 => array('align' => 'center'),
      4 => array('align' => 'center')
    );

    $tbody = array();
    while ($record = $recordQ->fetch())
    {
      $row = $recordQ->getCurrentRow() . ".";
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
    }
    $recordQ->freeResult();
    $recordQ->close();

    HTML::table($thead, $tbody, null, $options);

    Search::pageLinks($currentPageNmbr, $pageCount);
  } // end if-else
  unset($recordQ);

  echo '<p><a href="' . $returnLocation . '">' . _("Return to users list") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
