<?php
/**
 * test_del_confirm.php
 *
 * Confirmation screen of a medical test deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: test_del_confirm.php,v 1.32 2008/03/23 12:00:18 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "problems";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE, false); // Not in DEMO to prevent users' malice

  require_once("../lib/Form.php");
  require_once("../lib/Check.php");
  require_once("../model/Patient.php");
  require_once("../model/Problem.php");
  require_once("../model/Test.php");

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

  $problem = new Problem($idProblem);
  if ( !$problem )
  {
    FlashMsg::add(_("That medical problem does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  $test = new Test($idProblem, $idTest);
  if ( !$test )
  {
    FlashMsg::add(_("That medical test does not exist"), OPEN_MSG_ERROR);
    header("Location: ../medical/test_list.php");
    exit();
  }

  /**
   * Show page
   */
  $title = _("Delete Medical Test");
  $titlePage = $patient->getName() . ' [' . $problem->getWordingPreview() . '] (' . $title . ')';
  require_once("../layout/header.php");

  //$returnLocation = "../medical/test_list.php?id_problem=" . $idProblem . "&id_patient=" . $idPatient; // controlling var
  $returnLocation = "../medical/test_list.php"; // controlling var

  /**
   * Breadcrumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    _("Medical Problems Report") => "../medical/problem_list.php", //"?id_patient=" . $idPatient,
    $problem->getWordingPreview() => "../medical/problem_view.php",
    _("View Medical Tests") => $returnLocation,
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_patient");
  unset($links);

  echo $patient->getHeader();
  echo $problem->getHeader();

  /**
   * Confirm form
   */
  echo HTML::start('form', array('method' => 'post', 'action' => '../medical/test_del.php'));

  $tbody = array();

  $tbody[] = Msg::warning(sprintf(_("Are you sure you want to delete medical test, %s, from list?"),
    $test->getPathFilename())
  );

  $row = Form::hidden("id_problem", $idProblem);
  $row .= Form::hidden("id_test", $idTest);
  $row .= Form::hidden("id_patient", $idPatient);
  $row .= Form::hidden("path_filename", $test->getPathFilename());
  $tbody[] = $row;

  $tfoot = array(
    Form::button("delete", _("Delete"))
    . Form::generateToken()
  );

  $options = array('class' => 'center');

  echo Form::fieldset($title, $tbody, $tfoot, $options);

  echo HTML::end('form');

  echo HTML::para(HTML::link(_("Return"), $returnLocation));

  require_once("../layout/footer.php");
?>
