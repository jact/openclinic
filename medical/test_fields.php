<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: test_fields.php,v 1.6 2004/10/18 17:24:04 jact Exp $
 */

/**
 * test_fields.php
 ********************************************************************
 * Fields of medical test
 ********************************************************************
 * Author: jact <jachavar@terra.es>
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

  $row = '<label for="document_type">' . _("Document Type") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("document_type", 40, 128, $postVars["document_type"], $pageErrors["document_type"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '* <label for="path_filename" class="requiredField">' . _("Path Filename") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;

  //$row .= htmlInputHidden("MAX_FILE_SIZE", "70000");
  $len = strlen($postVars["path_filename"]);
  if ($len > 0)
  {
    $row .= htmlInputText("previous", $len, $len, $postVars['path_filename'], "", "text", true);
    $row .= "<br />\n";
  }

  $row .= htmlInputFile("path_filename", $postVars['path_filename'], 50);

  if (isset($pageErrors["path_filename"]))
  {
    $row .= htmlMessage($pageErrors["path_filename"], OPEN_MSG_ERROR);
  }

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $tfoot = array(
    htmlInputButton("button1", _("Submit"))
    . htmlInputButton("button2", _("Reset"), "reset")
    . htmlInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array(
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  showTable($thead, $tbody, $tfoot, $options);
?>
