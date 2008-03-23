<?php
/**
 * user_record_log.php
 *
 * List of record's logs for an user
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_record_log.php,v 1.38 2008/03/23 11:58:57 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "users";
  $returnLocation = "../admin/user_list.php";

  /**
   * Checking for get vars. Go back to user list if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["id_user"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR, false); // There are not logs in demo version

  require_once("../model/Query/Page/Record.php");
  require_once("../lib/Form.php");
  require_once("../lib/Search.php");
  require_once("../lib/Check.php");

  /**
   * Retrieving get vars
   */
  $idUser = intval($_GET["id_user"]);
  $login = Check::safeText($_GET["login"]);
  $currentPage = (isset($_GET["page"])) ? intval($_GET["page"]) : 1;

  /**
   * Search user operations
   */
  $recordQ = new Query_Page_Record();
  $recordQ->setItemsPerPage(OPEN_ITEMS_PER_PAGE);
  $recordQ->searchUser($idUser, $currentPage);

  if ($recordQ->getRowCount() == 0)
  {
    $recordQ->close();

    FlashMsg::add(sprintf(_("No logs for user %s."), $login));
    header("Location: " . $returnLocation);
    exit();
  }

  /**
   * Show page
   */
  $title = _("Record Logs");
  require_once("../layout/header.php");

  /**
   * Breadcrumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Users") => $returnLocation,
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_user");
  unset($links);

  echo HTML::section(2, sprintf(_("Record Logs List for user %s"), $login) . ":");

  // Printing result stats and page nav
  echo HTML::para(HTML::tag('strong', sprintf(_("%d transactions."), $recordQ->getRowCount())));

  $params = array(
    'id_user=' . $idUser,
    'login=' . $login
  );
  $params = implode('&', $params);

  $pageCount = $recordQ->getPageCount();
  $pageLinks = Search::pageLinks($currentPage, $pageCount, $_SERVER['PHP_SELF'] . '?' . $params);
  echo $pageLinks;

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

  echo HTML::table($thead, $tbody, null, $options);

  echo $pageLinks;

  unset($recordQ);
  unset($record);

  echo HTML::para(HTML::link(_("Return to users list"), $returnLocation));

  require_once("../layout/footer.php");
?>
