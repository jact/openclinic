<?php
/**
 * patient_new.php
 *
 * Patient addition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_new.php,v 1.19 2007/10/28 11:31:41 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $onlyDoctor = false;
  $errorLocation = "../medical/patient_new_form.php";

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: " . $errorLocation);
    exit();
  }

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Patient_Page_Query.php");
  require_once("../shared/record_log.php"); // record log

  /**
   * Validate data
   */
  $pat = new Patient();

  require_once("../medical/patient_validate_post.php");

  $patName = $pat->getFirstName() . ' ' . $pat->getSurname1() . ' ' . $pat->getSurname2();

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Insert new patient
   */
  $patQ = new Patient_Page_Query();
  $patQ->connect();

  if ($patQ->existName($pat->getFirstName(), $pat->getSurname1(), $pat->getSurname2()))
  {
    $patQ->close();

    FlashMsg::add(sprintf(_("Patient name, %s, is already in use. The changes have no effect."), $patName),
      OPEN_MSG_WARNING
    );
    header("Location: ../medical/patient_new_form.php");
    exit();
  }

  $patQ->insert($pat);
  $idPatient = $patQ->getLastId();

  $patQ->close();
  unset($patQ);
  unset($pat);

  /**
   * Record log process
   */
  recordLog("Patient_Page_Query", "INSERT", array($idPatient));

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  FlashMsg::add(_("Patient has been added."));
  //$returnLocation = "../medical/patient_view.php?id_patient=" . $idPatient;
  $returnLocation = "../medical/patient_view.php";
  header("Location: " . $returnLocation);
?>
