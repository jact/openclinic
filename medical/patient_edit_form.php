<?php
/**
 * patient_edit_form.php
 *
 * Edition screen of a patient
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_edit_form.php,v 1.24 2006/04/03 18:59:29 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "social";

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/Form.php");
  require_once("../classes/Staff_Query.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError

  /**
   * Retrieving get vars
   */
  $idPatient = intval($_GET["key"]);

  /**
   * Checking for $formError to read data from database
   */
  if ( !isset($formError) )
  {
    include_once("../classes/Patient_Page_Query.php");

    /**
     * Search database
     */
    $patQ = new Patient_Page_Query();
    $patQ->connect();

    if ( !$patQ->select($idPatient) )
    {
      $patQ->close();
      include_once("../shared/header.php");

      HTML::message(_("That patient does not exist."), OPEN_MSG_ERROR);

      include_once("../shared/footer.php");
      exit();
    }

    $pat = $patQ->fetch();
    if ($pat)
    {
      /**
       * load up post vars
       */
      $formVar["id_patient"] = $idPatient;
      //$formVar["last_update_date"] = date("Y-m-d"); // automatic date (ISO format)
      $formVar["id_member"] = $pat->getIdMember();
      $formVar["nif"] = $pat->getNIF();
      $formVar["first_name"] = $pat->getFirstName();
      $formVar["surname1"] = $pat->getSurname1();
      $formVar["surname2"] = $pat->getSurname2();
      $formVar["address"] = $pat->getAddress();
      $formVar["phone_contact"] = $pat->getPhone();
      $formVar["sex"] = $pat->getSex();
      $formVar["race"] = $pat->getRace();
      $formVar["birth_date"] = $pat->getBirthDate();
      $formVar["birth_place"] = $pat->getBirthPlace();
      $formVar["decease_date"] = $pat->getDeceaseDate();
      $formVar["nts"] = $pat->getNTS();
      $formVar["nss"] = $pat->getNSS();
      $formVar["family_situation"] = $pat->getFamilySituation();
      $formVar["labour_situation"] = $pat->getLabourSituation();
      $formVar["education"] = $pat->getEducation();
      $formVar["insurance_company"] = $pat->getInsuranceCompany();

      $_SESSION["formVar"] = $formVar;
    }
    else
    {
      Error::fetch($patQ, false);
    }

    $patQ->freeResult();
    $patQ->close();
    unset($patQ);
    unset($pat);
  }

  /**
   * Show page
   */
  $title = _("Edit Patient Social Data");
  $focusFormField = "nif"; // to avoid JavaScript mistakes in demo version
  require_once("../shared/header.php");

  $returnLocation = "../medical/patient_view.php?key=" . $idPatient;
  //Error::debug($formVar);

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Social Data") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  require_once("../shared/form_errors_msg.php");

  /**
   * Edit form
   */
  echo '<form method="post" action="../medical/patient_edit.php">' . "\n";

  Form::hidden("id_patient", $formVar["id_patient"]);
  //Form::hidden("last_update_date", $formVar["last_update_date"]);

  require_once("../medical/patient_fields.php");

  echo "</form>\n";

  HTML::message('* ' . _("Note: The fields with * are required."));

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  require_once("../shared/footer.php");
?>
