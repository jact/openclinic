<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: relative_new.php,v 1.8 2005/08/15 14:30:55 jact Exp $
 */

/**
 * relative_new.php
 *
 * Relation between patients addition process
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: ../medical/patient_new_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Relative_Query.php");
  require_once("../shared/record_log.php"); // record log

  /**
   * Retrieving post var
   */
  $idPatient = intval($_POST["id_patient"]);

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Insert new relatives patient
   */
  $relQ = new Relative_Query();
  $relQ->connect();
  if ($relQ->isError())
  {
    Error::query($relQ);
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
        Error::query($relQ);
      }
    }
    else
    {
      /**
       * Record log process
       */
      recordLog("Relative_Query", "INSERT", array($idPatient, $_POST["check"][$i]));
    }
  }
  $relQ->close();
  unset($relQ);

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  $returnLocation = "../medical/relative_list.php?key=" . $idPatient; // controlling var

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  header("Location: " . $returnLocation . "&added=Y");
?>
