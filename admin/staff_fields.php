<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_fields.php,v 1.10 2005/07/21 16:55:57 jact Exp $
 */

/**
 * staff_fields.php
 *
 * Fields of staff member data
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['PATH_TRANSLATED']))
  {
    header("Location: ../index.php");
    exit();
  }

  //Error::debug($postVars);

  $thead = array(
    $title => array('colspan' => 2)
  );

  $tbody = array();

  $row = '<label for="nif">' . _("Tax Identification Number (TIN)") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("nif", 20, 20, $postVars["nif"], $pageErrors["nif"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  if ((isset($memberType) && $memberType == "D") || substr($postVars["member_type"], 0, 1) == "D")
  {
    $row = '* <label for="collegiate_number" class="requiredField">' . _("Collegiate Number") . ":" . "</label>\n";
    $row .= OPEN_SEPARATOR;
    $row .= htmlInputText("collegiate_number", 20, 20, $postVars["collegiate_number"], $pageErrors["collegiate_number"]);

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  }

  $row = '* <label for="first_name" class="requiredField">' . _("First Name") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("first_name", 25, 25, $postVars["first_name"], $pageErrors["first_name"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '* <label for="surname1" class="requiredField">' . _("Surname 1") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("surname1", 30, 30, $postVars["surname1"], $pageErrors["surname1"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '* <label for="surname2" class="requiredField">' . _("Surname 2") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("surname2", 30, 30, $postVars["surname2"], $pageErrors["surname2"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="address">' . _("Address") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlTextArea("address", 2, 30, $postVars["address"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="phone_contact">' . _("Phone Contact") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlTextArea("phone_contact", 2, 30, $postVars["phone_contact"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="login">' . _("Login") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("login", 20, 20, $postVars["login"], $pageErrors["login"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $tfoot = array(
    htmlInputButton("button1", _("Submit"))
    . htmlInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array(
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  HTML::table($thead, $tbody, $tfoot, $options);
?>
