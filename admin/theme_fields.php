<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_fields.php,v 1.3 2004/08/03 11:26:54 jact Exp $
 */

/**
 * theme_fields.php
 ********************************************************************
 * Fields of theme data
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

  $thead = array(
    $title => array('colspan' => 2)
  );

  $tbody = array();

  $row = '* <label for="theme_name" class="requiredField">' . _("Theme Name") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("theme_name", 50, 50, $postVars["theme_name"], $pageErrors["theme_name"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '* <label for="css_file" class="requiredField">' . _("CSS File") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("css_file", 50, 50, $postVars["css_file"], $pageErrors["css_file"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '* <label for="css_rules" class="requiredField">' . _("CSS Rules") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlTextArea("css_rules", 25, 80, $postVars["css_rules"], $pageErrors["css_rules"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $tfoot = array(
    htmlInputButton("button1", _("Submit"), "button", 'onclick="editTheme()"')
    . htmlInputButton("button2", _("Reset"), "reset")
    . htmlInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array(
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  showTable($thead, $tbody, $tfoot, $options);
?>
