<?php
/**
 * test_list.php
 *
 * Medical tests screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: test_list.php,v 1.31 2007/11/02 22:21:06 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = true;
  $restrictInDemo = true; // To prevent users' malice

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Query/Test.php");
  require_once("../lib/misc_lib.php");
  require_once("../model/Patient.php");
  require_once("../lib/ProblemInfo.php");

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
  $title = _("View Medical Tests");
  $titlePage = $patient->getName() . ' [' . $problem->getWording() . '] (' . $title . ')';
  require_once("../layout/header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    _("Medical Problems Report") => "../medical/problem_list.php", //"?id_patient=" . $idPatient,
    $problem->getWording() => "../medical/problem_view.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  echo $patient->getHeader();
  $problem->showHeader();

  if ($hasMedicalAdminAuth)
  {
    HTML::para(
      HTML::strLink(_("Add New Medical Test"), '../medical/test_new_form.php',
        array(
          'id_problem' => $idProblem,
          'id_patient' => $idPatient
        )
      )
    );
    HTML::rule();
  }

  $testQ = new Query_Test();
  if ( !$testQ->select($idProblem) )
  {
    $testQ->close();

    Msg::info(_("No medical tests defined for this medical problem."));
    include_once("../layout/footer.php");
    exit();
  }

  HTML::section(2, _("Medical Tests List:"));

  $thead = array(
    _("Function") => array('colspan' => ($hasMedicalAdminAuth ? 3 : 1)),
    _("Document Type"),
    _("Path Filename")
  );

  $tbody = array();
  while ($test = $testQ->fetch())
  {
    $temp = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
    $temp = substr($temp, 0, strrpos($temp, "/")) . "/tests/" . translateBrowser($test->getPathFilename(false));

    $row = HTML::strLink(_("view"), $temp, null, array('class' => 'popup'));
    $row .= OPEN_SEPARATOR;

    if ($hasMedicalAdminAuth)
    {
      $row .= HTML::strLink(_("edit"), '../medical/test_edit_form.php',
        array(
          'id_problem' => $test->getIdProblem(),
          'id_patient' => $idPatient,
          'id_test' => $test->getIdTest()
        )
      );
      $row .= OPEN_SEPARATOR;

      $row .= HTML::strLink(_("del"), '../medical/test_del_confirm.php',
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

  HTML::table($thead, $tbody);

  require_once("../layout/footer.php");
?>
