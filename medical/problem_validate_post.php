<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_validate_post.php,v 1.5 2004/10/04 21:43:30 jact Exp $
 */

/**
 * problem_validate_post.php
 ********************************************************************
 * Validate post data of a medical problem
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * @since 0.6
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

  $problem->setLastUpdateDate($_POST["last_update_date"]);
  $_POST["last_update_date"] = $problem->getLastUpdateDate();

  $problem->setIdPatient($_POST["id_patient"]);
  //$_POST["id_patient"] = $problem->getIdPatient();

  $problem->setCollegiateNumber($_POST["collegiate_number"]);
  $_POST["collegiate_number"] = $problem->getCollegiateNumber();

  $problem->setOrderNumber($_POST["order_number"]);
  //$_POST["order_number"] = $problem->getOrderNumber();

  $problem->setOpeningDate($_POST["opening_date"]);
  $_POST["opening_date"] = $problem->getOpeningDate();

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
    $pageErrors["wording"] = $problem->getWordingError();

    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;

    header("Location: " . $errorLocation);
    exit();
  }
?>
