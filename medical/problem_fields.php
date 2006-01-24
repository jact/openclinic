<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_fields.php,v 1.20 2006/01/24 19:55:59 jact Exp $
 */

/**
 * problem_fields.php
 *
 * Fields of medical problem data
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  $tbody = array();

  $row = _("Order Number") . ": ";
  $row .= $postVars["order_number"];
  $tbody[] = $row;

  $row = _("Opening Date") . ": ";
  $row .= I18n::localDate($postVars["opening_date"]);
  $tbody[] = $row;

  $row = _("Last Update Date") . ": ";
  $row .= I18n::localDate($postVars["last_update_date"]);
  $tbody[] = $row;

  $row = Form::strLabel("id_member", _("Attending Physician") . ":");

  $staffQ = new Staff_Query();
  $staffQ->connect();

  $array = null;
  $array[0] = ""; // to permit null value
  if ($staffQ->selectType('D'))
  {
    while ($staff = $staffQ->fetch())
    {
      $array[$staff->getIdMember()] = $staff->getFirstName() . " " . $staff->getSurname1() . " " . $staff->getSurname2();
    }
    $staffQ->freeResult();
  }
  $staffQ->close();
  unset($staffQ);

  $row .= Form::strSelect("id_member", "id_member", $array, isset($postVars["id_member"]) ? $postVars["id_member"] : null);
  unset($array);
  $tbody[] = $row;

  $row = Form::strLabel("meeting_place", _("Meeting Place") . ":");
  $row .= Form::strText("meeting_place", "meeting_place", 40, 40,
    isset($postVars["meeting_place"]) ? $postVars["meeting_place"] : null,
    isset($pageErrors["meeting_place"]) ? $pageErrors["meeting_place"] : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("wording", _("Wording") . ":", true);
  $row .= Form::strTextArea("wording", "wording", 4, 90,
    isset($postVars["wording"]) ? $postVars["wording"] : null,
    isset($pageErrors["wording"]) ? $pageErrors["wording"] : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("subjective", _("Subjective") . ":");
  $row .= Form::strTextArea("subjective", "subjective", 4, 90, isset($postVars["subjective"]) ? $postVars["subjective"] : null);
  $tbody[] = $row;

  $row = Form::strLabel("objective", _("Objective") . ":");
  $row .= Form::strTextArea("objective", "objective", 4, 90, isset($postVars["objective"]) ? $postVars["objective"] : null);
  $tbody[] = $row;

  $row = Form::strLabel("appreciation", _("Appreciation") . ":");
  $row .= Form::strTextArea("appreciation", "appreciation", 4, 90, isset($postVars["appreciation"]) ? $postVars["appreciation"] : null);
  $tbody[] = $row;

  $row = Form::strLabel("action_plan", _("Action Plan") . ":");
  $row .= Form::strTextArea("action_plan", "action_plan", 4, 90, isset($postVars["action_plan"]) ? $postVars["action_plan"] : null);
  $tbody[] = $row;

  $row = Form::strLabel("prescription", _("Prescription") . ":");
  $row .= Form::strTextArea("prescription", "prescription", 4, 90, isset($postVars["prescription"]) ? $postVars["prescription"] : null);
  $tbody[] = $row;

  $row = Form::strLabel("closed_problem", _("Closed Problem") . ":");
  $row .= Form::strCheckBox("closed_problem", "closed_problem", "closed", isset($postVars["closed_problem"]) ? $postVars["closed_problem"] != "" : false);
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("button1", "button1", _("Submit"))
    . Form::strButton("return", "return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array(
    'class' => 'largeArea'
  );

  Form::fieldset($title, $tbody, $tfoot, $options);
?>
