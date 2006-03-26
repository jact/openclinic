<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: test_fields.php,v 1.15 2006/03/26 15:20:50 jact Exp $
 */

/**
 * test_fields.php
 *
 * Fields of medical test
 *
 * @author jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

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
  $len = (isset($formVar["path_filename"]) ? strlen($formVar["path_filename"]) : 0);
  if ($len > 0)
  {
    $row .= Form::strText("previous", $len, $formVar['path_filename'],
      array('readonly')
    );
    $row .= "<br />\n";
  }

  $row .= Form::strFile("path_filename", isset($formVar['path_filename']) ? $formVar['path_filename'] : null, 50, isset($formError["path_filename"]) ? array('error' => $formError["path_filename"]) : null);
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("button1", _("Submit"))
    . Form::strButton("return", _("Return"), "button", array('onclick' => "parent.location='" . $returnLocation . "'"))
  );

  Form::fieldset($title, $tbody, $tfoot);
?>
