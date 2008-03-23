<?php
/**
 * theme_fields.php
 *
 * Fields of theme data
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_fields.php,v 1.21 2008/03/23 11:58:57 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  $tbody = array();

  $row = Form::label("theme_name", _("Theme Name") . ":", array('class' => 'required'));
  $row .= Form::text("theme_name",
    isset($formVar["theme_name"]) ? $formVar["theme_name"] : null,
    array(
      'size' => 50,
      'error' => isset($formError["theme_name"]) ? $formError["theme_name"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::label("css_file", _("CSS File") . ":", array('class' => 'required'));
  $row .= Form::text("css_file",
    isset($formVar["css_file"]) ? $formVar["css_file"] : null,
    array(
      'size' => 50,
      'error' => isset($formError["css_file"]) ? $formError["css_file"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::label("css_rules", _("CSS Rules") . ":", array('class' => 'required'));
  $row .= Form::textArea("css_rules",
    isset($formVar["css_rules"]) ? $formVar["css_rules"] : null,
    array(
      'rows' => 25,
      'cols' => 80,
      'error' => isset($formError["css_rules"]) ? $formError["css_rules"] : null
    )
  );
  $tbody[] = $row;

  $tfoot = array(
    Form::button("save", _("Submit"), array('type' => 'button', 'onclick' => 'editTheme();'))
    . Form::generateToken()
  );

  $options = array(
    'class' => 'large_area'
  );

  echo Form::fieldset($title, $tbody, $tfoot, $options);
?>
