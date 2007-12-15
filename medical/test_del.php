<?php
/**
 * test_del.php
 *
 * Medical test deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: test_del.php,v 1.24 2007/12/15 15:05:02 jact Exp $
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
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE, false); // Not in DEMO to prevent users' malice

  require_once("../lib/Form.php");

  Form::compareToken('../medical/patient_search_form.php');

  require_once("../model/Query/Test.php");
  require_once("../model/Query/Page/Record.php");

  /**
   * Retrieving post vars
   */
  $idProblem = intval($_POST["id_problem"]);
  $idTest = intval($_POST["id_test"]);
  $idPatient = intval($_POST["id_patient"]);
  $file = Check::safeText($_POST["path_filename"]);

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Delete medical test
   */
  $testQ = new Query_Test();

  /**
   * Record log process (before deleting process)
   */
  $recordQ = new Query_Page_Record();
  $recordQ->log("Query_Test", "DELETE", array($idTest));
  $recordQ->close();
  unset($recordQ);

  $testQ->delete($idTest);

  $testQ->close();
  unset($testQ);

  //@unlink($file); // do not remove the file because LORTAD

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  FlashMsg::add(sprintf(_("Medical test, %s, has been deleted."), $file));
  // To header, without &amp;
  //$returnLocation = "../medical/test_list.php?id_problem=" . $idProblem . "&id_patient=" . $idPatient; // controlling var
  $returnLocation = "../medical/test_list.php"; // controlling var
  header("Location: " . $returnLocation);
?>
