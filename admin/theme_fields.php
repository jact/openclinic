<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_fields.php,v 1.9 2005/07/28 17:46:27 jact Exp $
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

  $row = Form::strLabel("theme_name", _("Theme Name") . ":", true);
  $row .= OPEN_SEPARATOR;
  $row .= Form::strText("theme_name", "theme_name", 50, 50,
    isset($postVars["theme_name"]) ? $postVars["theme_name"] : null,
    isset($pageErrors["theme_name"]) ? $pageErrors["theme_name"] : null
  );

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("css_file", _("CSS File") . ":", true);
  $row .= OPEN_SEPARATOR;
  $row .= Form::strText("css_file", "css_file", 50, 50,
    isset($postVars["css_file"]) ? $postVars["css_file"] : null,
    isset($pageErrors["css_file"]) ? $pageErrors["css_file"] : null
  );

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("css_rules", _("CSS Rules") . ":", true);
  $row .= OPEN_SEPARATOR;
  $row .= Form::strTextArea("css_rules", "css_rules", 25, 80, isset($postVars["css_rules"]) ? $postVars["css_rules"] : null, $pageErrors["css_rules"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $tfoot = array(
    Form::strButton("button1", "button1", _("Submit"), "button", 'onclick="editTheme()"')
    . Form::strButton("return", "return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array(
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  HTML::table($thead, $tbody, $tfoot, $options);
?>
