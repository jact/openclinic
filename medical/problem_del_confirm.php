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
 * @version   CVS: $Id: problem_del_confirm.php,v 1.21 2007/10/26 21:44:44 jact Exp $
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
  require_once("../medical/PatientInfo.php");
  require_once("../medical/ProblemInfo.php");

  /**
   * Retrieving vars (PGS)
   */
  $idProblem = Check::postGetSessionInt('id_problem');
  $idPatient = Check::postGetSessionInt('id_patient');

  $patient = new PatientInfo($idPatient);
  if ($patient->getName() == '')
  {
    FlashMsg::add(_("That patient does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  $problem = new ProblemInfo($idProblem);
  if ($problem->getWording() == '')
  {
    FlashMsg::add(_("That medical problem does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Show page
   */
  $title = _("Delete Medical Problem");
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
    $problem->getWording() => "../medical/problem_view.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  $patient->showHeader();

  /**
   * Confirm form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../medical/problem_del.php'));

  $tbody = array();

  $wording = $problem->getObject();
  $wording = $wording->getWording();
  $tbody[] = HTML::strMessage(sprintf(_("Are you sure you want to delete medical problem, %s, from list?"), $wording), OPEN_MSG_WARNING, false);

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
