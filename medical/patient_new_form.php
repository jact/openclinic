<?php
/**
 * patient_new_form.php
 *
 * Addition screen of a patient
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_new_form.php,v 1.16 2007/10/27 11:56:49 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "new";
  $onlyDoctor = false;
  $returnLocation = "../medical/index.php";

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Staff_Query.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError

  // after clean (get_form_vars.php)
  //$formVar["last_update_date"] = date("d-m-Y"); //date("Y-m-d");

  /**
   * Show page
   */
  $title = _("Add New Patient");
  $focusFormField = "nif"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => $returnLocation,
    _("New Patient") => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  require_once("../shared/form_errors_msg.php");

  /**
   * New form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../medical/patient_new.php'));

  //Form::hidden("last_update_date", $formVar['last_update_date']);

  require_once("../medical/patient_fields.php");

  HTML::end('form');

  HTML::message('* ' . _("Note: The fields with * are required."), OPEN_MSG_HINT);

  HTML::para(HTML::strLink(_("Return"), $returnLocation));

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  require_once("../layout/footer.php");
?>
