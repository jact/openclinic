<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_search_fields.php,v 1.2 2004/04/24 14:52:14 jact Exp $
 */

/**
 * problem_search_fields.php
 ********************************************************************
 * Fields of medical problem's search
 ********************************************************************
 * Author: jact <jachavar@terra.es>
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
          <?php echo $headerWording2; ?>
        </th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <td colspan="2" class="center">
          <?php
            echo '<label for="search_type_problem">';
            echo _("Field") . ': ';
            echo "</label>\n";

            $array = null;
            $array[OPEN_SEARCH_WORDING] = _("Wording");
            $array[OPEN_SEARCH_SUBJECTIVE] = _("Subjective");
            $array[OPEN_SEARCH_OBJECTIVE] = _("Objective");
            $array[OPEN_SEARCH_APPRECIATION] = _("Appreciation");
            $array[OPEN_SEARCH_ACTIONPLAN] = _("Action Plan");
            $array[OPEN_SEARCH_PRESCRIPTION] = _("Prescription");

            showSelectArray("search_type_problem", $array, OPEN_SEARCH_WORDING);
            unset($array);
          ?>
        </td>
      </tr>

      <tr>
        <td colspan="2">
          <?php
            showInputText("search_text_problem", 40, 120);
            showInputButton("submit_problem", _("Search"));
            showInputButton("reset_problem", _("Clear Search"), "reset");
          ?>
        </td>
      </tr>

      <tr class="center">
        <td>
          <?php
            echo '<label for="logical_problem">';
            echo _("Logical") . ': ';
            echo "</label>\n";

            $array = null;
            $array[OPEN_OR] = "OR";
            $array[OPEN_NOT] = "NOT";
            $array[OPEN_AND] = "AND"; // it makes sense in fields with two or more words

            showSelectArray("logical_problem", $array, OPEN_OR);
            unset($array);
          ?>
        </td>

        <td>
          <?php
            echo '<label for="limit_problem">';
            echo _("Limit") . ': ';
            echo "</label>\n";

            $array = null;
            $array["0"] = "";
            $array["10"] = 10;
            $array["20"] = 20;
            $array["50"] = 50;
            $array["100"] = 100;
            showSelectArray("limit_problem", $array);
            unset($array);
          ?>
        </td>
      </tr>
    </tbody>
  </table>
</div>
