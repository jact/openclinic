<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: history_personal_fields.php,v 1.4 2004/10/17 14:57:03 jact Exp $
 */

/**
 * history_personal_fields.php
 ********************************************************************
 * Fields of personal antecedents
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

  $row = '<label for="birth_growth">' . _("Birth and Growth") . ":" . "</label><br />\n";
  $row .= htmlTextArea("birth_growth", 4, 90, $postVars["birth_growth"]);

  $tbody[] = array($row);

  $row = '<label for="growth_sexuality">' . _("Growth and Sexuality") . ":" . "</label><br />\n";
  $row .= htmlTextArea("growth_sexuality", 4, 90, $postVars["growth_sexuality"]);

  $tbody[] = array($row);

  $row = '<label for="feed">' . _("Feed") . ":" . "</label><br />\n";
  $row .= htmlTextArea("feed", 4, 90, $postVars["feed"]);

  $tbody[] = array($row);

  $row = '<label for="habits">' . _("Habits") . ":" . "</label><br />\n";
  $row .= htmlTextArea("habits", 4, 90, $postVars["habits"]);

  $tbody[] = array($row);

  $row = '<label for="peristaltic_conditions">' . _("Peristaltic Conditions") . ":" . "</label><br />\n";
  $row .= htmlTextArea("peristaltic_conditions", 4, 90, $postVars["peristaltic_conditions"]);

  $tbody[] = array($row);

  $row = '<label for="psychological">' . _("Psychological Conditions") . ":" . "</label><br />\n";
  $row .= htmlTextArea("psychological", 4, 90, $postVars["psychological"]);

  $tbody[] = array($row);

  $row = '<label for="children_complaint">' . _("Children Complaint") . ":" . "</label><br />\n";
  $row .= htmlTextArea("children_complaint", 4, 90, $postVars["children_complaint"]);

  $tbody[] = array($row);

  $row = '<label for="venereal_disease">' . _("Venereal Disease") . ":" . "</label><br />\n";
  $row .= htmlTextArea("venereal_disease", 4, 90, $postVars["venereal_disease"]);

  $tbody[] = array($row);

  $row = '<label for="accident_surgical_operation">' . _("Accidents and Surgical Operations") . ":" . "</label><br />\n";
  $row .= htmlTextArea("accident_surgical_operation", 4, 90, $postVars["accident_surgical_operation"]);

  $tbody[] = array($row);

  $row = '<label for="medicinal_intolerance">' . _("Medicinal Intolerance") . ":" . "</label><br />\n";
  $row .= htmlTextArea("medicinal_intolerance", 4, 90, $postVars["medicinal_intolerance"]);

  $tbody[] = array($row);

  $row = '<label for="mental_illness">' . _("Mental Illness") . ":" . "</label><br />\n";
  $row .= htmlTextArea("mental_illness", 4, 90, $postVars["mental_illness"]);

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
