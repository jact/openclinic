<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_fields.php,v 1.13 2006/03/12 18:37:15 jact Exp $
 */

/**
 * theme_fields.php
 *
 * Fields of theme data
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  $tbody = array();

  $row = Form::strLabel("theme_name", _("Theme Name") . ":", true);
  $row .= Form::strText("theme_name", 50,
    isset($postVars["theme_name"]) ? $postVars["theme_name"] : null,
    isset($pageErrors["theme_name"]) ? array('error' => $pageErrors["theme_name"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("css_file", _("CSS File") . ":", true);
  $row .= Form::strText("css_file", 50,
    isset($postVars["css_file"]) ? $postVars["css_file"] : null,
    isset($pageErrors["css_file"]) ? array('error' => $pageErrors["css_file"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("css_rules", _("CSS Rules") . ":", true);
  $row .= Form::strTextArea("css_rules", 25, 80, isset($postVars["css_rules"]) ? $postVars["css_rules"] : null, array('error' => $pageErrors["css_rules"]));
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("button1", _("Submit"), "button", array('onclick' => 'editTheme()'))
    . Form::strButton("return", _("Return"), "button", array('onclick' => 'parent.location=\'' . $returnLocation . '\''))
  );

  $options = array(
    'class' => 'largeArea'
  );

  Form::fieldset($title, $tbody, $tfoot, $options);
?>
