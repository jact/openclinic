<?php
/**
 * problem_new.php
 *
 * Medical Problem addition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_new.php,v 1.24 2007/12/15 15:05:01 jact Exp $
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
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE);

  require_once("../model/Query/Page/Problem.php");
  require_once("../model/Query/Page/Record.php");

  /**
   * Retrieving post var
   */
  $idPatient = intval($_POST["id_patient"]);

  //$errorLocation = "../medical/problem_new_form.php?id_patient=" . $idPatient; // controlling var for validate_post
  $errorLocation = "../medical/problem_new_form.php"; // controlling var for validate_post

  /**
   * Validate data
   */
  $problem = new Problem();

  require_once("../medical/problem_validate_post.php");

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Insert new medical problem
   */
  $problemQ = new Query_Page_Problem();
  $problemQ->insert($problem);
  $idProblem = $problemQ->getLastId();

  if ($problem->getClosingDate(false))
  {
    FlashMsg::add(sprintf(_("Medical problem, %s, has been added to closed medical problems list."),
      $problem->getWording())
    );
  }
  else
  {
    FlashMsg::add(sprintf(_("Medical problem, %s, has been added."), $problem->getWording()));
  }

  $problemQ->close();
  unset($problemQ);
  unset($problem);

  /**
   * Record log process
   */
  $recordQ = new Query_Page_Record();
  $recordQ->log("Query_Page_Problem", "INSERT", array($idProblem));
  $recordQ->close();
  unset($recordQ);

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  //$returnLocation = "../medical/problem_view.php?id_patient=" . $idPatient;
  $returnLocation = "../medical/problem_view.php?id_patient=" . $idPatient . "&id_problem=" . $idProblem;
  header("Location: " . $returnLocation);
?>
