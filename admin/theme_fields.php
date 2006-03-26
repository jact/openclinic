<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_fields.php,v 1.15 2006/03/26 14:47:34 jact Exp $
 */

/**
 * theme_fields.php
 *
 * Fields of theme data
 *
 * @author jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  $tbody = array();

  $row = Form::strLabel("theme_name", _("Theme Name") . ":", true);
  $row .= Form::strText("theme_name", 50,
    isset($formVar["theme_name"]) ? $formVar["theme_name"] : null,
    isset($formError["theme_name"]) ? array('error' => $formError["theme_name"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("css_file", _("CSS File") . ":", true);
  $row .= Form::strText("css_file", 50,
    isset($formVar["css_file"]) ? $formVar["css_file"] : null,
    isset($formError["css_file"]) ? array('error' => $formError["css_file"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("css_rules", _("CSS Rules") . ":", true);
  $row .= Form::strTextArea("css_rules", 25, 80, isset($formVar["css_rules"]) ? $formVar["css_rules"] : null,
    isset($formError["css_rules"]) ? array('error' => $formError["css_rules"]) : null
  );
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
