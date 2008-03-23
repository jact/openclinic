<?php
/**
 * patient_new_form.php
 *
 * Addition screen of a patient
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_new_form.php,v 1.25 2008/03/23 12:00:17 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "new";
  $returnLocation = "../medical/index.php";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE);

  // after clean form vars
  //$formVar["last_update_date"] = date("d-m-Y"); //date("Y-m-d");

  /**
   * Show page
   */
  $title = _("Add New Patient");
  $focusFormField = "nif"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");

  /**
   * Breadcrumb
   */
  $links = array(
    _("Medical Records") => $returnLocation,
    _("New Patient") => ""
  );
  echo HTML::breadcrumb($links, "icon icon_patient");
  unset($links);

  echo Form::errorMsg();

  /**
   * New form
   */
  echo HTML::start('form', array('method' => 'post', 'action' => '../medical/patient_new.php'));

  //echo Form::hidden("last_update_date", $formVar['last_update_date']);

  require_once("../medical/patient_fields.php");

  echo HTML::end('form');

  echo Msg::hint('* ' . _("Note: The fields with * are required."));

  echo HTML::para(HTML::link(_("Return"), $returnLocation));

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  require_once("../layout/footer.php");
?>
