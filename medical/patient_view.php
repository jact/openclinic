<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_view.php,v 1.15 2005/07/28 17:47:33 jact Exp $
 */

/**
 * patient_view.php
 *
 * View patient data screen
 *
 * Author: jact <jachavar@gmail.com>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || empty($_GET["key"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "social";
  $onlyDoctor = true;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Patient_Page_Query.php");
  require_once("../classes/Staff_Query.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  ////////////////////////////////////////////////////////////////////
  // Retrieving get var
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_GET["key"]);

  ////////////////////////////////////////////////////////////////////
  // Search database for patient
  ////////////////////////////////////////////////////////////////////
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

    HTML::message(_("That patient does not exist."), OPEN_MSG_ERROR);

    include_once("../shared/footer.php");
    exit();
  }

  $pat = $patQ->fetch();
  if ($patQ->isError())
  {
    $patQ->close();
    Error::fetch($patQ);
  }
  $patQ->freeResult();
  $patQ->close();
  unset($patQ);
  $patName = $pat->getFirstName() . " " . $pat->getSurname1() . " " . $pat->getSurname2();

  ////////////////////////////////////////////////////////////////////
  // Update session variables
  ////////////////////////////////////////////////////////////////////
  require_once("../medical/visited_list.php");
  addPatient($pat->getIdPatient(), $patName);

  ////////////////////////////////////////////////////////////////////
  // Show search results
  ////////////////////////////////////////////////////////////////////
  $title = _("Social Data");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  ////////////////////////////////////////////////////////////////////
  // Display insertion message if coming from new with a successful insert.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["added"]))
  {
    HTML::message(_("Patient has been added."), OPEN_MSG_INFO);
  }

  ////////////////////////////////////////////////////////////////////
  // Display update message if coming from edit with a successful update.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["updated"]))
  {
    HTML::message(_("Patient has been updated."), OPEN_MSG_INFO);
  }

  echo '<p>';
  if ($hasMedicalAdminAuth)
  {
    echo '<a href="../medical/patient_edit_form.php?key=' . $idPatient . '&amp;reset=Y">' . _("Edit Social Data") . '</a> | ';
    echo '<a href="../medical/patient_del_confirm.php?key=' . $idPatient . '&amp;name=' . urlencode($patName) . '">' . _("Delete Patient") . '</a> | ';
  }
  echo '<a href="../medical/relative_list.php?key=' . $idPatient . '">' . _("View Relatives") . '</a>';
  echo "</p>\n";

  echo "<hr />\n";

  echo '<h3>' . _("Patient") . "</h3>\n";
  echo '<p>' . $pat->getSurname1() . ' ' . $pat->getSurname2() . ', ' . $pat->getFirstName() . "</p>\n";

  //echo '<h3>' . _("Last Update Date") . "</h3>\n";
  //echo '<p>' . I18n::localDate($pat->getLastUpdateDate()) . "</p>\n";

  if ($pat->getNIF())
  {
    echo '<h3>' . _("Tax Identification Number (TIN)") . "</h3>\n";
    echo '<p>' . $pat->getNIF() . "</p>\n";
  }

  if ($pat->getAddress())
  {
    echo '<h3>' . _("Address") . "</h3>\n";
    echo '<p>' . nl2br($pat->getAddress()) . "</p>\n";
  }

  if ($pat->getPhone())
  {
    echo '<h3>' . _("Phone Contact") . "</h3>\n";
    echo '<p>' . nl2br($pat->getPhone()) . "</p>\n";
  }

  echo '<h3>' . _("Sex") . "</h3>\n";
  echo '<p>' . (($pat->getSex() == 'V') ? _("Male") : _("Female")) . "</p>\n";

  if ($pat->getRace())
  {
    echo '<h3>' . _("Race") . "</h3>\n";
    echo '<p>' . $pat->getRace() . "</p>\n";
  }

  if ($pat->getBirthDate() != "")
  {
    echo '<h3>' . _("Birth Date") . "</h3>\n";
    echo '<p>' . $pat->getBirthDate() . "</p>\n";

    echo '<h3>' . _("Age") . "</h3>\n";
    echo '<p>' . $pat->getAge() . "</p>\n";
  }

  if ($pat->getBirthPlace())
  {
    echo '<h3>' . _("Birth Place") . "</h3>\n";
    echo '<p>' . $pat->getBirthPlace() . "</p>\n";
  }

  if ($pat->getDeceaseDate() != "")
  {
    echo '<h3>' . _("Decease Date") . "</h3>\n";
    echo '<p>' . $pat->getDeceaseDate() . "</p>\n";
  }

  if ($pat->getNTS())
  {
    echo '<h3>' . _("Sanitary Card Number (SCN)") . "</h3>\n";
    echo '<p>' . $pat->getNTS() . "</p>\n";
  }

  if ($pat->getNSS())
  {
    echo '<h3>' . _("National Health Service Number (NHSN)") . "</h3>\n";
    echo '<p>' . $pat->getNSS() . "</p>\n";
  }

  if ($pat->getFamilySituation())
  {
    echo '<h3>' . _("Family Situation") . "</h3>\n";
    echo '<p>' . nl2br($pat->getFamilySituation()) . "</p>\n";
  }

  if ($pat->getLabourSituation())
  {
    echo '<h3>' . _("Labour Situation") . "</h3>\n";
    echo '<p>' . nl2br($pat->getLabourSituation()) . "</p>\n";
  }

  if ($pat->getEducation())
  {
    echo '<h3>' . _("Education") . "</h3>\n";
    echo '<p>' . nl2br($pat->getEducation()) . "</p>\n";
  }

  if ($pat->getInsuranceCompany())
  {
    echo '<h3>' . _("Insurance Company") . "</h3>\n";
    echo '<p>' . $pat->getInsuranceCompany() . "</p>\n";
  }

  if ($pat->getIdMember())
  {
    $staffQ = new Staff_Query();
    $staffQ->connect();
    if ($staffQ->isError())
    {
      Error::query($staffQ);
    }

    $numRows = $staffQ->select($pat->getIdMember());
    if ($numRows)
    {
      $staff = $staffQ->fetch();
      if ($staff)
      {
        echo '<h3>' . _("Doctor you are assigned to") . "</h3>\n";
        echo '<p>' . $staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName() . "</p>\n";
      }
      $staffQ->freeResult();
    }
    $staffQ->close();
    unset($staffQ);
    unset($staff);
  }

  unset($pat);

  require_once("../shared/footer.php");
?>
