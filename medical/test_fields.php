<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: test_fields.php,v 1.12 2005/08/17 16:52:53 jact Exp $
 */

/**
 * test_fields.php
 *
 * Fields of medical test
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  $tbody = array();

  $row = Form::strLabel("document_type", _("Document Type") . ":");
  $row .= Form::strText("document_type", "document_type", 40, 128,
    isset($postVars["document_type"]) ? $postVars["document_type"] : null,
    isset($pageErrors["document_type"]) ? $pageErrors["document_type"] : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("path_filename", _("Path Filename") . ":", true);

  //$row .= Form::strHidden("MAX_FILE_SIZE", "MAX_FILE_SIZE", "70000");
  $len = (isset($postVars["path_filename"]) ? strlen($postVars["path_filename"]) : 0);
  if ($len > 0)
  {
    $row .= Form::strText("previous", "previous", $len, $len, $postVars['path_filename'], "", "text", true);
    $row .= "<br />\n";
  }

  $row .= Form::strFile("path_filename", "path_filename", isset($postVars['path_filename']) ? $postVars['path_filename'] : null, 50, "", isset($pageErrors["path_filename"]) ? $pageErrors["path_filename"] : "");
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("button1", "button1", _("Submit"))
    . Form::strButton("return", "return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  Form::fieldset($title, $tbody, $tfoot);
?>
