<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_family_fields.php,v 1.4 2004/10/17 14:57:03 jact Exp $
 */

/**
 * history_family_fields.php
 ********************************************************************
 * Fields of family antecedents
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['PATH_TRANSLATED'])
  {
    header("Location: ../index.php");
    exit();
  }

  $thead = array(
    $title
  );

  $tbody = array();

  $row = '<label for="parents_status_health">' . _("Parents Status Health") . ":" . "</label><br />\n";
  $row .= htmlTextArea("parents_status_health", 4, 90, $postVars["parents_status_health"]);

  $tbody[] = array($row);

  $row = '<label for="brothers_status_health">' . _("Brothers and Sisters Status Health") . ":" . "</label><br />\n";
  $row .= htmlTextArea("brothers_status_health", 4, 90, $postVars["brothers_status_health"]);

  $tbody[] = array($row);

  $row = '<label for="spouse_childs_status_health">' . _("Spouse and Childs Status Health") . ":" . "</label><br />\n";
  $row .= htmlTextArea("spouse_childs_status_health", 4, 90, $postVars["spouse_childs_status_health"]);

  $tbody[] = array($row);

  $row = '<label for="family_illness">' . _("Family Illness") . ":" . "</label><br />\n";
  $row .= htmlTextArea("family_illness", 4, 90, $postVars["family_illness"]);

  $tbody[] = array($row);

  $tfoot = array(
    htmlInputButton("button1", _("Update"))
    . htmlInputButton("button2", _("Reset"), "reset")
    . htmlInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array(
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  showTable($thead, $tbody, $tfoot, $options);
?>
