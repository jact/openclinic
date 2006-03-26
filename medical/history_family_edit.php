<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_family_edit.php,v 1.10 2006/03/26 15:13:08 jact Exp $
 */

/**
 * history_family_edit.php
 *
 * Family antecedents of a patient edition process
 *
 * @author jact <jachavar@gmail.com>
 */

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "history";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/History_Query.php");
  require_once("../shared/record_log.php"); // record log

  /**
   * Retrieving post var
   */
  $idPatient = intval($_POST["id_patient"]);

  /**
   * Validate data
   */
  $history = new History();

  $history->setIdPatient($_POST["id_patient"]);

  $history->setParentsStatusHealth($_POST["parents_status_health"]);
  $_POST["parents_status_health"] = $history->getParentsStatusHealth();

  $history->setBrothersStatusHealth($_POST["brothers_status_health"]);
  $_POST["brothers_status_health"] = $history->getBrothersStatusHealth();

  $history->setSpouseChildsStatusHealth($_POST["spouse_childs_status_health"]);
  $_POST["spouse_childs_status_health"] = $history->getSpouseChildsStatusHealth();

  $history->setFamilyIllness($_POST["family_illness"]);
  $_POST["family_illness"] = $history->getFamilyIllness();

  if ( !$history->validateData() )
  {
    //$formError["???"] = $history->get???Error();
    //$formError["???"] = $history->get???Error();

    $_SESSION["formVar"] = $_POST;
    //$_SESSION["formError"] = $formErrors;

    header("Location: ../medical/history_family_edit_form.php?key=" . $idPatient);
    exit();
  }

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Update family antecedents
   */
  $historyQ = new History_Query();
  $historyQ->connect();

  $historyQ->updateFamily($history);

  $historyQ->close();
  unset($historyQ);

  /**
   * Record log process
   */
  recordLog("History_Query", "UPDATE", array($idPatient), "selectFamily");

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  header("Location: ../medical/history_family_edit_form.php?key=" . $idPatient . "&updated=Y");
?>
