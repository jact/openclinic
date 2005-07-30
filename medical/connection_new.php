<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: connection_new.php,v 1.7 2005/07/30 15:10:25 jact Exp $
 */

/**
 * connection_new.php
 *
 * Connection between medical problems addition process
 *
 * Author: jact <jachavar@gmail.com>
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
    Error::query($connQ);
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
        Error::query($connQ);
      }
    }
    else
    {
      ////////////////////////////////////////////////////////////////////
      // Record log process
      ////////////////////////////////////////////////////////////////////
      recordLog("Connection_Query", "INSERT", array($idProblem, $_POST["check"][$i]));
    }
  }
  $connQ->close();
  unset($connQ);

  ////////////////////////////////////////////////////////////////////
  // Reset abort setting
  ////////////////////////////////////////////////////////////////////
  ignore_user_abort($oldAbort);

  // To header, without &amp;
  $returnLocation = "../medical/connection_list.php?key=" . $idProblem . "&pat=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Redirect to connection problem list to avoid reload problem
  ////////////////////////////////////////////////////////////////////
  header("Location: " . $returnLocation . "&added=Y");
?>
