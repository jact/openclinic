<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: profile_fields.php,v 1.1 2004/03/20 20:41:48 jact Exp $
 */

/**
 * profile_fields.php
 ********************************************************************
 * Fields of profile data
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 20/03/04 21:41
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
        <label for="description"><?php echo _("Description") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("description", 40, 40, $postVars["description"], $pageErrors["description"]); ?>
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
