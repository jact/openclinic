<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_validate_post.php,v 1.1 2004/02/19 18:45:48 jact Exp $
 */

/**
 * staff_validate_post.php
 ********************************************************************
 * Validate post data of a staff member
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 19/02/04 19:45
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

  $staff->setMemberType($_POST["member_type"]);
  $_POST["member_type"] = $staff->getMemberType();

  $staff->setCollegiateNumber($_POST["collegiate_number"]);
  $_POST["collegiate_number"] = $staff->getCollegiateNumber();

  $staff->setNIF($_POST["nif"]);
  $_POST["nif"] = $staff->getNIF();

  $staff->setFirstName($_POST["first_name"]);
  $_POST["first_name"] = $staff->getFirstName();

  $staff->setSurname1($_POST["surname1"]);
  $_POST["surname1"] = $staff->getSurname1();

  $staff->setSurname2($_POST["surname2"]);
  $_POST["surname2"] = $staff->getSurname2();

  $staff->setAddress($_POST["address"]);
  $_POST["address"] = $staff->getAddress();

  $staff->setPhone($_POST["phone_contact"]);
  $_POST["phone_contact"] = $staff->getPhone();

  $staff->setLogin($_POST["login"]);
  $_POST["login"] = $staff->getLogin();

  if ( !$staff->validateData() )
  {
    $pageErrors["collegiate_number"] = $staff->getCollegiateNumberError();
    $pageErrors["nif"] = $staff->getNIFError();
    $pageErrors["first_name"] = $staff->getFirstNameError();
    $pageErrors["surname1"] = $staff->getSurname1Error();
    $pageErrors["surname2"] = $staff->getSurname2Error();
    $pageErrors["login"] = $staff->getLoginError();

    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;

    header("Location: " . $errorLocation);
    exit();
  }
?>
