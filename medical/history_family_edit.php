<?php
/**
 * history_family_edit.php
 *
 * Family antecedents of a patient edition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: history_family_edit.php,v 1.21 2007/12/15 15:05:01 jact Exp $
 * @author    jact <jachavar@gmail.com>
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

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE);

  require_once("../lib/Form.php");

  Form::compareToken('../medical/patient_search_form.php');

  require_once("../model/Query/History.php");
  require_once("../model/Query/Page/Record.php");

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

    Form::setSession($_POST/*, $formError*/);

    //header("Location: ../medical/history_family_edit_form.php?id_patient=" . $idPatient);
    header("Location: ../medical/history_family_edit_form.php");
    exit();
  }

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Update family antecedents
   */
  $historyQ = new Query_History();

  $historyQ->updateFamily($history);

  $historyQ->close();
  unset($historyQ);

  /**
   * Record log process
   */
  $recordQ = new Query_Page_Record();
  $recordQ->log("Query_History", "UPDATE", array($idPatient), "selectFamily");
  $recordQ->close();
  unset($recordQ);

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Redirect to destiny to avoid reload problem
   */
  FlashMsg::add(_("Family Antecedents have been updated."));
  //header("Location: ../medical/history_family_view.php?id_patient=" . $idPatient);
  header("Location: ../medical/history_family_view.php");
?>
