<?php
/**
 * test_list.php
 *
 * Medical tests screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: test_list.php,v 1.37 2008/03/23 12:00:18 jact Exp $
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
  loginCheck(OPEN_PROFILE_DOCTOR, false); // Not in DEMO to prevent users' malice

  require_once("../model/Query/Test.php");
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
  $title = _("View Medical Tests");
  $titlePage = $patient->getName() . ' [' . $problem->getWordingPreview() . '] (' . $title . ')';
  require_once("../layout/header.php");

  /**
   * Breadcrumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    _("Medical Problems Report") => "../medical/problem_list.php", //"?id_patient=" . $idPatient,
    $problem->getWordingPreview() => "../medical/problem_view.php",
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_patient");
  unset($links);

  echo $patient->getHeader();
  echo $problem->getHeader();

  if ($_SESSION['auth']['is_administrative'])
  {
    echo HTML::para(
      HTML::link(_("Add New Medical Test"), '../medical/test_new_form.php',
        array(
          'id_problem' => $idProblem,
          'id_patient' => $idPatient
        )
      )
    );
    echo HTML::rule();
  }

  $testQ = new Query_Test();
  if ( !$testQ->select($idProblem) )
  {
    $testQ->close();

    echo Msg::info(_("No medical tests defined for this medical problem."));
    include_once("../layout/footer.php");
    exit();
  }

  echo HTML::section(2, _("Medical Tests List:"));

  $thead = array(
    _("Function") => array('colspan' => ($_SESSION['auth']['is_administrative'] ? 3 : 1)),
    _("Document Type"),
    _("Path Filename")
  );

  $tbody = array();
  while ($test = $testQ->fetch())
  {
    $temp = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
    $temp = substr($temp, 0, strrpos($temp, "/")) . "/tests/"
      . String::translateBrowser($test->getPathFilename(false));

    $row = HTML::link(
      HTML::image('../img/action_view.png', _("view")),
      $temp,
      null,
      array('class' => 'popup')
    );
    $row .= OPEN_SEPARATOR;

    if ($_SESSION['auth']['is_administrative'])
    {
      $row .= HTML::link(
        HTML::image('../img/action_edit.png', _("edit")),
        '../medical/test_edit_form.php',
        array(
          'id_problem' => $idProblem,
          'id_patient' => $idPatient,
          'id_test' => $test->getIdTest()
        )
      );
      $row .= OPEN_SEPARATOR;

      $row .= HTML::link(
        HTML::image('../img/action_delete.png', _("delete")),
        '../medical/test_del_confirm.php',
        array(
          'id_problem' => $idProblem,
          'id_patient' => $idPatient,
          'id_test' => $test->getIdTest()
        )
      );
      $row .= OPEN_SEPARATOR;
    } // end if

    $row .= $test->getDocumentType();
    $row .= OPEN_SEPARATOR;

    $row .= $test->getPathFilename();

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end while
  $testQ->freeResult();
  $testQ->close();
  unset($testQ);
  unset($test);

  echo HTML::table($thead, $tbody);

  require_once("../layout/footer.php");
?>
