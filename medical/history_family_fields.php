<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_family_fields.php,v 1.1 2004/03/20 20:39:52 jact Exp $
 */

/**
 * history_family_fields.php
 ********************************************************************
 * Fields of family antecedents
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 20/03/04 21:39
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
        <label for="parents_status_health"><?php echo _("Parents Status Health") . ":"; ?></label><br />
        <?php showTextArea("parents_status_health", 4, 90, $postVars["parents_status_health"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="brothers_status_health"><?php echo _("Brothers and Sisters Status Health") . ":"; ?></label><br />
        <?php showTextArea("brothers_status_health", 4, 90, $postVars["brothers_status_health"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="spouse_childs_status_health"><?php echo _("Spouse and Childs Status Health") . ":"; ?></label><br />
        <?php showTextArea("spouse_childs_status_health", 4, 90, $postVars["spouse_childs_status_health"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="family_illness"><?php echo _("Family Illness") . ":"; ?></label><br />
        <?php showTextArea("family_illness", 4, 90, $postVars["family_illness"]); ?>
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
