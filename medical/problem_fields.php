<?php
/**
 * problem_fields.php
 *
 * Fields of medical problem data
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_fields.php,v 1.31 2008/03/23 12:00:17 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../model/Query/Staff.php");

  $tbody = array();

  $row = _("Order Number") . ": ";
  $row .= $formVar["order_number"];
  $tbody[] = $row;

  $row = _("Opening Date") . ": ";
  $row .= I18n::localDate($formVar["opening_date"]);
  $tbody[] = $row;

  $row = _("Last Update Date") . ": ";
  $row .= I18n::localDate($formVar["last_update_date"]);
  $tbody[] = $row;

  $row = Form::label("id_member", _("Attending Physician") . ":");

  $staffQ = new Query_Staff();

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

  $row .= Form::select("id_member", $array, isset($formVar["id_member"]) ? $formVar["id_member"] : null);
  unset($array);
  $tbody[] = $row;

  $row = Form::label("meeting_place", _("Meeting Place") . ":");
  $row .= Form::text("meeting_place",
    isset($formVar["meeting_place"]) ? $formVar["meeting_place"] : null,
    array(
      'size' => 40,
      'error' => isset($formError["meeting_place"]) ? $formError["meeting_place"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::label("wording", _("Wording") . ":", array('class' => 'required'));
  $row .= Form::textArea("wording",
    isset($formVar["wording"]) ? $formVar["wording"] : null,
    array(
      'rows' => 4,
      'cols' => 90,
      'error' => isset($formError["wording"]) ? $formError["wording"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::label("subjective", _("Subjective") . ":");
  $row .= Form::textArea("subjective",
    isset($formVar["subjective"]) ? $formVar["subjective"] : null,
    array(
      'rows' => 4,
      'cols' => 90
    )
  );
  $tbody[] = $row;

  $row = Form::label("objective", _("Objective") . ":");
  $row .= Form::textArea("objective",
    isset($formVar["objective"]) ? $formVar["objective"] : null,
    array(
      'rows' => 4,
      'cols' => 90
    )
  );
  $tbody[] = $row;

  $row = Form::label("appreciation", _("Appreciation") . ":");
  $row .= Form::textArea("appreciation",
    isset($formVar["appreciation"]) ? $formVar["appreciation"] : null,
    array(
      'rows' => 4,
      'cols' => 90
    )
  );
  $tbody[] = $row;

  $row = Form::label("action_plan", _("Action Plan") . ":");
  $row .= Form::textArea("action_plan",
    isset($formVar["action_plan"]) ? $formVar["action_plan"] : null,
    array(
      'rows' => 4,
      'cols' => 90
    )
  );
  $tbody[] = $row;

  $row = Form::label("prescription", _("Prescription") . ":");
  $row .= Form::textArea("prescription",
    isset($formVar["prescription"]) ? $formVar["prescription"] : null,
    array(
      'rows' => 4,
      'cols' => 90
    )
  );
  $tbody[] = $row;

  $row = Form::label("closed_problem", _("Closed Problem") . ":");
  $row .= Form::checkBox("closed_problem", "closed",
    array('checked' => isset($formVar["closed_problem"]) ? $formVar["closed_problem"] != "" : false)
  );
  $tbody[] = $row;

  $tfoot = array(
    Form::button("save", _("Submit"))
    . Form::generateToken()
  );

  $options = array(
    'class' => 'large_area'
  );

  echo Form::fieldset($title, $tbody, $tfoot, $options);
?>
