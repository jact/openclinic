<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_family_fields.php,v 1.9 2005/08/03 17:40:18 jact Exp $
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

  $thead = array(
    $title
  );

  $tbody = array();

  $row = Form::strLabel("parents_status_health", _("Parents Status Health") . ":") . "<br />\n";
  $row .= Form::strTextArea("parents_status_health", "parents_status_health", 4, 90, $postVars["parents_status_health"]);

  $tbody[] = array($row);

  $row = Form::strLabel("brothers_status_health", _("Brothers and Sisters Status Health") . ":") . "<br />\n";
  $row .= Form::strTextArea("brothers_status_health", "brothers_status_health", 4, 90, $postVars["brothers_status_health"]);

  $tbody[] = array($row);

  $row = Form::strLabel("spouse_childs_status_health", _("Spouse and Childs Status Health") . ":") . "<br />\n";
  $row .= Form::strTextArea("spouse_childs_status_health", "spouse_childs_status_health", 4, 90, $postVars["spouse_childs_status_health"]);

  $tbody[] = array($row);

  $row = Form::strLabel("family_illness", _("Family Illness") . ":") . "<br />\n";
  $row .= Form::strTextArea("family_illness", "family_illness", 4, 90, $postVars["family_illness"]);

  $tbody[] = array($row);

  $tfoot = array(
    Form::strButton("button1", "button1", _("Update"))
    . Form::strButton("return", "return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array(
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  HTML::table($thead, $tbody, $tfoot, $options);
?>
