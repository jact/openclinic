<?php
/**
 * problem_del.php
 *
 * Medical Problem deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_del.php,v 1.17 2006/04/03 18:59:30 jact Exp $
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

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Problem_Page_Query.php");
  require_once("../classes/Connection_Query.php"); // referencial integrity
  require_once("../classes/DelProblem_Query.php");
  require_once("../shared/record_log.php"); // record log

  /**
   * Retrieving post vars
   */
  $idProblem = intval($_POST["id_problem"]);
  $idPatient = intval($_POST["id_patient"]);
  $wording = Check::safeText($_POST["wording"]);

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Delete medical problems connections
   */
  $connQ = new Connection_Query();
  $connQ->connect();

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
  $problemQ = new Problem_Page_Query();
  $problemQ->connect();

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    if ( !$problemQ->select($idProblem) )
    {
      $problemQ->close();
      include_once("../shared/header.php");

      HTML::message(_("That medical problem does not exist."), OPEN_MSG_ERROR);

      include_once("../shared/footer.php");
      exit();
    }

    $problem = $problemQ->fetch();
    if ( !$problem )
    {
      $problemQ->close();
      Error::fetch($problemQ);
    }

    $delProblemQ = new DelProblem_Query();
    $delProblemQ->connect();

    $delProblemQ->insert($problem, $_SESSION['userId'], $_SESSION['loginSession']);

    unset($delProblemQ);
    unset($problem);
  }

  /**
   * Record log process (before deleting process)
   */
  recordLog("Problem_Page_Query", "DELETE", array($idProblem));

  $problemQ->delete($idProblem);

  $problemQ->close();
  unset($problemQ);

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  $returnLocation = "../medical/problem_list.php?key=" . $idPatient; // controlling var

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  $info = urlencode($wording);
  header("Location: " . $returnLocation . "&deleted=Y&info=" . $info);
?>
