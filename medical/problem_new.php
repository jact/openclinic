<?php
/**
 * problem_new.php
 *
 * Medical Problem addition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_new.php,v 1.15 2006/10/13 19:53:17 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: ../medical/problem_new_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $onlyDoctor = false;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Problem_Page_Query.php");
  require_once("../shared/record_log.php"); // record log

  /**
   * Retrieving post var
   */
  $idPatient = intval($_POST["id_patient"]);

  $errorLocation = "../medical/problem_new_form.php?key=" . $idPatient; // controlling var

  /**
   * Validate data
   */
  $problem = new Problem();

  require_once("../medical/problem_validate_post.php");

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Insert new medical problem
   */
  $problemQ = new Problem_Page_Query();
  $problemQ->connect();

  $problemQ->insert($problem);
  $idProblem = $problemQ->getLastId();

  $problemQ->close();
  unset($problemQ);

  /**
   * Record log process
   */
  recordLog("Problem_Page_Query", "INSERT", array($idProblem));

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  $returnLocation = "../medical/problem_list.php?key=" . $idPatient; // controlling var

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  $info = urlencode($problem->getWording()) . ($problem->getClosingDate(false) ? "&closed=Y" : "");
  unset($problem);
  header("Location: " . $returnLocation . "&added=Y&info=" . $info);
?>
