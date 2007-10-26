<?php
/**
 * relative_new.php
 *
 * Relation between patients addition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: relative_new.php,v 1.15 2007/10/26 21:33:14 jact Exp $
 * @author    jact <jachavar@gmail.com>
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

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/Form.php");

  Form::compareToken('../medical/patient_new_form.php');

  require_once("../model/Relative_Query.php");
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
  $relQ->captureError(true);

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

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  FlashMsg::add(_("Relatives have been added."));
  //$returnLocation = "../medical/relative_list.php?id_patient=" . $idPatient; // controlling var
  $returnLocation = "../medical/relative_list.php"; // controlling var
  header("Location: " . $returnLocation);
?>
