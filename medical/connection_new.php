<?php
/**
 * connection_new.php
 *
 * Connection between medical problems addition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: connection_new.php,v 1.20 2007/12/15 15:05:01 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: ../medical/patient_new_form.php");
    exit();
  }

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE);

  require_once("../lib/Form.php");

  Form::compareToken('../medical/patient_new_form.php');

  require_once("../model/Query/Connection.php");
  require_once("../model/Query/Page/Record.php");

  /**
   * Retrieving post vars
   */
  $idPatient = intval($_POST["id_patient"]);
  $idProblem = intval($_POST["id_problem"]);

  /**
   * Prevent user from aborting script
   */
  $oldAbort = ignore_user_abort(true);

  /**
   * Insert new connection problem
   */
  $connQ = new Query_Connection();
  $recordQ = new Query_Page_Record();

  $n = count($_POST["check"]);
  for ($i = 0; $i < $n; $i++)
  {
    if ($idProblem == $_POST["check"][$i])
    {
      continue; // a problem can't be connection of itself
    }

    $connQ->captureError(true);
    $connQ->insert($idProblem, $_POST["check"][$i]);
    if ($connQ->isError())
    {
      if ($connQ->getDbErrno() == 1062) // duplicated key
      {
        $connQ->clearErrors();
      }
      else
      {
        $connQ->close();
        Error::query($connQ);
      }
    }
    else
    {
      /**
       * Record log process
       */
      $recordQ->log("Query_Connection", "INSERT", array($idProblem, $_POST["check"][$i]));
    }
  }
  $recordQ->close();
  unset($recordQ);
  $connQ->close();
  unset($connQ);

  /**
   * Reset abort setting
   */
  ignore_user_abort($oldAbort);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  FlashMsg::add(_("Connection problems have been added."));
  // To header, without &amp;
  //$returnLocation = "../medical/connection_list.php?id_problem=" . $idProblem . "&id_patient=" . $idPatient;
  $returnLocation = "../medical/connection_list.php";
  header("Location: " . $returnLocation);
?>
