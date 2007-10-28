<?php
/**
 * problem_new_form.php
 *
 * Addition screen of a medical problem
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_new_form.php,v 1.25 2007/10/28 20:16:25 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = false;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError
  require_once("../lib/PatientInfo.php");

  /**
   * Retrieving vars (PGS)
   */
  $idPatient = Check::postGetSessionInt('id_patient');
  $orderNumber = Check::postGetSessionInt('order_number',
    isset($formVar["order_number"]) ? $formVar["order_number"] - 1 : 0
  );

  $patient = new PatientInfo($idPatient);
  if ($patient->getName() == '')
  {
    FlashMsg::add(_("That patient does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

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
  $titlePage = $patient->getName() . ' (' . $title . ')';
  $focusFormField = "wording"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");

  //$returnLocation = "../medical/problem_list.php?id_patient=" . $idPatient;
  $returnLocation = "../medical/problem_list.php";

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    _("Medical Problems Report") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  $patient->showHeader();

  //Error::debug($formVar);

  require_once("../shared/form_errors_msg.php");

  /**
   * New form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../medical/problem_new.php'));

  Form::hidden("last_update_date", $formVar['last_update_date']);
  Form::hidden("id_patient", $idPatient);
  Form::hidden("opening_date", $formVar['opening_date']);
  Form::hidden("order_number", $formVar['order_number']);

  require_once("../medical/problem_fields.php");

  HTML::end('form');

  Msg::hint('* ' . _("Note: The fields with * are required."));

  HTML::para(HTML::strLink(_("Return"), $returnLocation));

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  require_once("../layout/footer.php");
?>
