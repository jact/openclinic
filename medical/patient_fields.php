<?php
/**
 * patient_fields.php
 *
 * Fields of patient data
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_fields.php,v 1.33 2013/01/07 18:24:32 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../model/Query/Staff.php");

  $tbody = array();

  //$row = _("Last Update Date") . ":";
  //$row .= I18n::localDate($formVar["last_update_date"]);
  //$tbody[] = $row;

  $row = Form::label("nif", _("Tax Identification Number (TIN)") . ":");
  $row .= Form::text("nif",
    isset($formVar["nif"]) ? $formVar["nif"] : null,
    array(
      'size' => 20,
      'error' => isset($formError["nif"]) ? $formError["nif"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::label("first_name", _("First Name") . ":", array('class' => 'required'));
  $row .= Form::text("first_name",
    isset($formVar["first_name"]) ? $formVar["first_name"] : null,
    array(
      'size' => 25,
      'error' => isset($formError["first_name"]) ? $formError["first_name"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::label("surname1", _("Surname 1") . ":", array('class' => 'required'));
  $row .= Form::text("surname1",
    isset($formVar["surname1"]) ? $formVar["surname1"] : null,
    array(
      'size' => 30,
      'error' => isset($formError["surname1"]) ? $formError["surname1"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::label("surname2", _("Surname 2") . ":"/*, array('class' => 'required')*/);
  $row .= Form::text("surname2",
    isset($formVar["surname2"]) ? $formVar["surname2"] : null,
    array(
      'size' => 30,
      'error' => isset($formError["surname2"]) ? $formError["surname2"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::label("address", _("Address") . ":");
  $row .= Form::textArea("address",
    $formVar["address"],
    array(
      'rows' => 3,
      'cols' => 30
    )
  );
  $tbody[] = $row;

  $row = Form::label("phone_contact", _("Phone Contact") . ":");
  $row .= Form::textArea("phone_contact",
    $formVar["phone_contact"],
    array(
      'rows' => 3,
      'cols' => 30
    )
  );
  $tbody[] = $row;

  $array = null;
  $array['V'] = _("Male");
  $array['H'] = _("Female");

  $row = Form::label("sex", _("Sex") . ":");
  $row .= Form::select("sex", $array, $formVar["sex"]);
  unset($array);
  $tbody[] = $row;

  $row = Form::label("race", _("Race") . ":");
  $row .= Form::text("race",
    isset($formVar["race"]) ? $formVar["race"] : null,
    array(
      'size' => 25,
      'error' => isset($formError["race"]) ? $formError["race"] : null
    )
  );
  $tbody[] = $row;

  // @todo hacer un helper para esta estructura
  $row = Form::label("year", _("Birth Date") . ":");
  $aux = explode("-", ( !empty($formVar["birth_date"]) ) ? $formVar["birth_date"] : '0000-00-00');
  $row .= Form::text("year",
    (intval($aux[0]) != 0) ? intval($aux[0]) : null,
    array('size' => 4)
  );
  $row .= " - ";
  $row .= Form::text("month",
    (intval($aux[1]) != 0) ? intval($aux[1]) : null,
    array('size' => 2)
  );
  $row .= " - ";
  $row .= Form::text("day",
    (intval($aux[2]) != 0) ? intval($aux[2]) : null,
    array('size' => 2)
  );
  $row .= " " . _("(yyyy-mm-dd)");
  unset($aux);
  if ($formError["birth_date"] != "")
  {
    $row .= Msg::error($formError["birth_date"]);
  }
  $tbody[] = $row;

  $row = Form::label("birth_place", _("Birth Place") . ":");
  $row .= Form::text("birth_place",
    isset($formVar["birth_place"]) ? $formVar["birth_place"] : null,
    array(
      'size' => 40,
      'error' => isset($formError["birth_place"]) ? $formError["birth_place"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::label("dyear", _("Decease Date") . ":");
  $aux = explode("-", ( !empty($formVar["decease_date"]) ) ? $formVar["decease_date"] : '0000-00-00');
  $row .= Form::text("dyear",
    (intval($aux[0]) != 0) ? intval($aux[0]) : null,
    array('size' => 4)
  );
  $row .= " - ";
  $row .= Form::text("dmonth",
    (intval($aux[1]) != 0) ? intval($aux[1]) : null,
    array('size' => 2)
  );
  $row .= " - ";
  $row .= Form::text("dday",
    (intval($aux[2]) != 0) ? intval($aux[2]) : null,
    array('size' => 2)
  );
  $row .= " " . _("(yyyy-mm-dd)");
  unset($aux);
  if ($formError["decease_date"] != "")
  {
    $row .= Msg::error($formError["decease_date"]);
  }
  $tbody[] = $row;

  $row = Form::label("nts", _("Sanitary Card Number (SCN)") . ":");
  $row .= Form::text("nts",
    isset($formVar["nts"]) ? $formVar["nts"] : null,
    array(
      'size' => 30,
      'error' => isset($formError["nts"]) ? $formError["nts"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::label("nss", _("National Health Service Number (NHSN)") . ":");
  $row .= Form::text("nss",
    isset($formVar["nss"]) ? $formVar["nss"] : null,
    array(
      'size' => 30,
      'error' => isset($formError["nss"]) ? $formError["nss"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::label("family_situation", _("Family Situation") . ":");
  $row .= Form::textArea("family_situation",
    $formVar["family_situation"],
    array(
      'rows' => 3,
      'cols' => 30
    )
  );
  $tbody[] = $row;

  $row = Form::label("labour_situation", _("Labour Situation") . ":");
  $row .= Form::textArea("labour_situation",
    $formVar["labour_situation"],
    array(
      'rows' => 3,
      'cols' => 30
    )
  );
  $tbody[] = $row;

  $row = Form::label("education", _("Education") . ":");
  $row .= Form::textArea("education",
    $formVar["education"],
    array(
      'rows' => 3,
      'cols' => 30
    )
  );
  $tbody[] = $row;

  $row = Form::label("insurance_company", _("Insurance Company") . ":");
  $row .= Form::text("insurance_company",
    isset($formVar["insurance_company"]) ? $formVar["insurance_company"] : null,
    array(
      'size' => 30,
      'error' => isset($formError["insurance_company"]) ? $formError["insurance_company"] : null
    )
  );
  $tbody[] = $row;

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

  $row = Form::label("id_member", _("Doctor you are assigned to") . ":");
  $row .= Form::select("id_member", $array, $formVar["id_member"]);
  unset($array);
  $tbody[] = $row;

  $tfoot = array(
    Form::button("save", _("Submit"))
    . Form::generateToken()
  );

  echo Form::fieldset($title, $tbody, $tfoot);
?>
