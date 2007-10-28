<?php
/**
 * problem_fields.php
 *
 * Fields of medical problem data
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_fields.php,v 1.27 2007/10/28 20:15:19 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

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

  $row = Form::strLabel("id_member", _("Attending Physician") . ":");

  $staffQ = new Query_Staff();
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

  $row .= Form::strSelect("id_member", $array, isset($formVar["id_member"]) ? $formVar["id_member"] : null);
  unset($array);
  $tbody[] = $row;

  $row = Form::strLabel("meeting_place", _("Meeting Place") . ":");
  $row .= Form::strText("meeting_place", 40,
    isset($formVar["meeting_place"]) ? $formVar["meeting_place"] : null,
    isset($formError["meeting_place"]) ? array('error' => $formError["meeting_place"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("wording", _("Wording") . ":", true);
  $row .= Form::strTextArea("wording", 4, 90,
    isset($formVar["wording"]) ? $formVar["wording"] : null,
    isset($formError["wording"]) ? array('error' => $formError["wording"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("subjective", _("Subjective") . ":");
  $row .= Form::strTextArea("subjective", 4, 90, isset($formVar["subjective"]) ? $formVar["subjective"] : null);
  $tbody[] = $row;

  $row = Form::strLabel("objective", _("Objective") . ":");
  $row .= Form::strTextArea("objective", 4, 90, isset($formVar["objective"]) ? $formVar["objective"] : null);
  $tbody[] = $row;

  $row = Form::strLabel("appreciation", _("Appreciation") . ":");
  $row .= Form::strTextArea("appreciation", 4, 90, isset($formVar["appreciation"]) ? $formVar["appreciation"] : null);
  $tbody[] = $row;

  $row = Form::strLabel("action_plan", _("Action Plan") . ":");
  $row .= Form::strTextArea("action_plan", 4, 90, isset($formVar["action_plan"]) ? $formVar["action_plan"] : null);
  $tbody[] = $row;

  $row = Form::strLabel("prescription", _("Prescription") . ":");
  $row .= Form::strTextArea("prescription", 4, 90, isset($formVar["prescription"]) ? $formVar["prescription"] : null);
  $tbody[] = $row;

  $row = Form::strLabel("closed_problem", _("Closed Problem") . ":");
  $row .= Form::strCheckBox("closed_problem", "closed", isset($formVar["closed_problem"]) ? $formVar["closed_problem"] != "" : false);
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("save", _("Submit"))
    . Form::generateToken()
  );

  $options = array(
    'class' => 'largeArea'
  );

  Form::fieldset($title, $tbody, $tfoot, $options);
?>
