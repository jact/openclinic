<?php
/**
 * problem_validate_post.php
 *
 * Validate post data of a medical problem
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_validate_post.php,v 1.16 2007/10/29 20:06:54 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.6
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../lib/Form.php");
  Form::compareToken($errorLocation);

  $problem->setLastUpdateDate($_POST["last_update_date"]);
  $_POST["last_update_date"] = $problem->getLastUpdateDate();

  $problem->setIdPatient($_POST["id_patient"]);
  //$_POST["id_patient"] = $problem->getIdPatient();

  $problem->setIdMember($_POST["id_member"]);
  $_POST["id_member"] = $problem->getIdMember();

  if (isset($_POST["order_number"]))
  {
    $problem->setOrderNumber($_POST["order_number"]);
    //$_POST["order_number"] = $problem->getOrderNumber();
  }

  if (isset($_POST["opening_date"]))
  {
    $problem->setOpeningDate($_POST["opening_date"]);
    $_POST["opening_date"] = $problem->getOpeningDate();
  }

  if (isset($_POST["closed_problem"]))
  {
    $problem->setClosingDate(date("Y-m-d")); // automatic date (ISO format)
    $_POST["closing_date"] = $problem->getClosingDate();
  }

  $problem->setMeetingPlace($_POST["meeting_place"]);
  $_POST["meeting_place"] = $problem->getMeetingPlace();

  $problem->setWording($_POST["wording"]);
  $_POST["wording"] = $problem->getWording();

  $problem->setSubjective($_POST["subjective"]);
  $_POST["subjective"] = $problem->getSubjective();

  $problem->setObjective($_POST["objective"]);
  $_POST["objective"] = $problem->getObjective();

  $problem->setAppreciation($_POST["appreciation"]);
  $_POST["appreciation"] = $problem->getAppreciation();

  $problem->setActionPlan($_POST["action_plan"]);
  $_POST["action_plan"] = $problem->getActionPlan();

  $problem->setPrescription($_POST["prescription"]);
  $_POST["prescription"] = $problem->getPrescription();

  if ( !$problem->validateData() )
  {
    $formError["wording"] = $problem->getWordingError();

    Form::setSession($_POST, $formError);

    header("Location: " . $errorLocation);
    exit();
  }
?>
