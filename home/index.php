<?php
/**
 * index.php
 *
 * Summary page of the Home tab
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: index.php,v 1.19 2008/03/23 11:59:18 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "home";
  $nav = "summary";

  require_once("../config/environment.php");
  if (isset($_SESSION['auth']['token']))
  {
    /**
     * Checking permissions
     */
    include_once("../auth/login_check.php");
    loginCheck();
  }
  require_once("../lib/Check.php");

  /**
   * Show page
   */
  $title = _("Welcome to OpenClinic");
  require_once("../layout/header.php");

  echo HTML::section(1, $title);

  echo HTML::para(_("OpenClinic is an easy to use, open source, medical records system."));
  echo HTML::para(_("When you select any of the following tabs you will be prompted to login."));

  echo HTML::section(2, HTML::link(_("Medical Records"), '../medical/index.php'), array('class' => 'icon icon_medical'));
  echo HTML::para(_("Use this tab to manage your patient's medical records."));
  echo HTML::para(_("Patient's Administration:"));

  $array = array(
    _("Search, new, delete, edit"),
    _("Social Data"),
    _("Clinic History"),
    _("Problem Reports")
  );
  echo HTML::itemList($array);

  echo HTML::rule();

  echo HTML::section(2, HTML::link(_("Admin"), '../admin/index.php'), array('class' => 'icon icon_admin'));
  echo HTML::para(_("Use this tab to manage administrative options."));

  $array = array(
    _("Staff members"),
    _("Config settings"),
    _("Clinic themes editor"),
    _("System users"),
    _("Dumps"),
    _("Logs")
  );
  echo HTML::itemList($array);

  require_once("../layout/footer.php");
?>
