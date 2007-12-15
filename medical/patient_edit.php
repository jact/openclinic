<?php
/**
 * patient_edit.php
 *
 * Patient edition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_edit.php,v 1.25 2007/12/15 15:05:01 jact Exp $
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
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE);

  require_once("../model/Query/Page/Patient.php");
  require_once("../model/Query/Page/Record.php");

  /**
   * Validate data
   */
  $idPatient = intval($_POST["id_patient"]);
  $errorLocation = "../medical/patient_edit_form.php?key=" . $idPatient; // controlling var
  $patName = urldecode(Check::safeText($_POST["first_name"] . " " . $_POST["surname1"] . " " . $_POST["surname2"]));

  $pat = new Patient();

  $pat->setIdPatient($_POST["id_patient"]);

  require_once("../medical/patient_validate_post.php");

  //$returnLocation = "../medical/patient_view.php?id_patient=" . $idPatient; // controlling var
  $returnLocation = "../medical/patient_view.php"; // controlling var

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Update patient
   */
  $patQ = new Query_Page_Patient();
  if ($patQ->existName($pat->getFirstName(), $pat->getSurname1(), $pat->getSurname2(), $pat->getIdPatient()))
  {
    $patQ->close();

    FlashMsg::add(sprintf(_("Patient name, %s, is already in use. The changes have no effect."), $patName),
      OPEN_MSG_WARNING
    );
    header("Location: " . $returnLocation);
    exit();
  }

  $patQ->update($pat);

  $patQ->close();
  unset($patQ);
  unset($pat);

  /**
   * Record log process
   */
  $recordQ = new Query_Page_Record();
  $recordQ->log("Query_Page_Patient", "UPDATE", array($idPatient));
  $recordQ->close();
  unset($recordQ);

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  FlashMsg::add(_("Patient has been updated."));
  header("Location: " . $returnLocation);
?>
