<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_fields.php,v 1.17 2005/08/03 17:40:19 jact Exp $
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

  $thead = array(
    $title => array('colspan' => 2)
  );

  $tbody = array();

  $row = _("Order Number") . ":";
  $row .= OPEN_SEPARATOR;
  $row .= $postVars["order_number"];

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = _("Opening Date") . ":";
  $row .= OPEN_SEPARATOR;
  $row .= I18n::localDate($postVars["opening_date"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = _("Last Update Date") . ":";
  $row .= OPEN_SEPARATOR;
  $row .= I18n::localDate($postVars["last_update_date"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("id_member", _("Attending Physician") . ":");
  $row .= OPEN_SEPARATOR;

  $staffQ = new Staff_Query();
  $staffQ->connect();
  if ($staffQ->isError())
  {
    Error::query($staffQ);
  }

  $numRows = $staffQ->selectType('D');
  if ($staffQ->isError())
  {
    $staffQ->close();
    Error::query($staffQ);
  }

  $array = null;
  $array[0] = ""; // to permit null value
  if ($numRows)
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

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("meeting_place", _("Meeting Place") . ":");
  $row .= OPEN_SEPARATOR;
  $row .= Form::strText("meeting_place", "meeting_place", 40, 40,
    isset($postVars["meeting_place"]) ? $postVars["meeting_place"] : null,
    isset($pageErrors["meeting_place"]) ? $pageErrors["meeting_place"] : null
  );

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("wording", _("Wording") . ":", true) . "<br />\n";
  $row .= Form::strTextArea("wording", "wording", 4, 90,
    isset($postVars["wording"]) ? $postVars["wording"] : null,
    isset($pageErrors["wording"]) ? $pageErrors["wording"] : null
  );

  $tbody[] = array($row);

  $row = Form::strLabel("subjective", _("Subjective") . ":") . "<br />\n";
  $row .= Form::strTextArea("subjective", "subjective", 4, 90, isset($postVars["subjective"]) ? $postVars["subjective"] : null);

  $tbody[] = array($row);

  $row = Form::strLabel("objective", _("Objective") . ":") . "<br />\n";
  $row .= Form::strTextArea("objective", "objective", 4, 90, isset($postVars["objective"]) ? $postVars["objective"] : null);

  $tbody[] = array($row);

  $row = Form::strLabel("appreciation", _("Appreciation") . ":") . "<br />\n";
  $row .= Form::strTextArea("appreciation", "appreciation", 4, 90, isset($postVars["appreciation"]) ? $postVars["appreciation"] : null);

  $tbody[] = array($row);

  $row = Form::strLabel("action_plan", _("Action Plan") . ":") . "<br />\n";
  $row .= Form::strTextArea("action_plan", "action_plan", 4, 90, isset($postVars["action_plan"]) ? $postVars["action_plan"] : null);

  $tbody[] = array($row);

  $row = Form::strLabel("prescription", _("Prescription") . ":") . "<br />\n";
  $row .= Form::strTextArea("prescription", "prescription", 4, 90, isset($postVars["prescription"]) ? $postVars["prescription"] : null);

  $tbody[] = array($row);

  $row = Form::strLabel("closed_problem", _("Closed Problem") . ":");
  $row .= OPEN_SEPARATOR;
  $row .= Form::strCheckBox("closed_problem", "closed_problem", "closed", isset($postVars["closed_problem"]) ? $postVars["closed_problem"] != "" : false);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $tfoot = array(
    Form::strButton("button1", "button1", _("Submit"))
    . Form::strButton("return", "return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array(
    'r5' => array('colspan' => 2),
    'r6' => array('colspan' => 2),
    'r7' => array('colspan' => 2),
    'r8' => array('colspan' => 2),
    'r9' => array('colspan' => 2),
    'r10' => array('colspan' => 2),
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  HTML::table($thead, $tbody, $tfoot, $options);
?>
