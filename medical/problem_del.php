<?php
/**
 * problem_del.php
 *
 * Medical Problem deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_del.php,v 1.29 2007/12/15 15:05:01 jact Exp $
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
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE);

  require_once("../lib/Form.php");

  Form::compareToken('../medical/patient_search_form.php');

  require_once("../model/Query/Page/Problem.php");
  require_once("../model/Query/Connection.php"); // referencial integrity
  require_once("../model/Query/DelProblem.php");
  require_once("../model/Query/Page/Record.php");

  /**
   * Retrieving post vars
   */
  $idProblem = intval($_POST["id_problem"]);
  $idPatient = intval($_POST["id_patient"]);

  //$returnLocation = "../medical/problem_list.php?id_patient=" . $idPatient; // controlling var
  $returnLocation = "../medical/problem_list.php"; // controlling var

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Delete medical problems connections
   */
  $connQ = new Query_Connection();
  $numRows = $connQ->select($idProblem);

  $conn = array();
  for ($i = 0; $i < $numRows; $i++)
  {
    $conn[] = $connQ->fetch();
  }
  $connQ->freeResult();

  while ($aux = array_shift($conn))
  {
    $connQ->delete($idProblem, $aux[1]);
  }
  $connQ->close();
  unset($connQ);
  unset($conn);

  /**
   * Delete problem
   */
  $problemQ = new Query_Page_Problem();
  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    if ( !$problemQ->select($idProblem) )
    {
      $problemQ->close();

      FlashMsg::add(_("That medical problem does not exist."), OPEN_MSG_ERROR);
      header("Location: " . $returnLocation);
      exit();
    }

    $problem = $problemQ->fetch();
    if ( !$problem )
    {
      $problemQ->close();
      Error::fetch($problemQ);
    }
    $wording = $problem->getWording();

    $delProblemQ = new Query_DelProblem();
    $delProblemQ->insert($problem, $_SESSION['auth']['user_id'], $_SESSION['auth']['login_session']);

    unset($delProblemQ);
    unset($problem);
  }

  /**
   * Record log process (before deleting process)
   */
  $recordQ = new Query_Page_Record();
  $recordQ->log("Query_Page_Problem", "DELETE", array($idProblem));
  $recordQ->close();
  unset($recordQ);

  $problemQ->delete($idProblem);

  $problemQ->close();
  unset($problemQ);

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  FlashMsg::add(sprintf(_("Medical problem, %s, has been deleted."), $wording));
  header("Location: " . $returnLocation);
?>
