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
 * @version   CVS: $Id: patient_del_confirm.php,v 1.28 2007/12/15 15:05:01 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "social";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE);

  require_once("../lib/Form.php");
  require_once("../lib/Check.php");
  require_once("../model/Patient.php");

  /**
   * Retrieving vars (PGS)
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
  HTML::breadCrumb($links, "icon icon_patient");
  unset($links);

  /**
   * Confirm form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../medical/patient_del.php'));

  $tbody = array();

  $tbody[] = Msg::strWarning(sprintf(_("Are you sure you want to delete patient, %s?"), $patient->getName()));

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
