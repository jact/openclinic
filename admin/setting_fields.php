<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: setting_fields.php,v 1.3 2004/05/15 17:20:07 jact Exp $
 */

/**
 * setting_fields.php
 ********************************************************************
 * Fields of config settings data
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
        <?php echo _("Edit Config Settings"); ?>
      </th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td>
        <label for="clinic_name"><?php echo _("Clinic Name") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("clinic_name", 40, 128, $postVars["clinic_name"], $pageErrors["clinic_name"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="clinic_image_url"><?php echo _("Clinic Image") . ":"; ?></label>
      </td>

      <td>
        <?php
          $dir = "../images/";
          $handle = opendir($dir);
          $array = null;
          $ext = array("bmp", "gif", "jpe", "jpeg", "jpg", "png");
          while (($file = readdir($handle)) != false)
          {
            $aux = explode(".", $file);
            if (in_array($aux[1], $ext) && is_file($dir . $file))
            {
              $array["$file"] = $file;
            }
          }
          closedir($handle);
          asort($array);

          showSelectArray("clinic_image_url", $array, basename($postVars["clinic_image_url"]));
          unset($array);
          unset($ext);

          echo '<br />' . _("(must be in /images/ directory)");
        ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="use_image"><?php echo _("Use Image in place of Name") . ":"; ?></label>
      </td>

      <td>
        <?php showCheckBox("use_image", "use_image", 1, $postVars["use_image"] != ""); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="clinic_hours"><?php echo _("Clinic Hours") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("clinic_hours", 40, 128, $postVars["clinic_hours"], $pageErrors["clinic_hours"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="clinic_address"><?php echo _("Clinic Address") . ":"; ?></label>
      </td>

      <td>
        <?php showTextArea("clinic_address", 3, 30, $postVars["clinic_address"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="clinic_phone"><?php echo _("Clinic Phone") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("clinic_phone", 40, 40, $postVars["clinic_phone"], $pageErrors["clinic_phone"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="clinic_url"><?php echo _("Clinic URL") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("clinic_url", 40, 300, $postVars["clinic_url"], $pageErrors["clinic_url"]); ?>
      </td>
    </tr>

  <?php
    if (defined("OPEN_DEMO") && !OPEN_DEMO)
    {
  ?>
    <tr>
      <td>
        <label for="language"><?php echo _("Language") . ":"; ?></label>
      </td>

      <td>
        <?php
          $dir = "../locale/";
          $handle = opendir($dir);
          $array = null;
          while (($file = readdir($handle)) != false)
          {
            if ($file != 'CVS' && $file != '.' && $file != '..' && is_dir($dir . $file))
            {
              $array["$file"] = $file;
            }
          }
          closedir($handle);
          showSelectArray("language", $array, $postVars["language"]);
          unset($array);
        ?>
      </td>
    </tr>
  <?php
    }
    else
    {
      echo '<tr><td colspan="2">';
      showInputHidden("language", "en");
      echo "</td></tr>\n";
    } // end if
  ?>

    <tr>
      <td>
        <label for="id_theme"><?php echo _("Theme by default") . ":"; ?></label>
      </td>

      <td>
        <?php showSelect("theme_tbl", "id_theme", $postVars["id_theme"], "theme_name"); ?>
      </td>
    </tr>

    <tr>
      <td>
        * <label for="session_timeout"><?php echo _("Session Timeout") . ":"; ?></label>
      </td>

      <td>
        <?php
          showInputText("session_timeout", 3, 3, $postVars["session_timeout"], $pageErrors["session_timeout"]);
          echo _("minutes");
        ?>
      </td>
    </tr>

    <tr>
      <td>
        * <label for="items_per_page"><?php echo _("Search Results") . ":"; ?></label>
      </td>

      <td>
        <?php
          showInputText("items_per_page", 2, 2, $postVars["items_per_page"], $pageErrors["items_per_page"]);
          echo _("items per page") . "**";
        ?>
      </td>
    </tr>

    <tr>
      <td class="center" colspan="2">
        <?php
          showInputButton("button1", _("Update"));
          showInputButton("button2", _("Reset"), "reset");
        ?>
      </td>
    </tr>
  </tbody>
</table>
