<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_fields.php,v 1.22 2006/03/12 18:43:39 jact Exp $
 */

/**
 * patient_fields.php
 *
 * Fields of patient data
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  $tbody = array();

  //$row = _("Last Update Date") . ":";
  //$row .= I18n::localDate($postVars["last_update_date"]);
  //$tbody[] = $row;

  $row = Form::strLabel("nif", _("Tax Identification Number (TIN)") . ":");
  $row .= Form::strText("nif", 20,
    isset($postVars["nif"]) ? $postVars["nif"] : null,
    isset($pageErrors["nif"]) ? array('error' => $pageErrors["nif"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("first_name", _("First Name") . ":", true);
  $row .= Form::strText("first_name", 25, $postVars["first_name"],
    array('error' => $pageErrors["first_name"])
  );
  $tbody[] = $row;

  $row = Form::strLabel("surname1", _("Surname 1") . ":", true);
  $row .= Form::strText("surname1", 30, $postVars["surname1"],
    array('error' => $pageErrors["surname1"])
  );
  $tbody[] = $row;

  $row = Form::strLabel("surname2", _("Surname 2") . ":", true);
  $row .= Form::strText("surname2", 30, $postVars["surname2"],
    array('error' => $pageErrors["surname2"])
  );
  $tbody[] = $row;

  $row = Form::strLabel("address", _("Address") . ":");
  $row .= Form::strTextArea("address", 3, 30, $postVars["address"]);
  $tbody[] = $row;

  $row = Form::strLabel("phone_contact", _("Phone Contact") . ":");
  $row .= Form::strTextArea("phone_contact", 3, 30, $postVars["phone_contact"]);
  $tbody[] = $row;

  $array = null;
  $array['V'] = _("Male");
  $array['H'] = _("Female");

  $row = Form::strLabel("sex", _("Sex") . ":");
  $row .= Form::strSelect("sex", $array, $postVars["sex"]);
  unset($array);
  $tbody[] = $row;

  $row = Form::strLabel("race", _("Race") . ":");
  $row .= Form::strText("race", 25,
    isset($postVars["race"]) ? $postVars["race"] : null,
    isset($pageErrors["race"]) ? array('error' => $pageErrors["race"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("year", _("Birth Date") . ":");
  $aux = explode("-", ( !empty($postVars["birth_date"]) ) ? $postVars["birth_date"] : '0000-00-00');
  $row .= Form::strText("year", 4, ((intval($aux[0]) != 0) ? intval($aux[0]) : ''));
  $row .= " - ";
  $row .= Form::strText("month", 2, ((intval($aux[1]) != 0) ? intval($aux[1]) : ''));
  $row .= " - ";
  $row .= Form::strText("day", 2, ((intval($aux[2]) != 0) ? intval($aux[2]) : ''));
  $row .= " " . _("(yyyy-mm-dd)");
  unset($aux);

  if ($pageErrors["birth_date"] != "")
  {
    $row .= HTML::strMessage($pageErrors["birth_date"], OPEN_MSG_ERROR, false);
  }
  $tbody[] = $row;

  $row = Form::strLabel("birth_place", _("Birth Place") . ":");
  $row .= Form::strText("birth_place", 40,
    isset($postVars["birth_place"]) ? $postVars["birth_place"] : null,
    isset($pageErrors["birth_place"]) ? array('error' => $pageErrors["birth_place"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("dyear", _("Decease Date") . ":");
  $aux = explode("-", ( !empty($postVars["decease_date"]) ) ? $postVars["decease_date"] : '0000-00-00');
  $row .= Form::strText("dyear", 4, ((intval($aux[0]) != 0) ? intval($aux[0]) : ''));
  $row .= " - ";
  $row .= Form::strText("dmonth", 2, ((intval($aux[1]) != 0) ? intval($aux[1]) : ''));
  $row .= " - ";
  $row .= Form::strText("dday", 2, ((intval($aux[2]) != 0) ? intval($aux[2]) : ''));
  $row .= " " . _("(yyyy-mm-dd)");
  unset($aux);

  if ($pageErrors["decease_date"] != "")
  {
    $row .= HTML::strMessage($pageErrors["decease_date"], OPEN_MSG_ERROR, false);
  }
  $tbody[] = $row;

  $row = Form::strLabel("nts", _("Sanitary Card Number (SCN)") . ":");
  $row .= Form::strText("nts", 30,
    isset($postVars["nts"]) ? $postVars["nts"] : null,
    isset($pageErrors["nts"]) ? array('error' => $pageErrors["nts"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("nss", _("National Health Service Number (NHSN)") . ":");
  $row .= Form::strText("nss", 30,
    isset($postVars["nss"]) ? $postVars["nss"] : null,
    isset($pageErrors["nss"]) ? array('error' => $pageErrors["nss"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("family_situation", _("Family Situation") . ":");
  $row .= Form::strTextArea("family_situation", 3, 30, $postVars["family_situation"]);
  $tbody[] = $row;

  $row = Form::strLabel("labour_situation", _("Labour Situation") . ":");
  $row .= Form::strTextArea("labour_situation", 3, 30, $postVars["labour_situation"]);
  $tbody[] = $row;

  $row = Form::strLabel("education", _("Education") . ":");
  $row .= Form::strTextArea("education", 3, 30, $postVars["education"]);
  $tbody[] = $row;

  $row = Form::strLabel("insurance_company", _("Insurance Company") . ":");
  $row .= Form::strText("insurance_company", 30,
    isset($postVars["insurance_company"]) ? $postVars["insurance_company"] : null,
    isset($pageErrors["insurance_company"]) ? array('error' => $pageErrors["insurance_company"]) : null
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

  $row .= Form::strSelect("id_member", $array, $postVars["id_member"]);
  unset($array);
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("button1", _("Submit"))
    . Form::strButton("return", _("Return"), "button", array('onclick' => 'parent.location=\'' . $returnLocation . '\''))
  );

  Form::fieldset($title, $tbody, $tfoot);
?>
