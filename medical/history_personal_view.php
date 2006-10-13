<?php
/**
 * history_personal_view.php
 *
 * Personal antecedents screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: history_personal_view.php,v 1.16 2006/10/13 19:53:16 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "history";
  $onlyDoctor = true;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/History_Query.php");

  /**
   * Retrieving get var
   */
  $idPatient = intval($_GET["key"]);

  /**
   * Search database for problem
   */
  $historyQ = new History_Query();
  $historyQ->connect();

  if ( !$historyQ->selectPersonal($idPatient) )
  {
    $historyQ->close();
    include_once("../layout/header.php");

    HTML::message(_("That patient does not exist."), OPEN_MSG_ERROR);

    include_once("../layout/footer.php");
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
  require_once("../medical/patient_header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Clinic History") => "../medical/history_list.php?key=" . $idPatient,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  showPatientHeader($idPatient);

  if ($hasMedicalAdminAuth)
  {
    HTML::para(
      HTML::strLink(_("Edit Personal Antecedents"), '../medical/history_personal_edit_form.php',
        array('key' => $idPatient)
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
