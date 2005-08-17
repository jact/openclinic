<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_family_fields.php,v 1.10 2005/08/17 16:52:53 jact Exp $
 */

/**
 * history_family_fields.php
 *
 * Fields of family antecedents
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  $tbody = array();

  $row = Form::strLabel("parents_status_health", _("Parents Status Health") . ":");
  $row .= Form::strTextArea("parents_status_health", "parents_status_health", 4, 90, $postVars["parents_status_health"]);
  $tbody[] = $row;

  $row = Form::strLabel("brothers_status_health", _("Brothers and Sisters Status Health") . ":");
  $row .= Form::strTextArea("brothers_status_health", "brothers_status_health", 4, 90, $postVars["brothers_status_health"]);
  $tbody[] = $row;

  $row = Form::strLabel("spouse_childs_status_health", _("Spouse and Childs Status Health") . ":");
  $row .= Form::strTextArea("spouse_childs_status_health", "spouse_childs_status_health", 4, 90, $postVars["spouse_childs_status_health"]);
  $tbody[] = $row;

  $row = Form::strLabel("family_illness", _("Family Illness") . ":");
  $row .= Form::strTextArea("family_illness", "family_illness", 4, 90, $postVars["family_illness"]);
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("button1", "button1", _("Update"))
    . Form::strButton("return", "return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  Form::fieldset($title, $tbody, $tfoot);
?>
