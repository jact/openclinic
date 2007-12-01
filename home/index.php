<?php
/**
 * index.php
 *
 * Summary page of the Home tab
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: index.php,v 1.17 2007/12/01 12:40:21 jact Exp $
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
    include_once("../auth/login_check.php");
  }
  require_once("../lib/Check.php");

  /**
   * Show page
   */
  $title = _("Welcome to OpenClinic");
  require_once("../layout/header.php");

  HTML::section(1, $title);

  HTML::para(_("OpenClinic is an easy to use, open source, medical records system."));
  HTML::para(_("When you select any of the following tabs you will be prompted to login."));

  HTML::section(2, HTML::strLink(_("Medical Records"), '../medical/index.php'), array('class' => 'icon icon_medical'));
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

  HTML::section(2, HTML::strLink(_("Admin"), '../admin/index.php'), array('class' => 'icon icon_admin'));
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
