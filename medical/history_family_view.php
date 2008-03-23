<?php
/**
 * history_family_view.php
 *
 * Family antecedents screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: history_family_view.php,v 1.26 2008/03/23 12:00:17 jact Exp $
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
      HTML::link(_("Edit Family Antecedents"), '../medical/history_family_edit_form.php',
        array('id_patient' => $idPatient)
      )
    );
  }

  /**
   * Show family antecedents
   */
  echo HTML::section(2, _("Family Antecedents"));

  if ($history->getParentsStatusHealth())
  {
    echo HTML::section(3, _("Parents Status Health"));
    echo HTML::para(nl2br($history->getParentsStatusHealth()));
  }

  if ($history->getBrothersStatusHealth())
  {
    echo HTML::section(3, _("Brothers and Sisters Status Health"));
    echo HTML::para(nl2br($history->getBrothersStatusHealth()));
  }

  if ($history->getSpouseChildsStatusHealth())
  {
    echo HTML::section(3, _("Spouse and Childs Status Health"));
    echo HTML::para(nl2br($history->getSpouseChildsStatusHealth()));
  }

  if ($history->getFamilyIllness())
  {
    echo HTML::section(3, _("Family Illness"));
    echo HTML::para(nl2br($history->getFamilyIllness()));
  }

  require_once("../layout/footer.php");
?>
