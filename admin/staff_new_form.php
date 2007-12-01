<?php
/**
 * staff_new_form.php
 *
 * Addition screen of a staff member
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: staff_new_form.php,v 1.27 2007/12/01 12:06:25 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "staff";
  $returnLocation = "../admin/staff_list.php";

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/Check.php");

  /**
   * Show page
   */
  $memberType = (isset($_GET["type"])) ? Check::safeText($_GET["type"]) : "A"; // Administrative by default

  switch (strtolower($memberType))
  {
    case "a":
      $title = _("Add New Administrative Information");
      $typeValue = OPEN_ADMINISTRATIVE;
      break;

    case "d":
      $title = _("Add New Doctor Information");
      $typeValue = OPEN_DOCTOR;
      break;
  }

  $focusFormField = "nif"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Staff Members") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon icon_staff");
  unset($links);

  Form::errorMsg();

  /**
   * New form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../admin/staff_new.php?type=' . $memberType));

  Form::hidden("member_type", $typeValue);

  require_once("../admin/staff_fields.php");

  HTML::end('form');

  Msg::hint('* ' . _("Note: The fields with * are required."));

  HTML::para(HTML::strLink(_("Return"), $returnLocation));

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  require_once("../layout/footer.php");
?>
