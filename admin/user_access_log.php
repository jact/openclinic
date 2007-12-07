<?php
/**
 * user_access_log.php
 *
 * List of user's accesses
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_access_log.php,v 1.35 2007/12/07 16:50:50 jact Exp $
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

  require_once("../model/Query/Page/Access.php");
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
   * Search user accesses
   */
  $accessQ = new Query_Page_Access();
  $accessQ->setItemsPerPage(OPEN_ITEMS_PER_PAGE);
  $accessQ->searchUser($idUser, $currentPage);

  if ($accessQ->getRowCount() == 0)
  {
    $accessQ->close();

    FlashMsg::add(sprintf(_("No logs for user %s."), $login));
    header("Location: " . $returnLocation);
    exit();
  }

  /**
   * Show page
   */
  $title = _("Access Logs");
  require_once("../layout/header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Users") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon icon_user");
  unset($links);

  HTML::section(2, sprintf(_("Access Logs List for user %s"), $login) . ":");

  // Printing result stats and page nav
  HTML::para(HTML::strTag('strong', sprintf(_("%d accesses."), $accessQ->getRowCount())));

  $params = array(
    'id_user=' . $idUser,
    'login=' . $login
  );
  $params = implode('&', $params);

  $pageCount = $accessQ->getPageCount();
  $pageLinks = Search::strPageLinks($currentPage, $pageCount, $_SERVER['PHP_SELF'] . '?' . $params);
  echo $pageLinks;

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

  echo $pageLinks;

  unset($accessQ);
  unset($access);
  unset($profiles);

  HTML::para(HTML::strLink(_("Return to users list"), $returnLocation));

  require_once("../layout/footer.php");
?>
