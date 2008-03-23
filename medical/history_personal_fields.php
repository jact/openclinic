<?php
/**
 * history_personal_fields.php
 *
 * Fields of personal antecedents
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: history_personal_fields.php,v 1.20 2008/03/23 12:00:17 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  $tbody = array();

  $row = Form::label("birth_growth", _("Birth and Growth") . ":");
  $row .= Form::textArea("birth_growth",
    $formVar["birth_growth"],
    array(
      'rows' => 4,
      'cols' => 90
    )
  );
  $tbody[] = $row;

  $row = Form::label("growth_sexuality", _("Growth and Sexuality") . ":");
  $row .= Form::textArea("growth_sexuality",
    $formVar["growth_sexuality"],
    array(
      'rows' => 4,
      'cols' => 90
    )
  );
  $tbody[] = $row;

  $row = Form::label("feed", _("Feed") . ":");
  $row .= Form::textArea("feed",
    $formVar["feed"],
    array(
      'rows' => 4,
      'cols' => 90
    )
  );
  $tbody[] = $row;

  $row = Form::label("habits", _("Habits") . ":");
  $row .= Form::textArea("habits",
    $formVar["habits"],
    array(
      'rows' => 4,
      'cols' => 90
    )
  );
  $tbody[] = $row;

  $row = Form::label("peristaltic_conditions", _("Peristaltic Conditions") . ":");
  $row .= Form::textArea("peristaltic_conditions",
    $formVar["peristaltic_conditions"],
    array(
      'rows' => 4,
      'cols' => 90
    )
  );
  $tbody[] = $row;

  $row = Form::label("psychological", _("Psychological Conditions") . ":");
  $row .= Form::textArea("psychological",
    $formVar["psychological"],
    array(
      'rows' => 4,
      'cols' => 90
    )
  );
  $tbody[] = $row;

  $row = Form::label("children_complaint", _("Children Complaint") . ":");
  $row .= Form::textArea("children_complaint",
    $formVar["children_complaint"],
    array(
      'rows' => 4,
      'cols' => 90
    )
  );
  $tbody[] = $row;

  $row = Form::label("venereal_disease", _("Venereal Disease") . ":");
  $row .= Form::textArea("venereal_disease",
    $formVar["venereal_disease"],
    array(
      'rows' => 4,
      'cols' => 90
    )
  );
  $tbody[] = $row;

  $row = Form::label("accident_surgical_operation", _("Accidents and Surgical Operations") . ":");
  $row .= Form::textArea("accident_surgical_operation",
    $formVar["accident_surgical_operation"],
    array(
      'rows' => 4,
      'cols' => 90
    )
  );
  $tbody[] = $row;

  $row = Form::label("medicinal_intolerance", _("Medicinal Intolerance") . ":");
  $row .= Form::textArea("medicinal_intolerance",
    $formVar["medicinal_intolerance"],
    array(
      'rows' => 4,
      'cols' => 90
    )
  );
  $tbody[] = $row;

  $row = Form::label("mental_illness", _("Mental Illness") . ":");
  $row .= Form::textArea("mental_illness",
    $formVar["mental_illness"],
    array(
      'rows' => 4,
      'cols' => 90
    )
  );
  $tbody[] = $row;

  $tfoot = array(
    Form::button("update", _("Update"))
    . Form::generateToken()
  );

  $options = array(
    'class' => 'large_area'
  );

  echo Form::fieldset($title, $tbody, $tfoot, $options);
?>
