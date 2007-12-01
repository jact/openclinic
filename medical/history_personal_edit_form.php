<?php
/**
 * history_personal_edit_form.php
 *
 * Edition screen of a patient personal antecedents
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: history_personal_edit_form.php,v 1.29 2007/12/01 12:13:48 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "history";
  $onlyDoctor = false;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Query/History.php");
  require_once("../model/Patient.php");

  /**
   * Retrieving var (PGS)
   */
  $idPatient = Check::postGetSessionInt('id_patient');

  $patient = new Patient($idPatient);
  if ($patient->getName() == '')
  {
    FlashMsg::add(_("That patient does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Search database
   */
  $historyQ = new Query_History();
  if ( !$historyQ->selectPersonal($idPatient) )
  {
    $historyQ->close();

    FlashMsg::add(_("That patient does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  $history = $historyQ->fetch();
  if ($history)
  {
    $formVar["id_patient"] = $history->getIdPatient();
    $formVar["birth_growth"] = $history->getBirthGrowth();
    $formVar["growth_sexuality"] = $history->getGrowthSexuality();
    $formVar["feed"] = $history->getFeed();
    $formVar["habits"] = $history->getHabits();
    $formVar["peristaltic_conditions"] = $history->getPeristalticConditions();
    $formVar["psychological"] = $history->getPsychological();
    $formVar["children_complaint"] = $history->getChildrenComplaint();
    $formVar["venereal_disease"] = $history->getVenerealDisease();
    $formVar["accident_surgical_operation"] = $history->getAccidentSurgicalOperation();
    $formVar["medicinal_intolerance"] = $history->getMedicinalIntolerance();
    $formVar["mental_illness"] = $history->getMentalIllness();
  }
  else
  {
    Error::fetch($historyQ, false);
  }
  $historyQ->freeResult();
  $historyQ->close();
  unset($historyQ);
  unset($history);

  /**
   * Show page
   */
  $title = _("Edit Personal Antecedents");
  $titlePage = $patient->getName() . ' (' . $title . ')';
  $focusFormField = "birth_growth"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");

  //$returnLocation = "../medical/history_personal_view.php?id_patient=" . $idPatient;
  $returnLocation = "../medical/history_personal_view.php";

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    _("Clinic History") => "../medical/history_list.php", //"?id_patient=" . $idPatient,
    _("View Personal Antecedents") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon icon_patient");
  unset($links);

  echo $patient->getHeader();

  Form::errorMsg();

  /**
   * Edit form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../medical/history_personal_edit.php'));

  Form::hidden("id_patient", $idPatient);

  require_once("../medical/history_personal_fields.php");

  HTML::end('form');

  HTML::para(HTML::strLink(_("Return"), $returnLocation));

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  require_once("../layout/footer.php");
?>
