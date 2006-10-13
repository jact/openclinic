<?php
/**
 * relative_del.php
 *
 * Relation between patients deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: relative_del.php,v 1.15 2006/10/13 19:53:17 jact Exp $
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

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Relative_Query.php");
  require_once("../shared/record_log.php"); // record log
  require_once("../lib/Check.php");

  /**
   * Retrieving post vars
   */
  $idPatient = intval($_POST["id_patient"]);
  $idRelative = intval($_POST["id_relative"]);
  $relName = Check::safeText($_POST["name"]);

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Delete relative
   */
  $relQ = new Relative_Query();
  $relQ->connect();

  /**
   * Record log process (before deleting process)
   */
  recordLog("Relative_Query", "DELETE", array($idPatient, $idRelative));

  $relQ->delete($idPatient, $idRelative);

  $relQ->close();
  unset($relQ);

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  $returnLocation = "../medical/relative_list.php?key=" . $idPatient; // controlling var

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  $info = urlencode($relName);
  header("Location: " . $returnLocation . "&deleted=Y&info=" . $info);
?>
