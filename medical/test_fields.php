<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: test_fields.php,v 1.2 2004/04/24 14:52:15 jact Exp $
 */

/**
 * test_fields.php
 ********************************************************************
 * Fields of medical test
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }
?>

<table>
  <thead>
    <tr>
      <th colspan="2">
        <?php echo $title; ?>
      </th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td>
        <label for="document_type"><?php echo _("Document Type") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("document_type", 40, 128, $postVars["document_type"], $pageErrors["document_type"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        * <label for="path_filename"><?php echo _("Path Filename") . ":"; ?></label>
      </td>

      <td>
        <?php
          //showInputHidden("MAX_FILE_SIZE", "70000");

          $len = strlen($postVars["path_filename"]);
          if ($len > 0)
          {
            showInputText("previous", $len, $len, $postVars['path_filename'], "", "text", true);
            echo "<br />\n";
          }

          showInputFile("path_filename", $postVars['path_filename'], 50);

          if (isset($pageErrors["path_filename"]))
          {
            echo '<br /><span class="error">' . $pageErrors["path_filename"] . "</span>\n";
          }
        ?>
      </td>
    </tr>

    <tr>
      <td class="center" colspan="2">
        <?php
          showInputButton("button1", _("Submit"));
          showInputButton("button2", _("Reset"), "reset");
          showInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"');
        ?>
      </td>
    </tr>
  </tbody>
</table>
