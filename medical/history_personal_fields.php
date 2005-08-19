<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_personal_fields.php,v 1.11 2005/08/19 10:59:02 jact Exp $
 */

/**
 * history_personal_fields.php
 *
 * Fields of personal antecedents
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  $tbody = array();

  $row = Form::strLabel("birth_growth", _("Birth and Growth") . ":");
  $row .= Form::strTextArea("birth_growth", "birth_growth", 4, 90, $postVars["birth_growth"]);
  $tbody[] = $row;

  $row = Form::strLabel("growth_sexuality", _("Growth and Sexuality") . ":");
  $row .= Form::strTextArea("growth_sexuality", "growth_sexuality", 4, 90, $postVars["growth_sexuality"]);
  $tbody[] = $row;

  $row = Form::strLabel("feed", _("Feed") . ":");
  $row .= Form::strTextArea("feed", "feed", 4, 90, $postVars["feed"]);
  $tbody[] = $row;

  $row = Form::strLabel("habits", _("Habits") . ":");
  $row .= Form::strTextArea("habits", "habits", 4, 90, $postVars["habits"]);
  $tbody[] = $row;

  $row = Form::strLabel("peristaltic_conditions", _("Peristaltic Conditions") . ":");
  $row .= Form::strTextArea("peristaltic_conditions", "peristaltic_conditions", 4, 90, $postVars["peristaltic_conditions"]);
  $tbody[] = $row;

  $row = Form::strLabel("psychological", _("Psychological Conditions") . ":");
  $row .= Form::strTextArea("psychological", "psychological", 4, 90, $postVars["psychological"]);
  $tbody[] = $row;

  $row = Form::strLabel("children_complaint", _("Children Complaint") . ":");
  $row .= Form::strTextArea("children_complaint", "children_complaint", 4, 90, $postVars["children_complaint"]);
  $tbody[] = $row;

  $row = Form::strLabel("venereal_disease", _("Venereal Disease") . ":");
  $row .= Form::strTextArea("venereal_disease", "venereal_disease", 4, 90, $postVars["venereal_disease"]);
  $tbody[] = $row;

  $row = Form::strLabel("accident_surgical_operation", _("Accidents and Surgical Operations") . ":");
  $row .= Form::strTextArea("accident_surgical_operation", "accident_surgical_operation", 4, 90, $postVars["accident_surgical_operation"]);
  $tbody[] = $row;

  $row = Form::strLabel("medicinal_intolerance", _("Medicinal Intolerance") . ":");
  $row .= Form::strTextArea("medicinal_intolerance", "medicinal_intolerance", 4, 90, $postVars["medicinal_intolerance"]);
  $tbody[] = $row;

  $row = Form::strLabel("mental_illness", _("Mental Illness") . ":");
  $row .= Form::strTextArea("mental_illness", "mental_illness", 4, 90, $postVars["mental_illness"]);
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("button1", "button1", _("Update"))
    . Form::strButton("return", "return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array(
    'class' => 'largeArea'
  );

  Form::fieldset($title, $tbody, $tfoot, $options);
?>
