<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_new.php,v 1.1 2004/03/24 19:19:28 jact Exp $
 */

/**
 * patient_new.php
 ********************************************************************
 * Patient addition process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 24/03/04 20:19
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
  require_once("../classes/Patient_Query.php");
  require_once("../lib/error_lib.php");
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
  $patQ = new Patient_Query();
  $patQ->connect();
  if ($patQ->errorOccurred())
  {
    showQueryError($patQ);
  }

  if ($patQ->existName($pat->getFirstName(), $pat->getSurname1(), $pat->getSurname2()))
  {
    $patQ->close();
    include_once("../shared/header.php");

    echo '<p>' . sprintf(_("Patient name, %s, is already in use. The changes have no effect."), $patName) . "</p>\n";

    include_once("../shared/footer.php");
    exit();
  }

  if ( !$patQ->insert($pat) )
  {
    $patQ->close();
    showQueryError($patQ);
  }

  $idPatient = $patQ->getLastId();
  if ( !$idPatient )
  {
    $patQ->close();
    showQueryError($patQ);
  }
  $patQ->close();
  unset($patQ);
  unset($pat);

  ////////////////////////////////////////////////////////////////////
  // Record log process
  ////////////////////////////////////////////////////////////////////
  recordLog("patient_tbl", "INSERT", $idPatient);

  ////////////////////////////////////////////////////////////////////
  // Reset abort setting
  ////////////////////////////////////////////////////////////////////
  ignore_user_abort($oldAbort);

  ////////////////////////////////////////////////////////////////////
  // Destroy form values and errors
  ////////////////////////////////////////////////////////////////////
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  ////////////////////////////////////////////////////////////////////
  // Show success page
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/header.php");

  $returnLocation = "../medical/index.php";

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => $returnLocation,
    _("New Patient") => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  echo '<p>' . sprintf(_("Patient, %s, has been added."), $patName) . "</p>\n";

  echo '<p><a href="../medical/patient_view.php?key=' . $idPatient . '">' . _("View Social Data") . "</a></p>\n";

  echo '<p><a href="' . $returnLocation . '">' . _("Return to Medical Records Summary") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
