<?php // add patient header?
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: relative_new.php,v 1.3 2004/07/07 17:23:21 jact Exp $
 */

/**
 * relative_new.php
 ********************************************************************
 * Relation between patients addition process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: ../medical/patient_new_form.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "social";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Relative_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../shared/record_log.php"); // record log

  ////////////////////////////////////////////////////////////////////
  // Retrieving post var
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_POST["id_patient"]);

  ////////////////////////////////////////////////////////////////////
  // Prevent user from aborting script
  ////////////////////////////////////////////////////////////////////
  $oldAbort = ignore_user_abort(true);

  ////////////////////////////////////////////////////////////////////
  // Insert new relatives patient
  ////////////////////////////////////////////////////////////////////
  $relQ = new Relative_Query();
  $relQ->connect();
  if ($relQ->isError())
  {
    showQueryError($relQ);
  }

  $n = count($_POST["check"]);
  for ($i = 0; $i < $n; $i++)
  {
    if ($idPatient == $_POST["check"][$i])
    {
      continue; // a patient can't be relative of himself
    }

    $relQ->insert($idPatient, $_POST["check"][$i]);
    if ($relQ->isError())
    {
      if ($relQ->getDbErrno() == 1062) // duplicated key
      {
        $relQ->clearErrors();
      }
      else
      {
        $relQ->close();
        showQueryError($relQ);
      }
    }
    ////////////////////////////////////////////////////////////////////
    // Record log process
    ////////////////////////////////////////////////////////////////////
    recordLog("relative_tbl", "INSERT", $idPatient, $_POST["check"][$i]);
  }
  $relQ->close();
  unset($relQ);

  ////////////////////////////////////////////////////////////////////
  // Reset abort setting
  ////////////////////////////////////////////////////////////////////
  ignore_user_abort($oldAbort);

  ////////////////////////////////////////////////////////////////////
  // Show success page
  ////////////////////////////////////////////////////////////////////
  $title = _("New Relatives");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  $returnLocation = "../medical/relative_list.php?key=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Social Data") => "../medical/patient_view.php?key=" . $idPatient,
    _("View Relatives") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  showPatientHeader($idPatient);

  echo '<p>' . _("Relatives have been added.") . "</p>\n";

  echo '<p><a href="' . $returnLocation . '">' . _("Return to Relatives Patient List") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
