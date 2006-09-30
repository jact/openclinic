<?php
/**
 * user_record_log.php
 *
 * List of record's logs for an user
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_record_log.php,v 1.29 2006/09/30 16:51:00 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "users";
  $restrictInDemo = true; // There are not logs in demo version
  $returnLocation = "../admin/user_list.php";

  /**
   * Checking for get vars. Go back to user list if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]))
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

  /**
   * Retrieving get vars
   */
  $idUser = intval($_GET["key"]);
  $login = Check::safeText($_GET["login"]);

  /**
   * Retrieving post vars and scrubbing the data
   */
  $currentPage = (isset($_POST["page"])) ? intval($_POST["page"]) : 1;
  $limit = (isset($_POST["limit"])) ? intval($_POST["limit"]) : 0;

  /**
   * Search user operations
   */
  $recordQ = new Record_Page_Query();
  $recordQ->setItemsPerPage(OPEN_ITEMS_PER_PAGE);
  $recordQ->connect();

  $recordQ->searchUser($idUser, $currentPage, $limit);

  /**
   * Show page
   */
  $title = _("Record Logs");
  require_once("../shared/header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Users") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon userIcon");
  unset($links);

  HTML::section(2, sprintf(_("Record Logs List for user %s"), $login) . ":");

  if ($recordQ->getRowCount() == 0)
  {
    $recordQ->close();
    HTML::message(_("No logs for this user."), OPEN_MSG_INFO);
  }
  else
  {
    // Printing result stats and page nav
    HTML::para(HTML::strTag('strong', sprintf(_("%d transactions."), $recordQ->getRowCount())));

    $pageCount = $recordQ->getPageCount();
    Search::pageLinks($currentPage, $pageCount);

    Search::changePageJS();

    /**
     * Form used by javascript to post back to this page (id="changePage" important)
     */
    HTML::start('form',
      array(
        'id' => 'changePage',
        'method' => 'post',
        'action' => '../admin/user_record_log.php?key=' . $idUser . '&login=' . urlencode($login)
      )
    );
    Form::hidden("page", $currentPage);
    Form::hidden("limit", $limit);
    HTML::end('form');

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

    Search::pageLinks($currentPage, $pageCount);
  } // end if-else
  unset($recordQ);

  HTML::para(HTML::strLink(_("Return to users list"), $returnLocation));

  require_once("../shared/footer.php");
?>
