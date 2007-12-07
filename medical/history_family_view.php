<?php
/**
 * history_family_view.php
 *
 * Family antecedents screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: history_family_view.php,v 1.24 2007/12/07 16:51:44 jact Exp $
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
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE);

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
  if ( !$historyQ->selectFamily($idPatient) )
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
  $title = _("View Family Antecedents");
  $titlePage = $patient->getName() . ' (' . $title . ')';
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
  HTML::breadCrumb($links, "icon icon_patient");
  unset($links);

  echo $patient->getHeader();

  if ($_SESSION['auth']['is_medical_doctor'])
  {
    HTML::para(
      HTML::strLink(_("Edit Family Antecedents"), '../medical/history_family_edit_form.php',
        array('id_patient' => $idPatient)
      )
    );
  }

  /**
   * Show family antecedents
   */
  HTML::section(2, _("Family Antecedents"));

  if ($history->getParentsStatusHealth())
  {
    HTML::section(3, _("Parents Status Health"));
    HTML::para(nl2br($history->getParentsStatusHealth()));
  }

  if ($history->getBrothersStatusHealth())
  {
    HTML::section(3, _("Brothers and Sisters Status Health"));
    HTML::para(nl2br($history->getBrothersStatusHealth()));
  }

  if ($history->getSpouseChildsStatusHealth())
  {
    HTML::section(3, _("Spouse and Childs Status Health"));
    HTML::para(nl2br($history->getSpouseChildsStatusHealth()));
  }

  if ($history->getFamilyIllness())
  {
    HTML::section(3, _("Family Illness"));
    HTML::para(nl2br($history->getFamilyIllness()));
  }

  require_once("../layout/footer.php");
?>
