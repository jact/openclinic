<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_search_fields.php,v 1.7 2004/10/17 14:57:03 jact Exp $
 */

/**
 * problem_search_fields.php
 ********************************************************************
 * Fields of medical problem's search
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * @since 0.4
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['PATH_TRANSLATED'])
  {
    header("Location: ../index.php");
    exit();
  }

  $thead = array(
    $headerWording2 => array('colspan' => 2)
  );

  $tbody = array();

  $row = '<label for="search_type_problem">' . _("Field") . ': ' . "</label>\n";

  $array = null;
  $array[OPEN_SEARCH_WORDING] = _("Wording");
  $array[OPEN_SEARCH_SUBJECTIVE] = _("Subjective");
  $array[OPEN_SEARCH_OBJECTIVE] = _("Objective");
  $array[OPEN_SEARCH_APPRECIATION] = _("Appreciation");
  $array[OPEN_SEARCH_ACTIONPLAN] = _("Action Plan");
  $array[OPEN_SEARCH_PRESCRIPTION] = _("Prescription");

  $row .= htmlSelectArray("search_type_problem", $array, OPEN_SEARCH_WORDING);
  unset($array);

  $tbody[] = array($row);

  $row = '* ' . htmlInputText("search_text_problem", 40, 120);
  $row .= htmlInputButton("submit_problem", _("Search"));
  $row .= htmlInputButton("reset_problem", _("Clear Search"), "reset");

  $tbody[] = array($row);

  $row = '<label for="logical_problem">' . _("Logical") . ': ' . "</label>\n";

  $array = null;
  $array[OPEN_OR] = "OR";
  $array[OPEN_NOT] = "NOT";
  $array[OPEN_AND] = "AND"; // it makes sense in fields with two or more words

  $row .= htmlSelectArray("logical_problem", $array, OPEN_OR);
  unset($array);

  $row .= OPEN_SEPARATOR;
  $row .= '<label for="limit_problem">' . _("Limit") . ': ' . "</label>\n";

  $array = null;
  $array["0"] = _("All");
  $array["10"] = 10;
  $array["20"] = 20;
  $array["50"] = 50;
  $array["100"] = 100;

  $row .= htmlSelectArray("limit_problem", $array);
  unset($array);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $options = array(
    0 => array('align' => 'center'),
    1 => array('align' => 'center'),
    'r0' => array('colspan' => 2),
    'r1' => array('colspan' => 2),
    'shaded' => false,
    'align' => 'center'
  );

  showTable($thead, $tbody, null, $options);
?>
