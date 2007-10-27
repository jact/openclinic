<?php
/**
 * patient_del_confirm.php
 *
 * Confirmation screen of a patient deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_del_confirm.php,v 1.22 2007/10/27 14:05:26 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "social";
  $onlyDoctor = false;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");
  require_once("../medical/PatientInfo.php");

  /**
   * Retrieving vars (PGS)
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
   * Show page
   */
  $title = _("Delete Patient");
  $titlePage = $patient->getName() . ' (' . $title . ')';
  require_once("../layout/header.php");

  //$returnLocation = "../medical/patient_view.php?id_patient=" . $idPatient; // controlling var
  $returnLocation = "../medical/patient_view.php"; // controlling var

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  /**
   * Confirm form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../medical/patient_del.php'));

  $tbody = array();

  $tbody[] = HTML::strMessage(sprintf(_("Are you sure you want to delete patient, %s?"), $patient->getName()), OPEN_MSG_WARNING, false);

  $row = Form::strHidden("id_patient", $idPatient);
  $row .= Form::strHidden("name", $patient->getName());
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("delete", _("Delete"))
    . Form::generateToken()
  );

  $options = array('class' => 'center');

  Form::fieldset($title, $tbody, $tfoot, $options);

  HTML::end('form');

  HTML::para(HTML::strLink(_("Return"), $returnLocation));

  require_once("../layout/footer.php");
?>
