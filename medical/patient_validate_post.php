<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_validate_post.php,v 1.5 2004/10/17 14:57:03 jact Exp $
 */

/**
 * patient_validate_post.php
 ********************************************************************
 * Validate post data of a patient
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * @since 0.6
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['PATH_TRANSLATED'])
  {
    header("Location: ../index.php");
    exit();
  }

  //$pat->setLastUpdateDate($_POST["last_update_date"]);
  //$_POST["last_update_date"] = $pat->getLastUpdateDate();

  $pat->setIdMember($_POST["id_member"]);
  $_POST["id_member"] = $pat->getIdMember();

  $pat->setNIF($_POST["nif"]);
  $_POST["nif"] = $pat->getNIF();

  $pat->setFirstName($_POST["first_name"]);
  $_POST["first_name"] = $pat->getFirstName();

  $pat->setSurname1($_POST["surname1"]);
  $_POST["surname1"] = $pat->getSurname1();

  $pat->setSurname2($_POST["surname2"]);
  $_POST["surname2"] = $pat->getSurname2();

  $pat->setAddress($_POST["address"]);
  $_POST["address"] = $pat->getAddress();

  $pat->setPhone($_POST["phone_contact"]);
  $_POST["phone_contact"] = $pat->getPhone();

  $pat->setSex($_POST["sex"]);
  $_POST["sex"] = $pat->getSex();

  $pat->setRace($_POST["race"]);
  $_POST["race"] = $pat->getRace();

  $pat->setBirthDateFromParts($_POST["month"], $_POST["day"], $_POST["year"]);
  $_POST["birth_date"] = $pat->getBirthDate();

  $pat->setBirthPlace($_POST["birth_place"]);
  $_POST["birth_place"] = $pat->getBirthPlace();

  $pat->setDeceaseDateFromParts($_POST["dmonth"], $_POST["dday"], $_POST["dyear"]);
  $_POST["decease_date"] = $pat->getDeceaseDate();

  $pat->setNTS($_POST["nts"]);
  $_POST["nts"] = $pat->getNTS();

  $pat->setNSS($_POST["nss"]);
  $_POST["nss"] = $pat->getNSS();

  $pat->setFamilySituation($_POST["family_situation"]);
  $_POST["family_situation"] = $pat->getFamilySituation();

  $pat->setLabourSituation($_POST["labour_situation"]);
  $_POST["labour_situation"] = $pat->getLabourSituation();

  $pat->setEducation($_POST["education"]);
  $_POST["education"] = $pat->getEducation();

  $pat->setInsuranceCompany($_POST["insurance_company"]);
  $_POST["insurance_company"] = $pat->getInsuranceCompany();

  if ( !$pat->validateData() )
  {
    $pageErrors["first_name"] = $pat->getFirstNameError();
    $pageErrors["surname1"] = $pat->getSurname1Error();
    $pageErrors["surname2"] = $pat->getSurname2Error();
    $pageErrors["birth_date"] = $pat->getBirthDateError();
    $pageErrors["decease_date"] = $pat->getDeceaseDateError();

    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;

    header("Location: " . $errorLocation);
    exit();
  }
?>
