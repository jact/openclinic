<?php
/**
 * index.php
 *
 * Summary page of the Home tab
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: index.php,v 1.14 2006/10/13 19:50:14 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "home";
  $nav = "home";

  require_once("../config/environment.php");
  require_once("../lib/Check.php");

  /**
   * Retrieving get vars
   */
  $info = (isset($_GET["info"]) ? urldecode(Check::safeText($_GET["info"])) : "");

  /**
   * Show page
   */
  $title = _("Welcome to OpenClinic");
  require_once("../layout/header.php");

  HTML::section(1, $title);

  /**
   * Display update message if coming from edit with a successful update.
   */
  if (isset($_GET["updated"]) && !empty($info))
  {
    HTML::message(sprintf(_("User, %s, has been updated."), $info), OPEN_MSG_INFO);
  }

  /**
   * Display login used message.
   */
  if (isset($_GET["login"]) && !empty($info))
  {
    HTML::message(sprintf(_("Login, %s, already exists. The changes have no effect."), $info), OPEN_MSG_INFO);
  }

  HTML::para(_("OpenClinic is an easy to use, open source, medical records system."));
  HTML::para(_("When you select any of the following tabs you will be prompted to login."));

  HTML::section(2, HTML::strLink(_("Medical Records"), '../medical/index.php'), array('class' => 'bigIcon medicalIcon'));
  HTML::para(_("Use this tab to manage your patient's medical records."));
  HTML::para(_("Patient's Administration:"));

  $array = array(
    _("Search, new, delete, edit"),
    _("Social Data"),
    _("Clinic History"),
    _("Problem Reports")
  );
  HTML::itemList($array);

  HTML::rule();

  HTML::section(2, HTML::strLink(_("Admin"), '../admin/index.php'), array('class' => 'bigIcon adminIcon'));
  HTML::para(_("Use this tab to manage administrative options."));

  $array = array(
    _("Staff members"),
    _("Config settings"),
    _("Clinic themes editor"),
    _("System users"),
    _("Dumps"),
    _("Logs")
  );
  HTML::itemList($array);

  require_once("../layout/footer.php");
?>
