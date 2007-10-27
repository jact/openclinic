<?php
/**
 * connection_del_confirm.php
 *
 * Confirmation screen of a connection between medical problems deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: connection_del_confirm.php,v 1.23 2007/10/27 17:32:53 jact Exp $
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
  require_once("../lib/PatientInfo.php");
  require_once("../lib/ProblemInfo.php");

  /**
   * Retrieving vars (PGS)
   */
  $idProblem = Check::postGetSessionInt('id_problem');
  $idPatient = Check::postGetSessionInt('id_patient');
  $idConnection = Check::postGetSessionInt('id_connection');

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
  $title = _("Delete Connection with Medical Problem");
  $titlePage = $patient->getName() . ' [' . $problem->getWording() . '] (' . $title . ')';
  require_once("../layout/header.php");

  //$returnLocation = "../medical/connection_list.php?id_problem=" . $idProblem . "&id_patient=" . $idPatient; // controlling var
  $returnLocation = "../medical/connection_list.php"; // controlling var

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    _("Medical Problems Report") => "../medical/problem_list.php", //"?id_patient=" . $idPatient,
    $problem->getWording() => "../medical/problem_view.php",
    _("View Connection Problems") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  $patient->showHeader();
  $problem->showHeader();

  /**
   * Form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../medical/connection_del.php'));

  $tbody = array();

  $problem = new ProblemInfo($idConnection);
  $wording = $problem->getObject();
  $wording = $wording->getWording();
  $tbody[] = Msg::strWarning(sprintf(_("Are you sure you want to delete connection, %s, from list?"), $wording));

  $row = Form::strHidden("id_problem", $idProblem);
  $row .= Form::strHidden("id_connection", $idConnection);
  $row .= Form::strHidden("id_patient", $idPatient);
  $row .= Form::strHidden("wording", $wording);
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
