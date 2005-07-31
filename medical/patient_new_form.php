<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_new_form.php,v 1.7 2005/07/31 11:09:00 jact Exp $
 */

/**
 * patient_new_form.php
 *
 * Addition screen of a patient
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "new";
  $onlyDoctor = false;
  $returnLocation = "../medical/index.php";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Staff_Query.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  // after clean (get_form_vars.php)
  //$postVars["last_update_date"] = date("d-m-Y"); //date("Y-m-d");

  /**
   * Show page
   */
  $title = _("Add New Patient");
  // to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "nif";
  require_once("../shared/header.php");

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
  echo '<form method="post" action="../medical/patient_new.php">' . "\n";
  echo "<div>\n";

  //Form::hidden("last_update_date", "last_update_date", $postVars['last_update_date']);

  require_once("../medical/patient_fields.php");

  echo "</div>\n</form>\n";

  HTML::message('* ' . _("Note: The fields with * are required."));

  require_once("../shared/footer.php");
?>
