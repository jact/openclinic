<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_fields.php,v 1.3 2004/07/14 18:24:47 jact Exp $
 */

/**
 * staff_fields.php
 ********************************************************************
 * Fields of staff member data
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
    <tr>
      <td>
        <label for="nif"><?php echo _("Tax Identification Number (TIN)") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("nif", 20, 20, $postVars["nif"], $pageErrors["nif"]); ?>
      </td>
    </tr>

<?php
  debug($postVars);

  if ((isset($memberType) && $memberType == "D") || substr($postVars["member_type"], 0, 1) == "D")
  {
?>
    <tr>
      <td>
        * <label for="collegiate_number"><?php echo _("Collegiate Number") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("collegiate_number", 20, 20, $postVars["collegiate_number"], $pageErrors["collegiate_number"]); ?>
      </td>
    </tr>
<?php
  }
?>

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
        <?php showTextArea("address", 2, 30, $postVars["address"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="phone_contact"><?php echo _("Phone Contact") . ":"; ?></label>
      </td>

      <td>
        <?php showTextArea("phone_contact", 2, 30, $postVars["phone_contact"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="login"><?php echo _("Login") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("login", 20, 20, $postVars["login"], $pageErrors["login"]); ?>
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
