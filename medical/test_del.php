<?php
/**
 * test_del.php
 *
 * Medical test deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: test_del.php,v 1.16 2006/10/13 19:53:17 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $onlyDoctor = false;
  $restrictInDemo = true; // To prevent users' malice

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Test_Query.php");
  require_once("../shared/record_log.php"); // record log

  /**
   * Retrieving post vars
   */
  $idProblem = intval($_POST["id_problem"]);
  $idTest = intval($_POST["id_test"]);
  $idPatient = intval($_POST["id_patient"]);
  $file = Check::safeText($_POST["file"]);

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Delete medical test
   */
  $testQ = new Test_Query();
  $testQ->connect();

  /**
   * Record log process (before deleting process)
   */
  recordLog("Test_Query", "DELETE", array($idTest));

  $testQ->delete($idTest);

  $testQ->close();
  unset($testQ);

  //@unlink($file); // do not remove the file because LORTAD

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  // To header, without &amp;
  $returnLocation = "../medical/test_list.php?key=" . $idProblem . "&pat=" . $idPatient; // controlling var

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  $info = urlencode($file);
  header("Location: " . $returnLocation . "&deleted=Y&info=" . $info);
?>
