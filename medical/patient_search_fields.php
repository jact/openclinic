<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_search_fields.php,v 1.1 2004/03/20 20:36:46 jact Exp $
 */

/**
 * patient_search_fields.php
 ********************************************************************
 * Change this
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 20/03/04 21:36
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }
?>

<div class="center">
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
        <td colspan="2" class="center">
          <?php
            echo '<label for="search_type">';
            echo _("Field") . ': ';
            echo "</label>\n";

            $array = null;
            $array[OPEN_SEARCH_SURNAME1] = _("Surname 1");
            $array[OPEN_SEARCH_SURNAME2] = _("Surname 2");
            $array[OPEN_SEARCH_FIRSTNAME] = _("First Name");
            $array[OPEN_SEARCH_NIF] = _("Tax Identification Number (TIN)");
            $array[OPEN_SEARCH_NTS] = _("Sanitary Card Number (SCN)");
            $array[OPEN_SEARCH_NSS] = _("National Health Service Number (NHSN)");
            $array[OPEN_SEARCH_BIRTHPLACE] = _("Birth Place");
            $array[OPEN_SEARCH_ADDRESS] = _("Address");
            $array[OPEN_SEARCH_PHONE] = _("Phone Contact");
            $array[OPEN_SEARCH_INSURANCE] = _("Insurance Company");
            $array[OPEN_SEARCH_COLLEGIATE] = _("Collegiate Number");

            showSelectArray("search_type", $array, OPEN_SEARCH_SURNAME1);
            unset($array);
          ?>
        </td>
      </tr>

      <tr>
        <td colspan="2">
          <?php
            showInputText("search_text", 40, 80);
            showInputButton("button1", _("Search"));
            showInputButton("button2", _("Clear Search"), "reset");
          ?>
        </td>
      </tr>

      <tr class="center">
        <td>
          <?php
            echo '<label for="logical">';
            echo _("Logical") . ': ';
            echo "</label>\n";

            $array = null;
            $array[OPEN_OR] = "OR";
            $array[OPEN_NOT] = "NOT";
            $array[OPEN_AND] = "AND"; // it makes sense in fields with two or more words

            showSelectArray("logical", $array, OPEN_OR);
            unset($array);
          ?>
        </td>

        <td>
          <?php
            echo '<label for="limit">';
            echo _("Limit") . ': ';
            echo "</label>\n";

            $array = null;
            $array["0"] = "";
            $array["10"] = 10;
            $array["20"] = 20;
            $array["50"] = 50;
            $array["100"] = 100;
            showSelectArray("limit", $array);
            unset($array);
          ?>
        </td>
      </tr>
    </tbody>
  </table>
</div>
