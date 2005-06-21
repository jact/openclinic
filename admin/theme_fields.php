<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_fields.php,v 1.7 2005/06/21 18:22:46 jact Exp $
 */

/**
 * theme_fields.php
 *
 * Fields of theme data
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['PATH_TRANSLATED']))
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
  $row .= htmlInputText("theme_name", 50, 50,
    isset($postVars["theme_name"]) ? $postVars["theme_name"] : null,
    isset($pageErrors["theme_name"]) ? $pageErrors["theme_name"] : null
  );

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '* <label for="css_file" class="requiredField">' . _("CSS File") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("css_file", 50, 50,
    isset($postVars["css_file"]) ? $postVars["css_file"] : null,
    isset($pageErrors["css_file"]) ? $pageErrors["css_file"] : null
  );

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '* <label for="css_rules" class="requiredField">' . _("CSS Rules") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlTextArea("css_rules", 25, 80, isset($postVars["css_rules"]) ? $postVars["css_rules"] : null, $pageErrors["css_rules"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $tfoot = array(
    htmlInputButton("button1", _("Submit"), "button", 'onclick="editTheme()"')
    . htmlInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array(
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  showTable($thead, $tbody, $tfoot, $options);
?>
