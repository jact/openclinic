<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_fields.php,v 1.11 2005/06/13 19:03:07 jact Exp $
 */

/**
 * problem_fields.php
 *
 * Fields of medical problem data
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['PATH_TRANSLATED']))
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
  $row .= localDate($postVars["opening_date"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = _("Last Update Date") . ":";
  $row .= OPEN_SEPARATOR;
  $row .= localDate($postVars["last_update_date"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="id_member">' . _("Doctor who treated you") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;

  $staffQ = new Staff_Query();
  $staffQ->connect();
  if ($staffQ->isError())
  {
    showQueryError($staffQ);
  }

  $numRows = $staffQ->selectType('D');
  if ($staffQ->isError())
  {
    $staffQ->close();
    showQueryError($staffQ);
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

  $row .= htmlSelectArray("id_member", $array, isset($postVars["id_member"]) ? $postVars["id_member"] : null);
  unset($array);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="meeting_place">' . _("Meeting Place") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("meeting_place", 40, 40,
    isset($postVars["meeting_place"]) ? $postVars["meeting_place"] : null,
    isset($pageErrors["meeting_place"]) ? $pageErrors["meeting_place"] : null
  );

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '* <label for="wording" class="requiredField">' . _("Wording") . ":" . "</label><br />\n";
  $row .= htmlTextArea("wording", 4, 90,
    isset($postVars["wording"]) ? $postVars["wording"] : null,
    isset($pageErrors["wording"]) ? $pageErrors["wording"] : null
  );

  $tbody[] = array($row);

  $row = '<label for="subjective">' . _("Subjective") . ":" . "</label><br />\n";
  $row .= htmlTextArea("subjective", 4, 90, isset($postVars["subjective"]) ? $postVars["subjective"] : null);

  $tbody[] = array($row);

  $row = '<label for="objective">' . _("Objective") . ":" . "</label><br />\n";
  $row .= htmlTextArea("objective", 4, 90, isset($postVars["objective"]) ? $postVars["objective"] : null);

  $tbody[] = array($row);

  $row = '<label for="appreciation">' . _("Appreciation") . ":" . "</label><br />\n";
  $row .= htmlTextArea("appreciation", 4, 90, isset($postVars["appreciation"]) ? $postVars["appreciation"] : null);

  $tbody[] = array($row);

  $row = '<label for="action_plan">' . _("Action Plan") . ":" . "</label><br />\n";
  $row .= htmlTextArea("action_plan", 4, 90, isset($postVars["action_plan"]) ? $postVars["action_plan"] : null);

  $tbody[] = array($row);

  $row = '<label for="prescription">' . _("Prescription") . ":" . "</label><br />\n";
  $row .= htmlTextArea("prescription", 4, 90, isset($postVars["prescription"]) ? $postVars["prescription"] : null);

  $tbody[] = array($row);

  $row = '<label for="closed_problem">' . _("Closed Problem") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlCheckBox("closed_problem", "closed_problem", "closed", isset($postVars["closed_problem"]) ? $postVars["closed_problem"] != "" : false);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $tfoot = array(
    htmlInputButton("button1", _("Submit"))
    . htmlInputButton("button2", _("Reset"), "reset")
    . htmlInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
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

  showTable($thead, $tbody, $tfoot, $options);
?>
