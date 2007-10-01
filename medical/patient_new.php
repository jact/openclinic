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
 * @version   CVS: $Id: patient_new.php,v 1.16 2007/10/01 20:00:08 jact Exp $
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
    include_once("../layout/header.php");

    HTML::message(sprintf(_("Patient name, %s, is already in use. The changes have no effect."), $patName), OPEN_MSG_INFO);

    include_once("../layout/footer.php");
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
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  $returnLocation = "../medical/patient_view.php?key=" . $idPatient;

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  header("Location: " . $returnLocation . "&added=Y");
?>
