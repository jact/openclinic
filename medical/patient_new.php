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
 * @version   CVS: $Id: patient_new.php,v 1.25 2007/12/15 15:05:01 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $errorLocation = "../medical/patient_new_form.php";

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: " . $errorLocation);
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
  $patQ = new Query_Page_Patient();
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
  $recordQ = new Query_Page_Record();
  $recordQ->log("Query_Page_Patient", "INSERT", array($idPatient));
  $recordQ->close();
  unset($recordQ);

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  FlashMsg::add(_("Patient has been added."));
  $returnLocation = "../medical/patient_view.php?id_patient=" . $idPatient;
  header("Location: " . $returnLocation);
?>
