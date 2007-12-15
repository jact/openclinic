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
 * @version   CVS: $Id: relative_new.php,v 1.20 2007/12/15 15:05:02 jact Exp $
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
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE);

  require_once("../lib/Form.php");

  Form::compareToken('../medical/patient_new_form.php');

  require_once("../model/Query/Relative.php");
  require_once("../model/Query/Page/Record.php");

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
  $relQ = new Query_Relative();
  $relQ->captureError(true);
  $recordQ = new Query_Page_Record();

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
      $recordQ->log("Query_Relative", "INSERT", array($idPatient, $_POST["check"][$i]));
    }
  }
  $recordQ->close();
  unset($recordQ);
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
