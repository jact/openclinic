<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_new_form.php,v 1.15 2006/03/26 15:20:49 jact Exp $
 */

/**
 * problem_new_form.php
 *
 * Addition screen of a medical problem
 *
 * @author jact <jachavar@gmail.com>
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
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError

  /**
   * Retrieving get vars
   */
  $idPatient = intval($_GET["key"]);
  $orderNumber = isset($_GET["num"]) ? intval($_GET["num"]) : (isset($formVar["order_number"]) ? $formVar["order_number"] - 1 : 0);

  // after clean (get_form_vars.php)
  $formVar["id_patient"] = $idPatient;
  //$formVar["id_member"] = ???; // @fixme si no está vacía y es la primera vez que se accede aquí es igual al médico que le corresponde por cupo?
  $formVar["order_number"] = $orderNumber + 1;
  $formVar["opening_date"] = date("Y-m-d"); // automatic date (ISO format) without getText
  $formVar["last_update_date"] = date("Y-m-d"); // automatic date (ISO format) without getText

  /**
   * Show page
   */
  $title = _("Add New Medical Problem");
  $focusFormField = "wording"; // to avoid JavaScript mistakes in demo version
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

  //Error::debug($formVar);

  require_once("../shared/form_errors_msg.php");

  /**
   * New form
   */
  echo '<form method="post" action="../medical/problem_new.php">' . "\n";

  Form::hidden("last_update_date", $formVar['last_update_date']);
  Form::hidden("id_patient", $idPatient);
  Form::hidden("opening_date", $formVar['opening_date']);
  Form::hidden("order_number", $formVar['order_number']);

  require_once("../medical/problem_fields.php");

  echo "</form>\n";

  HTML::message('* ' . _("Note: The fields with * are required."));

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  require_once("../shared/footer.php");
?>
