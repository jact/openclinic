<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: connection_new.php,v 1.3 2004/07/07 17:22:28 jact Exp $
 */

/**
 * connection_new.php
 ********************************************************************
 * Connection between medical problems addition process
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
  $nav = "problems";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Connection_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../shared/record_log.php"); // record log

  ////////////////////////////////////////////////////////////////////
  // Retrieving post vars
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_POST["id_patient"]);
  $idProblem = intval($_POST["id_problem"]);

  ////////////////////////////////////////////////////////////////////
  // Prevent user from aborting script
  ////////////////////////////////////////////////////////////////////
  $oldAbort = ignore_user_abort(true);

  ////////////////////////////////////////////////////////////////////
  // Insert new connection problem
  ////////////////////////////////////////////////////////////////////
  $connQ = new Connection_Query();
  $connQ->connect();
  if ($connQ->isError())
  {
    showQueryError($connQ);
  }

  $n = count($_POST["check"]);
  for ($i = 0; $i < $n; $i++)
  {
    if ($idProblem == $_POST["check"][$i])
    {
      continue; // a problem can't be connection of himself
    }

    $connQ->insert($idProblem, $_POST["check"][$i]);
    if ($connQ->isError())
    {
      if ($connQ->getDbErrno() == 1062) // duplicated key
      {
        $connQ->clearErrors();
      }
      else
      {
        $connQ->close();
        showQueryError($connQ);
      }
    }
    ////////////////////////////////////////////////////////////////////
    // Record log process
    ////////////////////////////////////////////////////////////////////
    recordLog("connection_problem_tbl", "INSERT", $idProblem, $_POST["check"][$i]);
  }
  $connQ->close();
  unset($connQ);

  ////////////////////////////////////////////////////////////////////
  // Reset abort setting
  ////////////////////////////////////////////////////////////////////
  ignore_user_abort($oldAbort);

  ////////////////////////////////////////////////////////////////////
  // Show success page
  ////////////////////////////////////////////////////////////////////
  $title = _("Add New Connection Problems");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");
  require_once("../medical/problem_header.php");

  $returnLocation = "../medical/connection_list.php?key=" . $idProblem . "&amp;pat=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Medical Problems Report") => "../medical/problem_list.php?key=" . $idPatient,
    _("View Medical Problem") => "../medical/problem_view.php?key=" . $idProblem . "&amp;pat=" . $idPatient,
    _("View Connection Problems") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  showPatientHeader($idPatient);
  showProblemHeader($idProblem);

  echo '<p>' . _("Connection problems have been added.") . "</p>\n";

  echo '<p><a href="' . $returnLocation . '">' . _("Return to connection problem list") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
