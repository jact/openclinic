<?php
/**
 * history_personal_edit.php
 *
 * Personal antecedents of a patient edition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: history_personal_edit.php,v 1.22 2007/12/15 15:05:01 jact Exp $
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

  $history->setBirthGrowth($_POST["birth_growth"]);
  $_POST["birth_growth"] = $history->getBirthGrowth();

  $history->setGrowthSexuality($_POST["growth_sexuality"]);
  $_POST["growth_sexuality"] = $history->getGrowthSexuality();

  $history->setFeed($_POST["feed"]);
  $_POST["feed"] = $history->getFeed();

  $history->setHabits($_POST["habits"]);
  $_POST["habits"] = $history->getHabits();

  $history->setPeristalticConditions($_POST["peristaltic_conditions"]);
  $_POST["peristaltic_conditions"] = $history->getPeristalticConditions();

  $history->setPsychological($_POST["psychological"]);
  $_POST["psychological"] = $history->getPsychological();

  $history->setChildrenComplaint($_POST["children_complaint"]);
  $_POST["children_complaint"] = $history->getChildrenComplaint();

  $history->setVenerealDisease($_POST["venereal_disease"]);
  $_POST["venereal_disease"] = $history->getVenerealDisease();

  $history->setAccidentSurgicalOperation($_POST["accident_surgical_operation"]);
  $_POST["accident_surgical_operation"] = $history->getAccidentSurgicalOperation();

  $history->setMedicinalIntolerance($_POST["medicinal_intolerance"]);
  $_POST["medicinal_intolerance"] = $history->getMedicinalIntolerance();

  $history->setMentalIllness($_POST["mental_illness"]);
  $_POST["mental_illness"] = $history->getMentalIllness();

  if ( !$history->validateData() )
  {
    //$formError["???"] = $history->get???Error();
    //$formError["???"] = $history->get???Error();

    Form::setSession($_POST/*, $formError*/);

    //header("Location: ../medical/history_personal_edit_form.php?id_patient=" . $idPatient);
    header("Location: ../medical/history_personal_edit_form.php");
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
   * Update personal antecedents
   */
  $historyQ = new Query_History();
  $historyQ->updatePersonal($history);

  $historyQ->close();
  unset($historyQ);

  /**
   * Record log process
   */
  $recordQ = new Query_Page_Record();
  $recordQ->log("Query_History", "UPDATE", array($idPatient), "selectPersonal");
  $recordQ->close();
  unset($recordQ);

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Redirect to destiny to avoid reload problem
   */
  FlashMsg::add(_("Personal Antecedents have been updated."));
  //header("Location: ../medical/history_personal_view.php?id_patient=" . $idPatient);
  header("Location: ../medical/history_personal_view.php");
?>
