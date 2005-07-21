<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_family_fields.php,v 1.7 2005/07/21 16:56:58 jact Exp $
 */

/**
 * history_family_fields.php
 *
 * Fields of family antecedents
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['PATH_TRANSLATED']))
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
    . htmlInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array(
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  HTML::table($thead, $tbody, $tfoot, $options);
?>
