<?php
/**
 * test_fields.php
 *
 * Fields of medical test
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: test_fields.php,v 1.21 2007/10/29 20:06:54 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  $tbody = array();

  $row = Form::strLabel("document_type", _("Document Type") . ":");
  $row .= Form::strText("document_type", 40,
    isset($formVar["document_type"]) ? $formVar["document_type"] : null,
    array(
      'maxlength' => 128,
      'error' => isset($formError["document_type"]) ? $formError["document_type"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::strLabel("path_filename", _("Path Filename") . ":", true);

  //$row .= Form::strHidden("MAX_FILE_SIZE", "70000");

  //$addendum['readonly'] = true; // does not work in IE, Mozilla
  isset($formError["path_filename"]) ? $addendum['error'] = $formError["path_filename"] : null;
  $row .= Form::strFile("path_filename",
    isset($formVar['path_filename']) ? $formVar['path_filename'] : null, 50,
    isset($addendum) ? $addendum : null
  );
  $row .= Form::strHidden('previous', $formVar['path_filename']);
  $row .= HTML::strTag('strong', $formVar['path_filename'], array('class' => 'previous_file'));
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("save", _("Submit"))
    . Form::generateToken()
  );

  Form::fieldset($title, $tbody, $tfoot);
?>
