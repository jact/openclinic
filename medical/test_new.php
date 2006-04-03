<?php
/**
 * test_new.php
 *
 * Medical test addition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: test_new.php,v 1.15 2006/04/03 18:59:30 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: ../medical/test_new_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $onlyDoctor = false;
  $restrictInDemo = true; // To prevent users' malice

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Test_Query.php");
  require_once("../shared/record_log.php"); // record log

  /**
   * Retrieving post vars
   */
  $idPatient = intval($_POST["id_patient"]);
  $idProblem = intval($_POST["id_problem"]);

  $errorLocation = "../medical/test_new_form.php?key=" . $idProblem . "&pat=" . $idPatient; // controlling var

  /**
   * Validate data
   */
  $test = new Test();

  require_once("../medical/test_validate_post.php");

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Insert new medical test
   */
  $testQ = new Test_Query();
  $testQ->connect();

  $testQ->insert($test);
  $idTest = $testQ->getLastId();

  $testQ->close();
  unset($testQ);

  /**
   * Record log process
   */
  recordLog("Test_Query", "INSERT", array($idTest));

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  // To header, without &amp;
  $returnLocation = "../medical/test_list.php?key=" . $idProblem . "&pat=" . $idPatient; // controlling var

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  $info = urlencode($test->getPathFilename(false));
  unset($test);
  header("Location: " . $returnLocation . "&added=Y&info=" . $info);
?>
