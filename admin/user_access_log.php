<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_access_log.php,v 1.17 2005/07/21 16:55:57 jact Exp $
 */

/**
 * user_access_log.php
 *
 * List of user's accesses
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
  require_once("../classes/Access_Page_Query.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/search_lib.php");
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
  // Search user accesses
  ////////////////////////////////////////////////////////////////////
  $accessQ = new Access_Page_Query();
  $accessQ->setItemsPerPage(OPEN_ITEMS_PER_PAGE);
  $accessQ->connect();
  if ($accessQ->isError())
  {
    Error::query($accessQ);
  }

  $accessQ->searchUser($idUser, $currentPageNmbr, $limit);
  if ($accessQ->isError())
  {
    $accessQ->close();
    Error::query($accessQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Show success page
  ////////////////////////////////////////////////////////////////////
  $title = _("Access Logs");
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

  echo '<h3>' . sprintf(_("Access Logs List for user %s"), $login) . ":</h3>\n";

  if ($accessQ->getRowCount() == 0)
  {
    $accessQ->close();
    HTML::message(_("No logs for this user."), OPEN_MSG_INFO);
  }
  else
  {
    // Printing result stats and page nav
    echo '<p><strong>' . sprintf(_("%d accesses."), $accessQ->getRowCount()) . "</strong></p>\n";

    $pageCount = $accessQ->getPageCount();
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
<form method="post" action="../admin/user_access_log.php?key=<?php echo $idUser; ?>&amp;login=<?php echo $login; ?>">
  <div>
<?php
  showInputHidden("page", $currentPageNmbr);
  showInputHidden("limit", $limit);
?>
  </div>
</form>

<?php
    $profiles = array(
      OPEN_PROFILE_ADMINISTRATOR => _("Administrator"),
      OPEN_PROFILE_ADMINISTRATIVE => _("Administrative"),
      OPEN_PROFILE_DOCTOR => _("Doctor")
    );

    $thead = array(
      _("Access Date") => array('colspan' => 2),
      _("Login"),
      _("Profile")
    );

    $options = array(
      0 => array('align' => 'right'),
      2 => array('align' => 'center'),
      3 => array('align' => 'center')
    );

    $tbody = array();
    while ($access = $accessQ->fetch())
    {
      $row = $accessQ->getCurrentRow() . ".";
      $row .= OPEN_SEPARATOR;
      $row .= I18n::localDate($access["access_date"]);
      $row .= OPEN_SEPARATOR;
      $row .= $access["login"];
      $row .= OPEN_SEPARATOR;
      $row .= $profiles[$access["id_profile"]];

      $tbody[] = explode(OPEN_SEPARATOR, $row);
    }
    $accessQ->freeResult();
    $accessQ->close();

    HTML::table($thead, $tbody, null, $options);

    showResultPages($currentPageNmbr, $pageCount);
  } // end if-else
  unset($accessQ);
  unset($access);
  unset($profiles);

  echo '<p><a href="' . $returnLocation . '">' . _("Return to users list") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
