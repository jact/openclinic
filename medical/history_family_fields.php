<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_family_fields.php,v 1.14 2006/03/26 15:13:08 jact Exp $
 */

/**
 * history_family_fields.php
 *
 * Fields of family antecedents
 *
 * @author jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  $tbody = array();

  $row = Form::strLabel("parents_status_health", _("Parents Status Health") . ":");
  $row .= Form::strTextArea("parents_status_health", 4, 90, $formVar["parents_status_health"]);
  $tbody[] = $row;

  $row = Form::strLabel("brothers_status_health", _("Brothers and Sisters Status Health") . ":");
  $row .= Form::strTextArea("brothers_status_health", 4, 90, $formVar["brothers_status_health"]);
  $tbody[] = $row;

  $row = Form::strLabel("spouse_childs_status_health", _("Spouse and Childs Status Health") . ":");
  $row .= Form::strTextArea("spouse_childs_status_health", 4, 90, $formVar["spouse_childs_status_health"]);
  $tbody[] = $row;

  $row = Form::strLabel("family_illness", _("Family Illness") . ":");
  $row .= Form::strTextArea("family_illness", 4, 90, $formVar["family_illness"]);
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("button1", _("Update"))
    . Form::strButton("return", _("Return"), "button", array('onclick' => "parent.location='" . $returnLocation . "'"))
  );

  $options = array(
    'class' => 'largeArea'
  );

  Form::fieldset($title, $tbody, $tfoot, $options);
?>
