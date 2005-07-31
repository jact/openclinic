<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_new_form.php,v 1.10 2005/07/31 11:11:35 jact Exp $
 */

/**
 * problem_new_form.php
 *
 * Addition screen of a medical problem
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"])) // $_GET["num"] can be empty
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/Form.php");
  require_once("../classes/Staff_Query.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  /**
   * Retrieving get vars
   */
  $idPatient = intval($_GET["key"]);
  $orderNumber = intval($_GET["num"]);

  // after clean (get_form_vars.php)
  $postVars["id_patient"] = $idPatient;
  //$postVars["id_member"] = ???; // @fixme si no está vacía y es la primera vez que se accede aquí es igual al médico que le corresponde por cupo?
  $postVars["order_number"] = $orderNumber + 1;
  $postVars["opening_date"] = date("Y-m-d"); // automatic date (ISO format) without getText
  $postVars["last_update_date"] = date("Y-m-d"); // automatic date (ISO format) without getText

  /**
   * Show page
   */
  $title = _("Add New Medical Problem");
  // to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "wording";
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  $returnLocation = "../medical/problem_list.php?key=" . $idPatient;

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Medical Problems Report") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  if ( !showPatientHeader($idPatient) )
  {
    HTML::message(_("That patient does not exist."), OPEN_MSG_ERROR);

    include_once("../shared/footer.php");
    exit();
  }
  echo "<br />\n"; // @fixme should be deleted

  //Error::debug($postVars);

  require_once("../shared/form_errors_msg.php");

  /**
   * New form
   */
  echo '<form method="post" action="../medical/problem_new.php">' . "\n";
  echo "<div>\n";

  Form::hidden("last_update_date", "last_update_date", $postVars['last_update_date']);
  Form::hidden("id_patient", "id_patient", $idPatient);
  Form::hidden("opening_date", "opening_date", $postVars['opening_date']);
  Form::hidden("order_number", "order_number", $postVars['order_number']);

  require_once("../medical/problem_fields.php");

  echo "</div>\n</form>\n";

  HTML::message('* ' . _("Note: The fields with * are required."));

  require_once("../shared/footer.php");
?>
