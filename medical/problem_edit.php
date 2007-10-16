<?php
/**
 * problem_edit.php
 *
 * Medical Problem edition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_edit.php,v 1.18 2007/10/16 20:15:18 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for post vars. Go back to medical problems list if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: ../medical/problem_list.php");
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
   * Retrieving post vars
   */
  $idPatient = intval($_POST["id_patient"]);
  $idProblem = intval($_POST["id_problem"]);

  $errorLocation = "../medical/problem_edit_form.php?key=" . $idProblem . "&pat=" . $idPatient; // controlling var

  /**
   * Validate data
   */
  $problem = new Problem();

  $problem->setIdProblem($idProblem);

  require_once("../medical/problem_validate_post.php");

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Update problem
   */
  $problemQ = new Problem_Page_Query();
  $problemQ->connect();

  $problemQ->update($problem);

  if ($problem->getClosingDate(false))
  {
    FlashMsg::add(sprintf(_("Medical problem, %s, has been added to closed medical problems list."),
      $problem->getWording())
    );
  }
  else
  {
    FlashMsg::add(sprintf(_("Medical problem, %s, has been updated."), $problem->getWording()));
  }

  $problemQ->close();
  unset($problemQ);
  unset($problem);

  /**
   * Record log process
   */
  recordLog("Problem_Page_Query", "UPDATE", array($idProblem));

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  $returnLocation = "../medical/problem_list.php?key=" . $idPatient;
  header("Location: " . $returnLocation);
?>
