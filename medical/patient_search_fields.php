<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_search_fields.php,v 1.6 2004/10/17 14:57:03 jact Exp $
 */

/**
 * patient_search_fields.php
 ********************************************************************
 * Fields of patient's search
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

  $row = '<label for="search_type">' . _("Field") . ': ' . "</label>\n";

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

  $row .= htmlSelectArray("search_type", $array, OPEN_SEARCH_SURNAME1);
  unset($array);

  $tbody[] = array($row);

  $row = '* ' . htmlInputText("search_text", 40, 80);
  $row .= htmlInputButton("button1", _("Search"));
  $row .= htmlInputButton("button2", _("Clear Search"), "reset");

  $tbody[] = array($row);

  $row = '<label for="logical">' . _("Logical") . ': ' . "</label>\n";

  $array = null;
  $array[OPEN_OR] = "OR";
  $array[OPEN_NOT] = "NOT";
  $array[OPEN_AND] = "AND"; // it makes sense in fields with two or more words

  $row .= htmlSelectArray("logical", $array, OPEN_OR);
  unset($array);

  $row .= OPEN_SEPARATOR;

  $row .= '<label for="limit">' . _("Limit") . ': ' . "</label>\n";

  $array = null;
  $array["0"] = _("All");
  $array["10"] = 10;
  $array["20"] = 20;
  $array["50"] = 50;
  $array["100"] = 100;
  $row .= htmlSelectArray("limit", $array);
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
