<?php
/**
 * theme_fields.php
 *
 * Fields of theme data
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_fields.php,v 1.18 2006/12/28 16:17:50 jact Exp $
 * @author    jact <jachavar@gmail.com>
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
    Form::strButton("save", _("Submit"), "button", array('onclick' => 'editTheme();'))
    . Form::generateToken()
  );

  $options = array(
    'class' => 'largeArea'
  );

  Form::fieldset($title, $tbody, $tfoot, $options);
?>
