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
 * @version   CVS: $Id: test_list.php,v 1.23 2007/10/09 18:42:13 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || !is_numeric($_GET["pat"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = true;
  $restrictInDemo = true; // To prevent users' malice

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Test_Query.php");
  require_once("../lib/misc_lib.php");

  /**
   * Retrieving get vars
   */
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);
  $info = (isset($_GET["info"]) ? urldecode(Check::safeText($_GET["info"])) : "");

  /**
   * Show page
   */
  $title = _("View Medical Tests");
  require_once("../layout/header.php");
  require_once("../medical/patient_header.php");
  require_once("../medical/problem_header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Medical Problems Report") => "../medical/problem_list.php?key=" . $idPatient,
    _("View Medical Problem") => "../medical/problem_view.php?key=" . $idProblem . "&pat=" . $idPatient,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  showPatientHeader($idPatient);
  showProblemHeader($idProblem);

  /**
   * Display insertion message if coming from new with a successful insert.
   */
  if (isset($_GET["added"]) && !empty($info))
  {
    HTML::message(sprintf(_("Medical test, %s, has been added."), $info), OPEN_MSG_INFO);
  }

  /**
   * Display update message if coming from edit with a successful update.
   */
  if (isset($_GET["updated"]) && !empty($info))
  {
    HTML::message(sprintf(_("Medical test, %s, has been updated."), $info), OPEN_MSG_INFO);
  }

  /**
   * Display deletion message if coming from del with a successful delete.
   */
  if (isset($_GET["deleted"]) && !empty($info))
  {
    HTML::message(sprintf(_("Medical test, %s, has been deleted."), $info), OPEN_MSG_INFO);
  }

  if ($hasMedicalAdminAuth)
  {
    HTML::para(
      HTML::strLink(_("Add New Medical Test"), '../medical/test_new_form.php',
        array(
          'key' => $idProblem,
          'pat' => $idPatient
        )
      )
    );
    HTML::rule();
  }

  $testQ = new Test_Query;
  $testQ->connect();

  if ( !$testQ->select($idProblem) )
  {
    $testQ->close();
    HTML::message(_("No medical tests defined for this medical problem."), OPEN_MSG_INFO);
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
          'key' => $test->getIdProblem(),
          'pat' => $idPatient,
          'test' => $test->getIdTest()
        )
      );
      $row .= OPEN_SEPARATOR;

      $row .= HTML::strLink(_("del"), '../medical/test_del_confirm.php',
        array(
          'key' => $idProblem,
          'pat' => $idPatient,
          'test' => $test->getIdTest(),
          'file' => $test->getPathFilename()
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
