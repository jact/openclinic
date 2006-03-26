<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_personal_fields.php,v 1.14 2006/03/26 15:13:08 jact Exp $
 */

/**
 * history_personal_fields.php
 *
 * Fields of personal antecedents
 *
 * @author jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  $tbody = array();

  $row = Form::strLabel("birth_growth", _("Birth and Growth") . ":");
  $row .= Form::strTextArea("birth_growth", 4, 90, $formVar["birth_growth"]);
  $tbody[] = $row;

  $row = Form::strLabel("growth_sexuality", _("Growth and Sexuality") . ":");
  $row .= Form::strTextArea("growth_sexuality", 4, 90, $formVar["growth_sexuality"]);
  $tbody[] = $row;

  $row = Form::strLabel("feed", _("Feed") . ":");
  $row .= Form::strTextArea("feed", 4, 90, $formVar["feed"]);
  $tbody[] = $row;

  $row = Form::strLabel("habits", _("Habits") . ":");
  $row .= Form::strTextArea("habits", 4, 90, $formVar["habits"]);
  $tbody[] = $row;

  $row = Form::strLabel("peristaltic_conditions", _("Peristaltic Conditions") . ":");
  $row .= Form::strTextArea("peristaltic_conditions", 4, 90, $formVar["peristaltic_conditions"]);
  $tbody[] = $row;

  $row = Form::strLabel("psychological", _("Psychological Conditions") . ":");
  $row .= Form::strTextArea("psychological", 4, 90, $formVar["psychological"]);
  $tbody[] = $row;

  $row = Form::strLabel("children_complaint", _("Children Complaint") . ":");
  $row .= Form::strTextArea("children_complaint", 4, 90, $formVar["children_complaint"]);
  $tbody[] = $row;

  $row = Form::strLabel("venereal_disease", _("Venereal Disease") . ":");
  $row .= Form::strTextArea("venereal_disease", 4, 90, $formVar["venereal_disease"]);
  $tbody[] = $row;

  $row = Form::strLabel("accident_surgical_operation", _("Accidents and Surgical Operations") . ":");
  $row .= Form::strTextArea("accident_surgical_operation", 4, 90, $formVar["accident_surgical_operation"]);
  $tbody[] = $row;

  $row = Form::strLabel("medicinal_intolerance", _("Medicinal Intolerance") . ":");
  $row .= Form::strTextArea("medicinal_intolerance", 4, 90, $formVar["medicinal_intolerance"]);
  $tbody[] = $row;

  $row = Form::strLabel("mental_illness", _("Mental Illness") . ":");
  $row .= Form::strTextArea("mental_illness", 4, 90, $formVar["mental_illness"]);
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("button1", _("Update"))
    . Form::strButton("return", _("Return"), "button", array('onclick' => "parent.location='" . $returnLocation . "'"))
  );

  $options = array(
    'class' => 'largeArea'
  );

  Form::fieldset($title, $tbody, $tfoot, $options);
?>
