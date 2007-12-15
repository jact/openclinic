<?php
/**
 * test_new.php
 *
 * Medical test addition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: test_new.php,v 1.24 2007/12/15 15:05:02 jact Exp $
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
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE, false); // Not in DEMO to prevent users' malice

  require_once("../model/Query/Test.php");
  require_once("../model/Query/Page/Record.php");

  /**
   * Retrieving post vars
   */
  $idPatient = intval($_POST["id_patient"]);
  $idProblem = intval($_POST["id_problem"]);

  //$errorLocation = "../medical/test_new_form.php?id_problem=" . $idProblem . "&id_patient=" . $idPatient; // controlling var for validate_post
  $errorLocation = "../medical/test_new_form.php"; // controlling var for validate_post

  /**
   * Validate data
   */
  $test = new Test();

  require_once("../medical/test_validate_post.php");

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Insert new medical test
   */
  $testQ = new Query_Test();
  $testQ->insert($test);
  $idTest = $testQ->getLastId();

  FlashMsg::add(sprintf(_("Medical test, %s, has been added."), $test->getPathFilename(false)));

  $testQ->close();
  unset($testQ);
  unset($test);

  /**
   * Record log process
   */
  $recordQ = new Query_Page_Record();
  $recordQ->log("Query_Test", "INSERT", array($idTest));
  $recordQ->close();
  unset($recordQ);

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  // To header, without &amp;
  //$returnLocation = "../medical/test_list.php?id_problem=" . $idProblem . "&id_patient=" . $idPatient; // controlling var
  $returnLocation = "../medical/test_list.php";
  header("Location: " . $returnLocation);
?>
