<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_fields.php,v 1.11 2004/10/17 14:57:03 jact Exp $
 */

/**
 * patient_fields.php
 ********************************************************************
 * Fields of patient data
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['PATH_TRANSLATED'])
  {
    header("Location: ../index.php");
    exit();
  }

  $thead = array(
    $title => array('colspan' => 2)
  );

  $tbody = array();

  //$row = _("Last Update Date") . ":";
  //$row .= OPEN_SEPARATOR;
  //$row .= localDate($postVars["last_update_date"]);

  //$tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="nif">' . _("Tax Identification Number (TIN)") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("nif", 20, 20, $postVars["nif"], $pageErrors["nif"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '* <label for="first_name" class="requiredField">' . _("First Name") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("first_name", 25, 25, $postVars["first_name"], $pageErrors["first_name"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '* <label for="surname1" class="requiredField">' . _("Surname 1") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("surname1", 30, 30, $postVars["surname1"], $pageErrors["surname1"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '* <label for="surname2" class="requiredField">' . _("Surname 2") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("surname2", 30, 30, $postVars["surname2"], $pageErrors["surname2"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="address">' . _("Address") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlTextArea("address", 3, 30, $postVars["address"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="phone_contact">' . _("Phone Contact") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlTextArea("phone_contact", 3, 30, $postVars["phone_contact"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="sex">' . _("Sex") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;

  $array = null;
  $array['V'] = _("Male");
  $array['H'] = _("Female");

  $row .= htmlSelectArray("sex", $array, $postVars["sex"]);
  unset($array);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="race">' . _("Race") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("race", 25, 25, $postVars["race"], $pageErrors["race"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="day">' . _("Birth Date") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;

  $aux = explode("-", $postVars["birth_date"]);
  $row .= htmlInputText("year", 4, 4, ((intval($aux[0]) != 0) ? intval($aux[0]) : ''));
  $row .= " - ";
  $row .= htmlInputText("month", 2, 2, ((intval($aux[1]) != 0) ? intval($aux[1]) : ''));
  $row .= " - ";
  $row .= htmlInputText("day", 2, 2, ((intval($aux[2]) != 0) ? intval($aux[2]) : ''));
  $row .= " " . _("(yyyy-mm-dd)");
  unset($aux);

  if ($pageErrors["birth_date"] != "")
  {
    $row .= showMessage($pageErrors["birth_date"], OPEN_MSG_ERROR);
  }

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="birth_place">' . _("Birth Place") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("birth_place", 40, 40, $postVars["birth_place"], $pageErrors["birth_place"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="dday">' . _("Decease Date") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;

  $aux = explode("-", $postVars["decease_date"]);
  $row .= htmlInputText("dyear", 4, 4, ((intval($aux[0]) != 0) ? intval($aux[0]) : ''));
  $row .= " - ";
  $row .= htmlInputText("dmonth", 2, 2, ((intval($aux[1]) != 0) ? intval($aux[1]) : ''));
  $row .= " - ";
  $row .= htmlInputText("dday", 2, 2, ((intval($aux[2]) != 0) ? intval($aux[2]) : ''));
  $row .= " " . _("(yyyy-mm-dd)");
  unset($aux);

  if ($pageErrors["decease_date"] != "")
  {
    $row .= showMessage($pageErrors["decease_date"], OPEN_MSG_ERROR);
  }

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="nts">' . _("Sanitary Card Number (SCN)") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("nts", 30, 30, $postVars["nts"], $pageErrors["nts"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="nss">' . _("National Health Service Number (NHSN)") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("nss", 30, 30, $postVars["nss"], $pageErrors["nss"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="family_situation">' . _("Family Situation") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlTextArea("family_situation", 3, 30, $postVars["family_situation"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="labour_situation">' . _("Labour Situation") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlTextArea("labour_situation", 3, 30, $postVars["labour_situation"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="education">' . _("Education") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlTextArea("education", 3, 30, $postVars["education"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="insurance_company">' . _("Insurance Company") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("insurance_company", 30, 30, $postVars["insurance_company"], $pageErrors["insurance_company"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="id_member">' . _("Doctor you are assigned to") . ":" . "</label>\n";
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

  $row .= htmlSelectArray("id_member", $array, $postVars["id_member"]);
  unset($array);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $tfoot = array(
    htmlInputButton("button1", _("Submit"))
    . htmlInputButton("button2", _("Reset"), "reset")
    . htmlInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array(
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  showTable($thead, $tbody, $tfoot, $options);
?>
