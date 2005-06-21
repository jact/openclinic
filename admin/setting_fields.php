<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: setting_fields.php,v 1.12 2005/06/21 18:20:25 jact Exp $
 */

/**
 * setting_fields.php
 *
 * Fields of config settings data
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['PATH_TRANSLATED']))
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../lib/file_lib.php");

  if (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    showInputHidden("language", "en");
  }

  $thead = array(
    _("Edit Config Settings") => array('colspan' => 2)
  );

  $tbody = array();

  $row = '<label for="clinic_name">' . _("Clinic Name") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("clinic_name", 40, 128, $postVars["clinic_name"], $pageErrors["clinic_name"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="clinic_image_url">' . _("Clinic Image") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;

  $dir = "../images/";
  $ext = array("bmp", "gif", "jpe", "jpeg", "jpg", "png");
  $array = getFiles($dir, false, $ext);

  $row .= htmlSelectArray("clinic_image_url", $array, basename($postVars["clinic_image_url"]));
  unset($array);
  unset($ext);

  $row .= '<br />' . _("(must be in /images/ directory)");

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="use_image">' . _("Use Image in place of Name") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlCheckBox("use_image", "use_image", 1, $postVars["use_image"] != "");

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="clinic_hours">' . _("Clinic Hours") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("clinic_hours", 40, 128, $postVars["clinic_hours"], $pageErrors["clinic_hours"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="clinic_address">' . _("Clinic Address") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlTextArea("clinic_address", 3, 30, $postVars["clinic_address"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="clinic_phone">' . _("Clinic Phone") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("clinic_phone", 40, 40, $postVars["clinic_phone"], $pageErrors["clinic_phone"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="clinic_url">' . _("Clinic URL") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("clinic_url", 40, 300, $postVars["clinic_url"], $pageErrors["clinic_url"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    $row = '<label for="language">' . _("Language") . ":" . "</label>\n";
    $row .= OPEN_SEPARATOR;

    $row .= htmlSelectArray("language", languageList(), $postVars["language"]);

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  }

  $row = '<label for="id_theme">' . _("Theme by default") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlSelect("theme_tbl", "id_theme", $postVars["id_theme"], "theme_name");

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '* <label for="session_timeout" class="requiredField">' . _("Session Timeout") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("session_timeout", 3, 3, $postVars["session_timeout"], $pageErrors["session_timeout"]);
  $row .= _("minutes");

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '* <label for="items_per_page" class="requiredField">' . _("Search Results") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("items_per_page", 2, 2, $postVars["items_per_page"], $pageErrors["items_per_page"]);
  $row .= _("items per page") . "**";

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $tfoot = array(htmlInputButton("button1", _("Update")));

  $options = array(
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  showTable($thead, $tbody, $tfoot, $options);
?>
