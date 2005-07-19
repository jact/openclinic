<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_edit.php,v 1.8 2005/07/19 19:51:13 jact Exp $
 */

/**
 * patient_edit.php
 *
 * Patient edition process
 *
 * Author: jact <jachavar@gmail.com>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "social";
  $onlyDoctor = false;
  $errorLocation = "../medical/patient_edit_form.php";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Patient_Page_Query.php");
  require_once("../shared/record_log.php"); // record log
  require_once("../lib/validator_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Validate data
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_POST["id_patient"]);
  $patName = urldecode(safeText($_POST["first_name"] . " " . $_POST["surname1"] . " " . $_POST["surname2"]));

  $pat = new Patient();

  $pat->setIdPatient($_POST["id_patient"]);

  require_once("../medical/patient_validate_post.php");

  // To header, without &amp;
  $returnLocation = "../medical/patient_view.php?key=" . $idPatient . "&reset=Y";

  ////////////////////////////////////////////////////////////////////
  // Prevent user from aborting script
  ////////////////////////////////////////////////////////////////////
  $oldAbort = ignore_user_abort(true);

  ////////////////////////////////////////////////////////////////////
  // Update patient
  ////////////////////////////////////////////////////////////////////
  $patQ = new Patient_Page_Query();
  $patQ->connect();
  if ($patQ->isError())
  {
    Error::query($patQ);
  }

  if ($patQ->existName($pat->getFirstName(), $pat->getSurname1(), $pat->getSurname2(), $pat->getIdPatient()))
  {
    $patQ->close();
    include_once("../shared/header.php");

    echo '<p>' . sprintf(_("Patient name, %s, is already in use. The changes have no effect."), $patName) . "</p>\n";

    echo '<p><a href="' . $returnLocation . '">' . _("Return to Patient Social Data") . "</a></p>\n";

    include_once("../shared/footer.php");
    exit();
  }

  $patQ->update($pat);
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
  recordLog($table, "UPDATE", array($idPatient));

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
  // Redirect to patient view to avoid reload problem
  ////////////////////////////////////////////////////////////////////
  header("Location: " . $returnLocation . "&updated=Y");
?>
