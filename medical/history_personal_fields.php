<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_personal_fields.php,v 1.1 2004/03/20 20:40:04 jact Exp $
 */

/**
 * history_personal_fields.php
 ********************************************************************
 * Fields of personal antecedents
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 20/03/04 21:40
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
      <th>
        <?php echo $title; ?>
      </th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td>
        <label for="birth_growth"><?php echo _("Birth and Growth") . ":"; ?></label><br />
        <?php showTextArea("birth_growth", 4, 90, $postVars["birth_growth"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="growth_sexuality"><?php echo _("Growth and Sexuality") . ":"; ?></label><br />
        <?php showTextArea("growth_sexuality", 4, 90, $postVars["growth_sexuality"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="feed"><?php echo _("Feed") . ":"; ?></label><br />
        <?php showTextArea("feed", 4, 90, $postVars["feed"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="habits"><?php echo _("Habits") . ":"; ?></label><br />
        <?php showTextArea("habits", 4, 90, $postVars["habits"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="peristaltic_conditions"><?php echo _("Peristaltic Conditions") . ":"; ?></label><br />
        <?php showTextArea("peristaltic_conditions", 4, 90, $postVars["peristaltic_conditions"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="psychological"><?php echo _("Psychological Conditions") . ":"; ?></label><br />
        <?php showTextArea("psychological", 4, 90, $postVars["psychological"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="children_complaint"><?php echo _("Children Complaint") . ":"; ?></label><br />
        <?php showTextArea("children_complaint", 4, 90, $postVars["children_complaint"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="venereal_disease"><?php echo _("Venereal Disease") . ":"; ?></label><br />
        <?php showTextArea("venereal_disease", 4, 90, $postVars["venereal_disease"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="accident_surgical_operation"><?php echo _("Accidents and Surgical Operations") . ":"; ?></label><br />
        <?php showTextArea("accident_surgical_operation", 4, 90, $postVars["accident_surgical_operation"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="medicinal_intolerance"><?php echo _("Medicinal Intolerance") . ":"; ?></label><br />
        <?php showTextArea("medicinal_intolerance", 4, 90, $postVars["medicinal_intolerance"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="mental_illness"><?php echo _("Mental Illness") . ":"; ?></label><br />
        <?php showTextArea("mental_illness", 4, 90, $postVars["mental_illness"]); ?>
      </td>
    </tr>

    <tr>
      <td class="center" colspan="2">
        <?php
          showInputButton("button1", _("Update"));
          showInputButton("button2", _("Reset"), "reset");
          showInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"');
        ?>
      </td>
    </tr>
  </tbody>
</table>
