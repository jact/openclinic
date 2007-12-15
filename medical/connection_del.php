<?php
/**
 * connection_del.php
 *
 * Connection between medical problems deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: connection_del.php,v 1.24 2007/12/15 15:05:00 jact Exp $
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

  require_once("../model/Query/Connection.php");
  require_once("../model/Query/Page/Record.php");
  require_once("../lib/Check.php");

  /**
   * Retrieving post vars
   */
  $idProblem = intval($_POST["id_problem"]);
  $idConnection = intval($_POST["id_connection"]);
  $idPatient = intval($_POST["id_patient"]);
  $wording = Check::safeText($_POST["wording"]);

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Delete relative
   */
  $connQ = new Query_Connection();

  /**
   * Record log process (before deleting process)
   */
  $recordQ = new Query_Page_Record();
  $recordQ->log("Query_Connection", "DELETE", array($idProblem, $idConnection));
  $recordQ->close();
  unset($recordQ);

  $connQ->delete($idProblem, $idConnection);

  $connQ->close();
  unset($connQ);

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  FlashMsg::add(sprintf(_("Connection with medical problem, %s, has been deleted."), $wording));
  // To header, without &amp;
  //$returnLocation = "../medical/connection_list.php?id_problem=" . $idProblem . "&id_patient=" . $idPatient;
  $returnLocation = "../medical/connection_list.php";
  header("Location: " . $returnLocation);
?>
