<?php
/**
 * patient_edit.php
 *
 * Patient edition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_edit.php,v 1.17 2006/10/13 19:53:16 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0 || !is_numeric($_POST["id_patient"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $onlyDoctor = false;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Patient_Page_Query.php");
  require_once("../shared/record_log.php"); // record log

  /**
   * Validate data
   */
  $idPatient = intval($_POST["id_patient"]);
  $errorLocation = "../medical/patient_edit_form.php?key=" . $idPatient; // controlling var
  $patName = urldecode(Check::safeText($_POST["first_name"] . " " . $_POST["surname1"] . " " . $_POST["surname2"]));

  $pat = new Patient();

  $pat->setIdPatient($_POST["id_patient"]);

  require_once("../medical/patient_validate_post.php");

  $returnLocation = "../medical/patient_view.php?key=" . $idPatient; // controlling var

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Update patient
   */
  $patQ = new Patient_Page_Query();
  $patQ->connect();

  if ($patQ->existName($pat->getFirstName(), $pat->getSurname1(), $pat->getSurname2(), $pat->getIdPatient()))
  {
    $patQ->close();
    include_once("../layout/header.php");

    HTML::message(sprintf(_("Patient name, %s, is already in use. The changes have no effect."), $patName), OPEN_MSG_INFO);

    HTML::para(HTML::strLink(_("Return to Patient Social Data"), $returnLocation));

    include_once("../layout/footer.php");
    exit();
  }

  $patQ->update($pat);

  $patQ->close();
  unset($patQ);
  unset($pat);

  /**
   * Record log process
   */
  recordLog("Patient_Page_Query", "UPDATE", array($idPatient));

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  header("Location: " . $returnLocation . "&updated=Y");
?>
