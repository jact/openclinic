<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_fields.php,v 1.14 2006/03/12 18:30:14 jact Exp $
 */

/**
 * staff_fields.php
 *
 * Fields of staff member data
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  $tbody = array();

  $row = Form::strLabel("nif", _("Tax Identification Number (TIN)") . ":");
  $row .= Form::strText("nif", 20, $postVars["nif"], array('error' => $pageErrors["nif"]));
  $tbody[] = $row;

  if ((isset($memberType) && $memberType == "D") || substr($postVars["member_type"], 0, 1) == "D")
  {
    $row = Form::strLabel("collegiate_number", _("Collegiate Number") . ":", true);
    $row .= Form::strText("collegiate_number", 20, $postVars["collegiate_number"], array('error' => $pageErrors["collegiate_number"]));
    $tbody[] = $row;
  }

  $row = Form::strLabel("first_name", _("First Name") . ":", true);
  $row .= Form::strText("first_name", 25, $postVars["first_name"], array('error' => $pageErrors["first_name"]));
  $tbody[] = $row;

  $row = Form::strLabel("surname1", _("Surname 1") . ":", true);
  $row .= Form::strText("surname1", 30, $postVars["surname1"], array('error' => $pageErrors["surname1"]));
  $tbody[] = $row;

  $row = Form::strLabel("surname2", _("Surname 2") . ":", true);
  $row .= Form::strText("surname2", 30, $postVars["surname2"], array('error' => $pageErrors["surname2"]));
  $tbody[] = $row;

  $row = Form::strLabel("address", _("Address") . ":");
  $row .= Form::strTextArea("address", 2, 30, $postVars["address"]);
  $tbody[] = $row;

  $row = Form::strLabel("phone_contact", _("Phone Contact") . ":");
  $row .= Form::strTextArea("phone_contact", 2, 30, $postVars["phone_contact"]);
  $tbody[] = $row;

  $row = Form::strLabel("login", _("Login") . ":");
  $row .= Form::strText("login", 20, $postVars["login"], array('error' => $pageErrors["login"]));
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("button1", _("Submit"))
    . Form::strButton("return", _("Return"), "button", array('onclick' => 'parent.location=\'' . $returnLocation . '\'"'))
  );

  Form::fieldset($title, $tbody, $tfoot);
?>
