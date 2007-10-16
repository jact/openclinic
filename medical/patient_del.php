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
 * @version   CVS: $Id: patient_del.php,v 1.23 2007/10/16 20:09:51 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $onlyDoctor = false;
  $returnLocation = "../medical/patient_search_form.php";

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/Form.php");

  Form::compareToken('../medical/patient_search_form.php');

  require_once("../model/History_Query.php");
  require_once("../model/Patient_Page_Query.php");
  require_once("../model/Relative_Query.php"); // referencial integrity
  require_once("../model/DelPatient_Query.php");
  require_once("../model/Problem_Page_Query.php"); // referencial integrity
  require_once("../model/DelProblem_Query.php"); // referencial integrity
  require_once("../shared/record_log.php"); // record log

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
  $relQ = new Relative_Query();
  $relQ->connect();

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
  $patQ = new Patient_Page_Query();
  $patQ->connect();

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

    $historyQ = new History_Query();
    $historyQ->connect();

    $historyQ->selectPersonal($idPatient);
    $historyP = $historyQ->fetch();

    $historyQ->selectFamily($idPatient);
    $historyF = $historyQ->fetch();
    //Error::debug($patient); Error::debug($historyP); Error::debug($historyF, "", true);

    $delPatientQ = new DelPatient_Query();
    $delPatientQ->connect();

    $delPatientQ->insert($patient, $historyP, $historyF, $_SESSION['userId'], $_SESSION['loginSession']);

    unset($delPatientQ);
    unset($patient);
    unset($historyQ);
    unset($historyP);
    unset($historyF);
  }

  /**
   * Record log process (before deleting process)
   */
  recordLog("Patient_Page_Query", "DELETE", array($idPatient));

  $patQ->delete($idPatient);

  $patQ->close();
  unset($patQ);

  /**
   * Delete asociated problems
   */
  $problemQ = new Problem_Page_Query();
  $problemQ->connect();

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

    $delProblemQ = new DelProblem_Query();
    $delProblemQ->connect();

    for ($i = 0; $i < $numRows; $i++)
    {
      $delProblemQ->insert($array[$i], $_SESSION['userId'], $_SESSION['loginSession']);
    }
    $delProblemQ->close();
    unset($delProblemQ);

    $problemQ = new Problem_Page_Query();
    $problemQ->connect();

    /**
     * Record log process (before deleting process)
     */
    for ($i = 0; $i < $numRows; $i++)
    {
      recordLog("Problem_Page_Query", "DELETE", array($array[$i]->getIdProblem()));
    }

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
  $problemQ = new Problem_Page_Query();
  $problemQ->connect();

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

    $delProblemQ = new DelProblem_Query();
    $delProblemQ->connect();

    for ($i = 0; $i < $numRows; $i++)
    {
      $delProblemQ->insert($array[$i], $_SESSION['userId'], $_SESSION['loginSession']);
    }
    $delProblemQ->close();
    unset($delProblemQ);

    $problemQ = new Problem_Page_Query();
    $problemQ->connect();

    /**
     * Record log process (before deleting process)
     */
    for ($i = 0; $i < $numRows; $i++)
    {
      recordLog("Problem_Page_Query", "DELETE", array($array[$i]->getIdProblem()));
    }

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
  require_once("../medical/visited_list.php");
  deletePatient($idPatient);

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
