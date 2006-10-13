<?php
/**
 * history_family_view.php
 *
 * Family antecedents screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: history_family_view.php,v 1.16 2006/10/13 19:53:16 jact Exp $
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

  if ( !$historyQ->selectFamily($idPatient) )
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
  $title = _("View Family Antecedents");
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
      HTML::strLink(_("Edit Family Antecedents"), '../medical/history_family_edit_form.php',
        array('key' => $idPatient)
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
