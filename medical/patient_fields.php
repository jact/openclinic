<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_fields.php,v 1.3 2004/04/24 17:57:07 jact Exp $
 */

/**
 * patient_fields.php
 ********************************************************************
 * Fields of patient data
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }
?>

<table>
  <thead>
    <tr>
      <th colspan="2">
        <?php echo $title; ?>
      </th>
    </tr>
  </thead>

  <tbody>
    <!--tr>
      <td-->
        <?php //echo _("Last Update Date") . ":"; ?>
      <!--/td>

      <td-->
        <?php //echo $postVars["last_update_date"]; ?>
      <!--/td>
    </tr-->

    <tr>
      <td>
        <label for="nif"><?php echo _("Tax Identification Number (TIN)") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("nif", 20, 20, $postVars["nif"], $pageErrors["nif"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        * <label for="first_name"><?php echo _("First Name") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("first_name", 25, 25, $postVars["first_name"], $pageErrors["first_name"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        * <label for="surname1"><?php echo _("Surname 1") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("surname1", 30, 30, $postVars["surname1"], $pageErrors["surname1"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        * <label for="surname2"><?php echo _("Surname 2") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("surname2", 30, 30, $postVars["surname2"], $pageErrors["surname2"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="address"><?php echo _("Address") . ":"; ?></label>
      </td>

      <td>
        <?php showTextArea("address", 3, 30, $postVars["address"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="phone_contact"><?php echo _("Phone Contact") . ":"; ?></label>
      </td>

      <td>
        <?php showTextArea("phone_contact", 3, 30, $postVars["phone_contact"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="sex"><?php echo _("Sex") . ":"; ?></label>
      </td>

      <td>
        <?php
          $array = null;
          $array['V'] = _("Male");
          $array['H'] = _("Female");

          showSelectArray("sex", $array, $postVars["sex"]);
          unset($array);
        ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="race"><?php echo _("Race") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("race", 25, 25, $postVars["race"], $pageErrors["race"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="day"><?php echo _("Birth Date") . ":"; ?></label>
      </td>

      <td>
        <?php
          $aux = explode("-", $postVars["birth_date"]);
          showInputText("day", 2, 2, ((intval($aux[0]) != 0) ? intval($aux[0]) : ''));
          echo " / ";
          showInputText("month", 2, 2, ((intval($aux[1]) != 0) ? intval($aux[1]) : ''));
          echo " / ";
          showInputText("year", 4, 4, ((intval($aux[2]) != 0) ? intval($aux[2]) : ''));
          echo " (dd/mm/yyyy)";
          unset($aux);

          if ($pageErrors["birth_date"] != "")
          {
            echo '<br /><span class="error">' . $pageErrors["birth_date"] . "</span>\n";
          }

          showInputHidden("birth_date");
        ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="birth_place"><?php echo _("Birth Place") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("birth_place", 40, 40, $postVars["birth_place"], $pageErrors["birth_place"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="dday"><?php echo _("Decease Date") . ":"; ?></label>
      </td>

      <td>
        <?php
          $aux = explode("-", $postVars["decease_date"]);
          showInputText("dday", 2, 2, ((intval($aux[0]) != 0) ? intval($aux[0]) : ''));
          echo " / ";
          showInputText("dmonth", 2, 2, ((intval($aux[1]) != 0) ? intval($aux[1]) : ''));
          echo " / ";
          showInputText("dyear", 4, 4, ((intval($aux[2]) != 0) ? intval($aux[2]) : ''));
          echo " (dd/mm/yyyy)";
          unset($aux);

          if ($pageErrors["decease_date"] != "")
          {
            echo '<br /><span class="error">' . $pageErrors["decease_date"] . "</span>\n";
          }

          showInputHidden("decease_date");
        ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="nts"><?php echo _("Sanitary Card Number (SCN)") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("nts", 30, 30, $postVars["nts"], $pageErrors["nts"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="nss"><?php echo _("National Health Service Number (NHSN)") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("nss", 30, 30, $postVars["nss"], $pageErrors["nss"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="family_situation"><?php echo _("Family Situation") . ":"; ?></label>
      </td>

      <td>
        <?php showTextArea("family_situation", 3, 30, $postVars["family_situation"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="labour_situation"><?php echo _("Labour Situation") . ":"; ?></label>
      </td>

      <td>
        <?php showTextArea("labour_situation", 3, 30, $postVars["labour_situation"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="education"><?php echo _("Education") . ":"; ?></label>
      </td>

      <td>
        <?php showTextArea("education", 3, 30, $postVars["education"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="insurance_company"><?php echo _("Insurance Company") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("insurance_company", 30, 30, $postVars["insurance_company"], $pageErrors["insurance_company"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="collegiate_number"><?php echo _("Doctor you are assigned to") . ":"; ?></label>
      </td>

      <td>
        <?php
          $staffQ = new Staff_Query();
          $staffQ->connect();
          if ($staffQ->errorOccurred())
          {
            showQueryError($staffQ);
          }

          $numRows = $staffQ->selectType('D');
          if ($staffQ->errorOccurred())
          {
            $staffQ->close();
            showQueryError($staffQ);
          }

          $array = null;
          $array[""] = ""; // to permit null value
          if ($numRows)
          {
            while ($row = $staffQ->fetchStaff())
            {
              $array[$row->getCollegiateNumber()] = $row->getFirstName() . " " . $row->getSurname1() . " " . $row->getSurname2();
            }
            $staffQ->freeResult();
          }
          $staffQ->close();
          unset($staffQ);

          showSelectArray("collegiate_number", $array, $postVars["collegiate_number"]);
          unset($array);
        ?>
      </td>
    </tr>

    <tr>
      <td class="center" colspan="2">
        <?php
          showInputButton("button1", _("Submit"));
          showInputButton("button2", _("Reset"), "reset");
          showInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"');
        ?>
      </td>
    </tr>
  </tbody>
</table>
