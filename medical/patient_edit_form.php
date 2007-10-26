<?php
/**
 * patient_edit_form.php
 *
 * Edition screen of a patient
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_edit_form.php,v 1.27 2007/10/26 21:51:35 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "social";

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/Form.php");
  require_once("../model/Staff_Query.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError

  /**
   * Retrieving vars (PGS)
   */
  $idPatient = Check::postGetSessionInt('id_patient');

  /**
   * Checking for $formError to read data from database
   */
  if ( !isset($formError) )
  {
    include_once("../medical/PatientInfo.php");

    $patient = new PatientInfo($idPatient);
    $patName = $patient->getName();
    $patient = $patient->getObject();
    if ($patient == null)
    {
      FlashMsg::add(_("That patient does not exist."), OPEN_MSG_ERROR);
      header("Location: ../medical/patient_search_form.php");
      exit();
    }

    /**
     * load up post vars
     */
    $formVar["id_patient"] = $idPatient;
    //$formVar["last_update_date"] = date("Y-m-d"); // automatic date (ISO format)
    $formVar["id_member"] = $patient->getIdMember();
    $formVar["nif"] = $patient->getNIF();
    $formVar["first_name"] = $patient->getFirstName();
    $formVar["surname1"] = $patient->getSurname1();
    $formVar["surname2"] = $patient->getSurname2();
    $formVar["address"] = $patient->getAddress();
    $formVar["phone_contact"] = $patient->getPhone();
    $formVar["sex"] = $patient->getSex();
    $formVar["race"] = $patient->getRace();
    $formVar["birth_date"] = $patient->getBirthDate();
    $formVar["birth_place"] = $patient->getBirthPlace();
    $formVar["decease_date"] = $patient->getDeceaseDate();
    $formVar["nts"] = $patient->getNTS();
    $formVar["nss"] = $patient->getNSS();
    $formVar["family_situation"] = $patient->getFamilySituation();
    $formVar["labour_situation"] = $patient->getLabourSituation();
    $formVar["education"] = $patient->getEducation();
    $formVar["insurance_company"] = $patient->getInsuranceCompany();

    $_SESSION["formVar"] = $formVar;

    unset($patient);
  }

  /**
   * Show page
   */
  $title = _("Edit Patient Social Data");
  $focusFormField = "nif"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");

  //$returnLocation = "../medical/patient_view.php?id_patient=" . $idPatient;
  $returnLocation = "../medical/patient_view.php";
  //Error::debug($formVar);

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patName => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  require_once("../shared/form_errors_msg.php");

  /**
   * Edit form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../medical/patient_edit.php'));

  Form::hidden("id_patient", $formVar["id_patient"]);
  //Form::hidden("last_update_date", $formVar["last_update_date"]);

  require_once("../medical/patient_fields.php");

  HTML::end('form');

  HTML::message('* ' . _("Note: The fields with * are required."), OPEN_MSG_HINT);

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  require_once("../layout/footer.php");
?>
