<?php
/**
 * history_personal_view.php
 *
 * Personal antecedents screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: history_personal_view.php,v 1.17 2007/10/26 22:06:15 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "history";
  $onlyDoctor = true;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/History_Query.php");
  require_once("../medical/PatientInfo.php");

  /**
   * Retrieving var (PGS)
   */
  $idPatient = Check::postGetSessionInt('id_patient');

  $patient = new PatientInfo($idPatient);
  if ($patient->getName() == '')
  {
    FlashMsg::add(_("That patient does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Search database for problem
   */
  $historyQ = new History_Query();
  $historyQ->connect();

  if ( !$historyQ->selectPersonal($idPatient) )
  {
    $historyQ->close();

    FlashMsg::add(_("That patient does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  $history = $historyQ->fetch();
  if ( !$history )
  {
    $historyQ->close();
    Error::fetch($historyQ);
  }

  $historyQ->freeResult();
  $historyQ->close();
  unset($historyQ);

  /**
   * Show page
   */
  $title = _("View Personal Antecedents");
  require_once("../layout/header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    _("Clinic History") => "../medical/history_list.php", //"?id_patient=" . $idPatient,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  $patient->showHeader();

  if ($hasMedicalAdminAuth)
  {
    HTML::para(
      HTML::strLink(_("Edit Personal Antecedents"), '../medical/history_personal_edit_form.php',
        array('id_patient' => $idPatient)
      )
    );
  }

  /**
   * Show personal antecedents
   */
  HTML::section(2, _("Personal Antecedents"));

  if ($history->getBirthGrowth())
  {
    HTML::section(3, _("Birth and Growth"));
    HTML::para(nl2br($history->getBirthGrowth()));
  }

  if ($history->getGrowthSexuality())
  {
    HTML::section(3, _("Growth and Sexuality"));
    HTML::para(nl2br($history->getGrowthSexuality()));
  }

  if ($history->getFeed())
  {
    HTML::section(3, _("Feed"));
    HTML::para(nl2br($history->getFeed()));
  }

  if ($history->getHabits())
  {
    HTML::section(3, _("Habits"));
    HTML::para(nl2br($history->getHabits()));
  }

  if ($history->getPeristalticConditions())
  {
    HTML::section(3, _("Peristaltic Conditions"));
    HTML::para(nl2br($history->getPeristalticConditions()));
  }

  if ($history->getPsychological())
  {
    HTML::section(3, _("Psychological Conditions"));
    HTML::para(nl2br($history->getPsychological()));
  }

  if ($history->getChildrenComplaint())
  {
    HTML::section(3, _("Children Complaint"));
    HTML::para(nl2br($history->getChildrenComplaint()));
  }

  if ($history->getVenerealDisease())
  {
    HTML::section(3, _("Venereal Disease"));
    HTML::para(nl2br($history->getVenerealDisease()));
  }

  if ($history->getAccidentSurgicalOperation())
  {
    HTML::section(3, _("Accidents and Surgical Operations"));
    HTML::para(nl2br($history->getAccidentSurgicalOperation()));
  }

  if ($history->getMedicinalIntolerance())
  {
    HTML::section(3, _("Medicinal Intolerance"));
    HTML::para(nl2br($history->getMedicinalIntolerance()));
  }

  if ($history->getMentalIllness())
  {
    HTML::section(3, _("Mental Illness"));
    HTML::para(nl2br($history->getMentalIllness()));
  }

  require_once("../layout/footer.php");
?>
