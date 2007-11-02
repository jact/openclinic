<?php
/**
 * problem_del_confirm.php
 *
 * Confirmation screen of a medical problem deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_del_confirm.php,v 1.26 2007/11/02 22:54:03 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = false;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");
  require_once("../model/Patient.php");
  require_once("../model/Problem.php");

  /**
   * Retrieving vars (PGS)
   */
  $idProblem = Check::postGetSessionInt('id_problem');
  $idPatient = Check::postGetSessionInt('id_patient');

  $patient = new Patient($idPatient);
  if ($patient->getName() == '')
  {
    FlashMsg::add(_("That patient does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  $problem = new Problem($idProblem);
  if ( !$problem )
  {
    FlashMsg::add(_("That medical problem does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Show page
   */
  $title = _("Delete Medical Problem");
  $titlePage = $patient->getName() . ' [' . $problem->getWordingPreview() . '] (' . $title . ')';
  require_once("../layout/header.php");

  //$returnLocation = "../medical/problem_list.php?id_patient=" . $idPatient; // controlling var
  $returnLocation = "../medical/problem_list.php"; // controlling var

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    _("Medical Problems Report") => $returnLocation,
    $problem->getWordingPreview() => "../medical/problem_view.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  echo $patient->getHeader();

  /**
   * Confirm form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../medical/problem_del.php'));

  $tbody = array();

  $wording = $problem->getWordingPreview();
  $tbody[] = Msg::strWarning(sprintf(_("Are you sure you want to delete medical problem, %s, from list?"), $wording));

  $row = Form::strHidden("id_problem", $idProblem);
  $row .= Form::strHidden("id_patient", $idPatient);
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
