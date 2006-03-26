<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_new_form.php,v 1.17 2006/03/26 14:47:34 jact Exp $
 */

/**
 * staff_new_form.php
 *
 * Addition screen of a staff member
 *
 * @author jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "staff";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError

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
  require_once("../shared/header.php");

  $returnLocation = "../admin/staff_list.php";

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Staff Members") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon staffIcon");
  unset($links);

  require_once("../shared/form_errors_msg.php");

  /**
   * New form
   */
  echo '<form method="post" action="../admin/staff_new.php?type=' . $memberType . '">' . "\n";

  Form::hidden("member_type", $typeValue);

  require_once("../admin/staff_fields.php");

  echo "</form>\n";

  HTML::message('* ' . _("Note: The fields with * are required."));

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  require_once("../shared/footer.php");
?>
