<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_fields.php,v 1.1 2004/03/24 19:23:30 jact Exp $
 */

/**
 * problem_fields.php
 ********************************************************************
 * Fields of medical problem data
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 24/03/04 20:23
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
    <tr>
      <td>
        <?php echo _("Order Number") . ":"; ?>
      </td>

      <td>
        <?php echo $postVars["order_number"]; ?>
      </td>
    </tr>

    <tr>
      <td>
        <?php echo _("Opening Date") . ":"; ?>
      </td>

      <td>
        <?php echo $postVars["opening_date"]; ?>
      </td>
    </tr>

    <tr>
      <td>
        <?php echo _("Last Update Date") . ":"; ?>
      </td>

      <td>
        <?php echo $postVars["last_update_date"]; ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="collegiate_number"><?php echo _("Doctor who treated you") . ":"; ?></label>
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
            while ($staff = $staffQ->fetchStaff())
            {
              $array[$staff->getCollegiateNumber()] = $staff->getFirstName() . " " . $staff->getSurname1() . " " . $staff->getSurname2();
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
      <td>
        <label for="meeting_place"><?php echo _("Meeting Place") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("meeting_place", 40, 40, $postVars["meeting_place"], $pageErrors["meeting_place"]); ?>
      </td>
    </tr>

    <tr>
      <td colspan="2">
        * <label for="wording"><?php echo _("Wording") . ":"; ?></label><br />
        <?php
          showTextArea("wording", 4, 90, $postVars["wording"], $pageErrors["wording"]);
        ?>
      </td>
    </tr>

    <tr>
      <td colspan="2">
        <label for="subjective"><?php echo _("Subjective") . ":"; ?></label><br />
        <?php showTextArea("subjective", 4, 90, $postVars["subjective"]); ?>
      </td>
    </tr>

    <tr>
      <td colspan="2">
        <label for="objective"><?php echo _("Objective") . ":"; ?></label><br />
        <?php showTextArea("objective", 4, 90, $postVars["objective"]); ?>
      </td>
    </tr>

    <tr>
      <td colspan="2">
        <label for="appreciation"><?php echo _("Appreciation") . ":"; ?></label><br />
        <?php showTextArea("appreciation", 4, 90, $postVars["appreciation"]); ?>
      </td>
    </tr>

    <tr>
      <td colspan="2">
        <label for="action_plan"><?php echo _("Action Plan") . ":"; ?></label><br />
        <?php showTextArea("action_plan", 4, 90, $postVars["action_plan"]); ?>
      </td>
    </tr>

    <tr>
      <td colspan="2">
        <label for="prescription"><?php echo _("Prescription") . ":"; ?></label><br />
        <?php showTextArea("prescription", 4, 90, $postVars["prescription"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="closed_problem"><?php echo _("Closed Problem") . ":"; ?></label>
      </td>

      <td>
        <?php showCheckBox("closed_problem", "closed_problem", "closed", $postVars["closed_problem"] != ""); ?>
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
