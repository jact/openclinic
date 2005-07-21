<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_edit_form.php,v 1.13 2005/07/21 16:00:48 jact Exp $
 */

/**
 * patient_edit_form.php
 *
 * Edition screen of a patient
 *
 * Author: jact <jachavar@gmail.com>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "social";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");
  require_once("../classes/Staff_Query.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  // after login_check inclusion to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "nif";

  ////////////////////////////////////////////////////////////////////
  // Checking for query string flag to read data from database.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["key"]))
  {
    $idPatient = intval($_GET["key"]);

    include_once("../classes/Patient_Page_Query.php");

    $patQ = new Patient_Page_Query();
    $patQ->connect();
    if ($patQ->isError())
    {
      Error::query($patQ);
    }

    $numRows = $patQ->select($idPatient);
    if ($patQ->isError())
    {
      $patQ->close();
      Error::query($patQ);
    }

    if ( !$numRows )
    {
      $patQ->close();
      include_once("../shared/header.php");

      showMessage(_("That patient does not exist."), OPEN_MSG_ERROR);

      include_once("../shared/footer.php");
      exit();
    }

    $pat = $patQ->fetch();
    if ($patQ->isError())
    {
      Error::fetch($patQ, false);
    }
    else
    {
      ////////////////////////////////////////////////////////////////////
      // load up post vars
      ////////////////////////////////////////////////////////////////////
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
      $postVars["birth_date"] = $pat->getBirthDate(false);
      $postVars["birth_place"] = $pat->getBirthPlace();
      $postVars["decease_date"] = $pat->getDeceaseDate(false);
      $postVars["nts"] = $pat->getNTS();
      $postVars["nss"] = $pat->getNSS();
      $postVars["family_situation"] = $pat->getFamilySituation();
      $postVars["labour_situation"] = $pat->getLabourSituation();
      $postVars["education"] = $pat->getEducation();
      $postVars["insurance_company"] = $pat->getInsuranceCompany();

      $_SESSION["postVars"] = $postVars;
    }
    $patName = urlencode($pat->getFirstName() . " " . $pat->getSurname1() . " " . $pat->getSurname2());

    $patQ->freeResult();
    $patQ->close();
    unset($patQ);
    unset($pat);
  }
  else
  {
    $idPatient = $postVars["id_patient"];
    $patName = urlencode($postVars["first_name"] . " " . $postVars["surname1"] . " " . $postVars["surname2"]);
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Edit Patient Social Data");
  require_once("../shared/header.php");

  $returnLocation = "../medical/patient_view.php?key=" . $idPatient . "&amp;reset=Y";
  //Error::debug($postVars);

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Social Data") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  require_once("../shared/form_errors_msg.php");
?>

<form method="post" action="../medical/patient_edit.php">
  <div>
<?php
  showInputHidden("id_patient", $postVars["id_patient"]);
  //showInputHidden("last_update_date", $postVars["last_update_date"]);

  require_once("../medical/patient_fields.php");
?>
  </div>
</form>

<?php
  showMessage('* ' . _("Note: The fields with * are required."));

  require_once("../shared/footer.php");
?>
