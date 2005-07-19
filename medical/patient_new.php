<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_new.php,v 1.7 2005/07/19 19:51:13 jact Exp $
 */

/**
 * patient_new.php
 *
 * Patient addition process
 *
 * Author: jact <jachavar@gmail.com>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "";
  $onlyDoctor = false;
  $errorLocation = "../medical/patient_new_form.php";

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: " . $errorLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Patient_Page_Query.php");
  require_once("../shared/record_log.php"); // record log

  ////////////////////////////////////////////////////////////////////
  // Validate data
  ////////////////////////////////////////////////////////////////////
  $pat = new Patient();

  require_once("../medical/patient_validate_post.php");

  $patName = $pat->getFirstName() . ' ' . $pat->getSurname1() . ' ' . $pat->getSurname2();

  ////////////////////////////////////////////////////////////////////
  // Prevent user from aborting script
  ////////////////////////////////////////////////////////////////////
  $oldAbort = ignore_user_abort(true);

  ////////////////////////////////////////////////////////////////////
  // Insert new patient
  ////////////////////////////////////////////////////////////////////
  $patQ = new Patient_Page_Query();
  $patQ->connect();
  if ($patQ->isError())
  {
    Error::query($patQ);
  }

  if ($patQ->existName($pat->getFirstName(), $pat->getSurname1(), $pat->getSurname2()))
  {
    $patQ->close();
    include_once("../shared/header.php");

    echo '<p>' . sprintf(_("Patient name, %s, is already in use. The changes have no effect."), $patName) . "</p>\n";

    include_once("../shared/footer.php");
    exit();
  }

  $patQ->insert($pat);
  if ($patQ->isError())
  {
    $patQ->close();
    Error::query($patQ);
  }

  $idPatient = $patQ->getLastId();
  if ($patQ->isError())
  {
    $patQ->close();
    Error::query($patQ);
  }

  $table = $patQ->getTableName();

  $patQ->close();
  unset($patQ);
  unset($pat);

  ////////////////////////////////////////////////////////////////////
  // Record log process
  ////////////////////////////////////////////////////////////////////
  recordLog($table, "INSERT", array($idPatient));

  ////////////////////////////////////////////////////////////////////
  // Reset abort setting
  ////////////////////////////////////////////////////////////////////
  ignore_user_abort($oldAbort);

  ////////////////////////////////////////////////////////////////////
  // Destroy form values and errors
  ////////////////////////////////////////////////////////////////////
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  $returnLocation = "../medical/patient_view.php?key=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Redirect to patient view to avoid reload problem
  ////////////////////////////////////////////////////////////////////
  header("Location: " . $returnLocation . "&added=Y");
?>
