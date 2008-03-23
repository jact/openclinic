<?php
/**
 * history_personal_view.php
 *
 * Personal antecedents screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: history_personal_view.php,v 1.26 2008/03/23 12:00:17 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "history";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_DOCTOR);

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
   * Search database for problem
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
  $titlePage = $patient->getName() . ' (' . $title . ')';
  require_once("../layout/header.php");

  /**
   * Breadcrumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    _("Clinic History") => "../medical/history_list.php", //"?id_patient=" . $idPatient,
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_patient");
  unset($links);

  echo $patient->getHeader();

  if ($_SESSION['auth']['is_administrative'])
  {
    echo HTML::para(
      HTML::link(_("Edit Personal Antecedents"), '../medical/history_personal_edit_form.php',
        array('id_patient' => $idPatient)
      )
    );
  }

  /**
   * Show personal antecedents
   */
  echo HTML::section(2, _("Personal Antecedents"));

  if ($history->getBirthGrowth())
  {
    echo HTML::section(3, _("Birth and Growth"));
    echo HTML::para(nl2br($history->getBirthGrowth()));
  }

  if ($history->getGrowthSexuality())
  {
    echo HTML::section(3, _("Growth and Sexuality"));
    echo HTML::para(nl2br($history->getGrowthSexuality()));
  }

  if ($history->getFeed())
  {
    echo HTML::section(3, _("Feed"));
    echo HTML::para(nl2br($history->getFeed()));
  }

  if ($history->getHabits())
  {
    echo HTML::section(3, _("Habits"));
    echo HTML::para(nl2br($history->getHabits()));
  }

  if ($history->getPeristalticConditions())
  {
    echo HTML::section(3, _("Peristaltic Conditions"));
    echo HTML::para(nl2br($history->getPeristalticConditions()));
  }

  if ($history->getPsychological())
  {
    echo HTML::section(3, _("Psychological Conditions"));
    echo HTML::para(nl2br($history->getPsychological()));
  }

  if ($history->getChildrenComplaint())
  {
    echo HTML::section(3, _("Children Complaint"));
    echo HTML::para(nl2br($history->getChildrenComplaint()));
  }

  if ($history->getVenerealDisease())
  {
    echo HTML::section(3, _("Venereal Disease"));
    echo HTML::para(nl2br($history->getVenerealDisease()));
  }

  if ($history->getAccidentSurgicalOperation())
  {
    echo HTML::section(3, _("Accidents and Surgical Operations"));
    echo HTML::para(nl2br($history->getAccidentSurgicalOperation()));
  }

  if ($history->getMedicinalIntolerance())
  {
    echo HTML::section(3, _("Medicinal Intolerance"));
    echo HTML::para(nl2br($history->getMedicinalIntolerance()));
  }

  if ($history->getMentalIllness())
  {
    echo HTML::section(3, _("Mental Illness"));
    echo HTML::para(nl2br($history->getMentalIllness()));
  }

  require_once("../layout/footer.php");
?>
