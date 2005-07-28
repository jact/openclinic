<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_fields.php,v 1.11 2005/07/28 17:46:27 jact Exp $
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

  $row = Form::strLabel("nif", _("Tax Identification Number (TIN)") . ":");
  $row .= OPEN_SEPARATOR;
  $row .= Form::strText("nif", "nif", 20, 20, $postVars["nif"], $pageErrors["nif"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  if ((isset($memberType) && $memberType == "D") || substr($postVars["member_type"], 0, 1) == "D")
  {
    $row = Form::strLabel("collegiate_number", _("Collegiate Number") . ":", true);
    $row .= OPEN_SEPARATOR;
    $row .= Form::strText("collegiate_number", "collegiate_number", 20, 20, $postVars["collegiate_number"], $pageErrors["collegiate_number"]);

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  }

  $row = Form::strLabel("first_name", _("First Name") . ":", true);
  $row .= OPEN_SEPARATOR;
  $row .= Form::strText("first_name", "first_name", 25, 25, $postVars["first_name"], $pageErrors["first_name"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("surname1", _("Surname 1") . ":", true);
  $row .= OPEN_SEPARATOR;
  $row .= Form::strText("surname1", "surname1", 30, 30, $postVars["surname1"], $pageErrors["surname1"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("surname2", _("Surname 2") . ":", true);
  $row .= OPEN_SEPARATOR;
  $row .= Form::strText("surname2", "surname2", 30, 30, $postVars["surname2"], $pageErrors["surname2"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("address", _("Address") . ":");
  $row .= OPEN_SEPARATOR;
  $row .= Form::strTextArea("address", "address", 2, 30, $postVars["address"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("phone_contact", _("Phone Contact") . ":");
  $row .= OPEN_SEPARATOR;
  $row .= Form::strTextArea("phone_contact", "phone_contact", 2, 30, $postVars["phone_contact"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("login", _("Login") . ":");
  $row .= OPEN_SEPARATOR;
  $row .= Form::strText("login", "login", 20, 20, $postVars["login"], $pageErrors["login"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $tfoot = array(
    Form::strButton("button1", "button1", _("Submit"))
    . Form::strButton("return", "return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array(
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  HTML::table($thead, $tbody, $tfoot, $options);
?>
