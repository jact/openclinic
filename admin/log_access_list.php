<?php
/**
 * log_access_list.php
 *
 * List of user's accesses in a date
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: log_access_list.php,v 1.18 2006/09/30 16:40:47 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.4
 */

  /**
   * Checking for get vars. Go back to log statistics if none found.
   */
  if (count($_GET) == 0)
  {
    header("Location: ../admin/log_stats.php?table=access");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "logs";
  $restrictInDemo = true; // There are not logs in demo version

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Access_Page_Query.php");

  /**
   * Retrieving get vars
   */
  $year = (isset($_GET["year"])) ? intval($_GET["year"]) : 0;
  $month = (isset($_GET["month"])) ? intval($_GET["month"]) : 0;
  $day = (isset($_GET["day"])) ? intval($_GET["day"]) : 0;
  $hour = (isset($_GET["hour"])) ? intval($_GET["hour"]) : 0;

  /**
   * Show page
   */
  $title = _("Access Logs");
  require_once("../shared/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Logs") => "../admin/log_stats.php?table=access",
    $title => ""
  );
  HTML::breadCrumb($links, "icon logIcon");
  unset($links);

  $accessQ = new Access_Page_Query();
  $accessQ->connect();

  $total = $accessQ->select($year, $month, $day, $hour);
  if ($total == 0)
  {
    $accessQ->close();
    HTML::message(_("No logs in this date."), OPEN_MSG_INFO);
    include_once("../shared/footer.php");
    exit();
  }

  HTML::section(3, _("Access Logs List:"));
  HTML::para(HTML::strTag('strong', sprintf(_("%d accesses."), $total)));

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
  for ($i = 1; $access = $accessQ->fetch(); $i++)
  {
    $row = $i . '.';
    $row .= OPEN_SEPARATOR;
    $row .= I18n::localDate($access["access_date"]);
    $row .= OPEN_SEPARATOR;
    $row .= $access["login"];
    $row .= OPEN_SEPARATOR;
    $row .= $profiles[$access["id_profile"]];

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end for

  $accessQ->freeResult();
  $accessQ->close();
  unset($accessQ);
  unset($access);
  unset($profiles);

  HTML::table($thead, $tbody, null, $options);

  HTML::para(
    HTML::strLink(_("Back return"), (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '../index.php'))
  );

  require_once("../shared/footer.php");
?>
