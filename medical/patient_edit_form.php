<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_edit_form.php,v 1.21 2006/03/12 18:43:18 jact Exp $
 */

/**
 * patient_edit_form.php
 *
 * Edition screen of a patient
 *
 * Author: jact <jachavar@gmail.com>
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
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  /**
   * Retrieving get vars
   */
  $idPatient = intval($_GET["key"]);

  /**
   * Checking for query string flag to read data from database
   */
  if (isset($_GET["reset"]))
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
      $postVars["id_patient"] = $idPatient;
      //$postVars["last_update_date"] = date("Y-m-d"); // automatic date (ISO format)
      $postVars["id_member"] = $pat->getIdMember();
      $postVars["nif"] = $pat->getNIF();
      $postVars["first_name"] = $pat->getFirstName();
      $postVars["surname1"] = $pat->getSurname1();
      $postVars["surname2"] = $pat->getSurname2();
      $postVars["address"] = $pat->getAddress();
      $postVars["phone_contact"] = $pat->getPhone();
      $postVars["sex"] = $pat->getSex();
      $postVars["race"] = $pat->getRace();
      $postVars["birth_date"] = $pat->getBirthDate();
      $postVars["birth_place"] = $pat->getBirthPlace();
      $postVars["decease_date"] = $pat->getDeceaseDate();
      $postVars["nts"] = $pat->getNTS();
      $postVars["nss"] = $pat->getNSS();
      $postVars["family_situation"] = $pat->getFamilySituation();
      $postVars["labour_situation"] = $pat->getLabourSituation();
      $postVars["education"] = $pat->getEducation();
      $postVars["insurance_company"] = $pat->getInsuranceCompany();

      $_SESSION["postVars"] = $postVars;
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

  $returnLocation = "../medical/patient_view.php?key=" . $idPatient . "&amp;reset=Y";
  //Error::debug($postVars);

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
  echo "<div>\n";

  Form::hidden("id_patient", $postVars["id_patient"]);
  //Form::hidden("last_update_date", $postVars["last_update_date"]);

  require_once("../medical/patient_fields.php");

  echo "</div>\n</form>\n";

  HTML::message('* ' . _("Note: The fields with * are required."));

  require_once("../shared/footer.php");
?>
