<?php
/**
 * patient_fields.php
 *
 * Fields of patient data
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_fields.php,v 1.27 2006/12/28 16:25:39 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  $tbody = array();

  //$row = _("Last Update Date") . ":";
  //$row .= I18n::localDate($formVar["last_update_date"]);
  //$tbody[] = $row;

  $row = Form::strLabel("nif", _("Tax Identification Number (TIN)") . ":");
  $row .= Form::strText("nif", 20,
    isset($formVar["nif"]) ? $formVar["nif"] : null,
    isset($formError["nif"]) ? array('error' => $formError["nif"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("first_name", _("First Name") . ":", true);
  $row .= Form::strText("first_name", 25, $formVar["first_name"],
    array('error' => $formError["first_name"])
  );
  $tbody[] = $row;

  $row = Form::strLabel("surname1", _("Surname 1") . ":", true);
  $row .= Form::strText("surname1", 30, $formVar["surname1"],
    array('error' => $formError["surname1"])
  );
  $tbody[] = $row;

  $row = Form::strLabel("surname2", _("Surname 2") . ":", true);
  $row .= Form::strText("surname2", 30, $formVar["surname2"],
    array('error' => $formError["surname2"])
  );
  $tbody[] = $row;

  $row = Form::strLabel("address", _("Address") . ":");
  $row .= Form::strTextArea("address", 3, 30, $formVar["address"]);
  $tbody[] = $row;

  $row = Form::strLabel("phone_contact", _("Phone Contact") . ":");
  $row .= Form::strTextArea("phone_contact", 3, 30, $formVar["phone_contact"]);
  $tbody[] = $row;

  $array = null;
  $array['V'] = _("Male");
  $array['H'] = _("Female");

  $row = Form::strLabel("sex", _("Sex") . ":");
  $row .= Form::strSelect("sex", $array, $formVar["sex"]);
  unset($array);
  $tbody[] = $row;

  $row = Form::strLabel("race", _("Race") . ":");
  $row .= Form::strText("race", 25,
    isset($formVar["race"]) ? $formVar["race"] : null,
    isset($formError["race"]) ? array('error' => $formError["race"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("year", _("Birth Date") . ":");
  $aux = explode("-", ( !empty($formVar["birth_date"]) ) ? $formVar["birth_date"] : '0000-00-00');
  $row .= Form::strText("year", 4, ((intval($aux[0]) != 0) ? intval($aux[0]) : ''));
  $row .= " - ";
  $row .= Form::strText("month", 2, ((intval($aux[1]) != 0) ? intval($aux[1]) : ''));
  $row .= " - ";
  $row .= Form::strText("day", 2, ((intval($aux[2]) != 0) ? intval($aux[2]) : ''));
  $row .= " " . _("(yyyy-mm-dd)");
  unset($aux);

  if ($formError["birth_date"] != "")
  {
    $row .= HTML::strMessage($formError["birth_date"], OPEN_MSG_ERROR, false);
  }
  $tbody[] = $row;

  $row = Form::strLabel("birth_place", _("Birth Place") . ":");
  $row .= Form::strText("birth_place", 40,
    isset($formVar["birth_place"]) ? $formVar["birth_place"] : null,
    isset($formError["birth_place"]) ? array('error' => $formError["birth_place"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("dyear", _("Decease Date") . ":");
  $aux = explode("-", ( !empty($formVar["decease_date"]) ) ? $formVar["decease_date"] : '0000-00-00');
  $row .= Form::strText("dyear", 4, ((intval($aux[0]) != 0) ? intval($aux[0]) : ''));
  $row .= " - ";
  $row .= Form::strText("dmonth", 2, ((intval($aux[1]) != 0) ? intval($aux[1]) : ''));
  $row .= " - ";
  $row .= Form::strText("dday", 2, ((intval($aux[2]) != 0) ? intval($aux[2]) : ''));
  $row .= " " . _("(yyyy-mm-dd)");
  unset($aux);

  if ($formError["decease_date"] != "")
  {
    $row .= HTML::strMessage($formError["decease_date"], OPEN_MSG_ERROR, false);
  }
  $tbody[] = $row;

  $row = Form::strLabel("nts", _("Sanitary Card Number (SCN)") . ":");
  $row .= Form::strText("nts", 30,
    isset($formVar["nts"]) ? $formVar["nts"] : null,
    isset($formError["nts"]) ? array('error' => $formError["nts"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("nss", _("National Health Service Number (NHSN)") . ":");
  $row .= Form::strText("nss", 30,
    isset($formVar["nss"]) ? $formVar["nss"] : null,
    isset($formError["nss"]) ? array('error' => $formError["nss"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("family_situation", _("Family Situation") . ":");
  $row .= Form::strTextArea("family_situation", 3, 30, $formVar["family_situation"]);
  $tbody[] = $row;

  $row = Form::strLabel("labour_situation", _("Labour Situation") . ":");
  $row .= Form::strTextArea("labour_situation", 3, 30, $formVar["labour_situation"]);
  $tbody[] = $row;

  $row = Form::strLabel("education", _("Education") . ":");
  $row .= Form::strTextArea("education", 3, 30, $formVar["education"]);
  $tbody[] = $row;

  $row = Form::strLabel("insurance_company", _("Insurance Company") . ":");
  $row .= Form::strText("insurance_company", 30,
    isset($formVar["insurance_company"]) ? $formVar["insurance_company"] : null,
    isset($formError["insurance_company"]) ? array('error' => $formError["insurance_company"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("id_member", _("Doctor you are assigned to") . ":");

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

  $row .= Form::strSelect("id_member", $array, $formVar["id_member"]);
  unset($array);
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("save", _("Submit"))
    . Form::generateToken()
  );

  Form::fieldset($title, $tbody, $tfoot);
?>
