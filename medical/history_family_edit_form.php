<?php
/**
 * history_family_edit_form.php
 *
 * Edition screen of a patient family antecedents
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: history_family_edit_form.php,v 1.17 2006/10/13 19:53:16 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "history";
  $onlyDoctor = false;

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/History_Query.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError

  /**
   * Retrieving get vars
   */
  $idPatient = intval($_GET["key"]);

  /**
   * Search database
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
  if ($history)
  {
    $formVar["id_patient"] = $history->getIdPatient();
    $formVar["parents_status_health"] = $history->getParentsStatusHealth();
    $formVar["brothers_status_health"] = $history->getBrothersStatusHealth();
    $formVar["spouse_childs_status_health"] = $history->getSpouseChildsStatusHealth();
    $formVar["family_illness"] = $history->getFamilyIllness();
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
  $title = _("Edit Family Antecedents");
  $focusFormField = "parents_status_health"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");
  require_once("../medical/patient_header.php");

  $returnLocation = "../medical/history_family_view.php?key=" . $idPatient;

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Clinic History") => "../medical/history_list.php?key=" . $idPatient,
    _("View Family Antecedents") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  showPatientHeader($idPatient);

  require_once("../shared/form_errors_msg.php");

  /**
   * Display update message if coming from setting_edit with a successful update.
   */
  if (isset($_GET["updated"]))
  {
    HTML::message(_("Family Antecedents have been updated."), OPEN_MSG_INFO);
  }

  /**
   * Edit form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../medical/history_family_edit.php'));

  Form::hidden("id_patient", $idPatient);

  require_once("../medical/history_family_fields.php");

  HTML::end('form');

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  require_once("../layout/footer.php");
?>
