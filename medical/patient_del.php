<?php
/**
 * patient_del.php
 *
 * Patient deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_del.php,v 1.35 2007/12/15 15:05:01 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $returnLocation = "../medical/patient_search_form.php";

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: " . $returnLocation);
    exit();
  }

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE);

  require_once("../lib/Form.php");

  Form::compareToken('../medical/patient_search_form.php');

  require_once("../model/Query/History.php");
  require_once("../model/Query/Page/Patient.php");
  require_once("../model/Query/Relative.php"); // referencial integrity
  require_once("../model/Query/DelPatient.php");
  require_once("../model/Query/Page/Problem.php"); // referencial integrity
  require_once("../model/Query/DelProblem.php"); // referencial integrity
  require_once("../model/Query/Page/Record.php");

  /**
   * Retrieving post vars
   */
  $idPatient = intval($_POST["id_patient"]);
  $patName = Check::safeText($_POST["name"]);

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Delete relatives
   */
  $relQ = new Query_Relative();
  $numRows = $relQ->select($idPatient);

  $rel = array();
  for ($i = 0; $i < $numRows; $i++)
  {
    $rel[] = $relQ->fetch();
  }
  $relQ->freeResult();

  while ($aux = array_shift($rel))
  {
    $relQ->delete($idPatient, $aux[1]);
  }
  $relQ->close();
  unset($relQ);
  unset($rel);

  /**
   * Delete patient
   */
  $patQ = new Query_Page_Patient();
  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    if ( !$patQ->select($idPatient) )
    {
      $patQ->close();

      FlashMsg::add(_("That patient does not exist."), OPEN_MSG_ERROR);
      header("Location: " . $returnLocation);
      exit();
    }

    $patient = $patQ->fetch();
    if ( !$patient )
    {
      $patQ->close();
      Error::fetch($patQ);
    }

    $historyQ = new Query_History();
    $historyQ->selectPersonal($idPatient);
    $historyP = $historyQ->fetch();

    $historyQ->selectFamily($idPatient);
    $historyF = $historyQ->fetch();
    //Error::debug($patient); Error::debug($historyP); Error::debug($historyF, "", true);

    $delPatientQ = new Query_DelPatient();
    $delPatientQ->insert($patient, $historyP, $historyF,
      $_SESSION['auth']['user_id'],
      $_SESSION['auth']['login_session']
    );

    unset($delPatientQ);
    unset($patient);
    unset($historyQ);
    unset($historyP);
    unset($historyF);
  }

  /**
   * Record log process (before deleting process)
   */
  $recordQ = new Query_Page_Record();
  $recordQ->log("Query_Page_Patient", "DELETE", array($idPatient));
  $recordQ->close();
  unset($recordQ);

  $patQ->delete($idPatient);

  $patQ->close();
  unset($patQ);

  /**
   * Delete asociated problems
   */
  $problemQ = new Query_Page_Problem();

  /**
   * First: open problems
   */
  $numRows = $problemQ->selectProblems($idPatient, false);
  if ($numRows)
  {
    $array = array();
    for ($i = 0; $i < $numRows; $i++)
    {
      $array[$i] = $problemQ->fetch();
    }
    $problemQ->freeResult();
    $problemQ->close();
    unset($problemQ);

    $delProblemQ = new Query_DelProblem();
    for ($i = 0; $i < $numRows; $i++)
    {
      $delProblemQ->insert($array[$i], $_SESSION['auth']['user_id'], $_SESSION['auth']['login_session']);
    }
    $delProblemQ->close();
    unset($delProblemQ);

    $problemQ = new Query_Page_Problem();

    /**
     * Record log process (before deleting process)
     */
    $recordQ = new Query_Page_Record();
    for ($i = 0; $i < $numRows; $i++)
    {
      $recordQ->log("Query_Page_Problem", "DELETE", array($array[$i]->getIdProblem()));
    }
    $recordQ->close();
    unset($recordQ);

    for ($i = 0; $i < $numRows; $i++)
    {
      $problemQ->delete($array[$i]->getIdProblem());
    }
    $problemQ->close();
    unset($problemQ);
    unset($array);
  }

  /**
   * Afterwards: closed problems
   */
  $problemQ = new Query_Page_Problem();
  $numRows = $problemQ->selectProblems($idPatient, true);
  if ($numRows)
  {
    $array = array();
    for ($i = 0; $i < $numRows; $i++)
    {
      $array[$i] = $problemQ->fetch();
    }
    $problemQ->freeResult();
    $problemQ->close();
    unset($problemQ);

    $delProblemQ = new Query_DelProblem();
    for ($i = 0; $i < $numRows; $i++)
    {
      $delProblemQ->insert($array[$i], $_SESSION['auth']['user_id'], $_SESSION['auth']['login_session']);
    }
    $delProblemQ->close();
    unset($delProblemQ);

    $problemQ = new Query_Page_Problem();

    /**
     * Record log process (before deleting process)
     */
    $recordQ = new Query_Page_Record();
    for ($i = 0; $i < $numRows; $i++)
    {
      $recordQ->log("Query_Page_Problem", "DELETE", array($array[$i]->getIdProblem()));
    }
    $recordQ->close();
    unset($recordQ);

    for ($i = 0; $i < $numRows; $i++)
    {
      $problemQ->delete($array[$i]->getIdProblem());
    }
    $problemQ->close();
    unset($problemQ);
    unset($array);
  }

  /**
   * Update session variables
   */
  require_once("../lib/LastViewedPatient.php");
  LastViewedPatient::delete($idPatient);

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  FlashMsg::add(sprintf(_("Patient, %s, has been deleted."), $patName));
  header("Location: " . $returnLocation);
?>
