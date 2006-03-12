<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: test_fields.php,v 1.13 2006/03/12 18:47:58 jact Exp $
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
  $row .= Form::strText("document_type", 40,
    isset($postVars["document_type"]) ? $postVars["document_type"] : null,
    array(
      'maxlength' => 128,
      'error' => isset($pageErrors["document_type"]) ? $pageErrors["document_type"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::strLabel("path_filename", _("Path Filename") . ":", true);

  //$row .= Form::strHidden("MAX_FILE_SIZE", "70000");
  $len = (isset($postVars["path_filename"]) ? strlen($postVars["path_filename"]) : 0);
  if ($len > 0)
  {
    $row .= Form::strText("previous", $len, $postVars['path_filename'],
      array('readonly')
    );
    $row .= "<br />\n";
  }

  $row .= Form::strFile("path_filename", isset($postVars['path_filename']) ? $postVars['path_filename'] : null, 50, isset($pageErrors["path_filename"]) ? array('error' => $pageErrors["path_filename"]) : null);
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("button1", _("Submit"))
    . Form::strButton("return", _("Return"), "button", array('onclick' => 'parent.location=\'' . $returnLocation . '\''))
  );

  Form::fieldset($title, $tbody, $tfoot);
?>
