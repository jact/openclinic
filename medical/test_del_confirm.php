<?php
/**
 * test_del_confirm.php
 *
 * Confirmation screen of a medical test deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: test_del_confirm.php,v 1.26 2007/11/02 22:21:06 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = false;
  $restrictInDemo = true; // To prevent users' malice

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");
  require_once("../model/Patient.php");
  require_once("../lib/ProblemInfo.php");
  require_once("../lib/TestInfo.php");

  /**
   * Retrieving vars (PGS)
   */
  $idProblem = Check::postGetSessionInt('id_problem');
  $idPatient = Check::postGetSessionInt('id_patient');
  $idTest = Check::postGetSessionInt('id_test');

  $patient = new Patient($idPatient);
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

  $test = new TestInfo($idProblem, $idTest);
  $test = $test->getObject();
  if ($test == null)
  {
    FlashMsg::add(_("That medical test does not exist"), OPEN_MSG_ERROR);
    header("Location: ../medical/test_list.php");
    exit();
  }

  /**
   * Show page
   */
  $title = _("Delete Medical Test");
  $titlePage = $patient->getName() . ' [' . $problem->getWording() . '] (' . $title . ')';
  require_once("../layout/header.php");

  //$returnLocation = "../medical/test_list.php?id_problem=" . $idProblem . "&id_patient=" . $idPatient; // controlling var
  $returnLocation = "../medical/test_list.php"; // controlling var

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    _("Medical Problems Report") => "../medical/problem_list.php", //"?id_patient=" . $idPatient,
    $problem->getWording() => "../medical/problem_view.php",
    _("View Medical Tests") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  echo $patient->getHeader();
  $problem->showHeader();

  /**
   * Confirm form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../medical/test_del.php'));

  $tbody = array();

  $tbody[] = Msg::strWarning(sprintf(_("Are you sure you want to delete medical test, %s, from list?"),
    $test->getPathFilename())
  );

  $row = Form::strHidden("id_problem", $idProblem);
  $row .= Form::strHidden("id_test", $idTest);
  $row .= Form::strHidden("id_patient", $idPatient);
  $row .= Form::strHidden("path_filename", $test->getPathFilename());
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
