<?php
/**
 * patient_edit_form.php
 *
 * Edition screen of a patient
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_edit_form.php,v 1.39 2008/03/23 12:00:17 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "social";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE);

  /**
   * Retrieving vars (PGS)
   */
  $idPatient = Check::postGetSessionInt('id_patient');

  /**
   * Checking for $formError to read data from database
   */
  if ( !isset($formError) )
  {
    include_once("../model/Patient.php");

    $patient = new Patient($idPatient);
    $patName = $patient->getName();
    if ($patName == '')
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

    Form::setSession($formVar);

    unset($patient);
  }
  else
  {
    $patName = $formVar["first_name"] . ' ' . $formVar["surname1"] . ' ' . $formVar["surname2"];
  }

  /**
   * Show page
   */
  $title = _("Edit Patient Social Data");
  $titlePage = $patName . ' (' . $title . ')';
  $focusFormField = "nif"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");

  //$returnLocation = "../medical/patient_view.php?id_patient=" . $idPatient;
  $returnLocation = "../medical/patient_view.php";
  //Error::debug($formVar);

  /**
   * Breadcrumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patName => $returnLocation,
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_patient");
  unset($links);

  echo Form::errorMsg();

  /**
   * Edit form
   */
  echo HTML::start('form', array('method' => 'post', 'action' => '../medical/patient_edit.php'));

  echo Form::hidden("id_patient", $formVar["id_patient"]);
  //echo Form::hidden("last_update_date", $formVar["last_update_date"]);

  require_once("../medical/patient_fields.php");

  echo HTML::end('form');

  echo Msg::hint('* ' . _("Note: The fields with * are required."));

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  require_once("../layout/footer.php");
?>
