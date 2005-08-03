<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: setting_fields.php,v 1.17 2005/08/03 17:39:28 jact Exp $
 */

/**
 * setting_fields.php
 *
 * Fields of config settings data
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../lib/File.php");

  if (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    showInputHidden("language", "en");
  }

  $thead = array(
    _("Edit Config Settings") => array('colspan' => 2)
  );

  $tbody = array();

  $row = Form::strLabel("clinic_name", _("Clinic Name") . ":");
  $row .= OPEN_SEPARATOR;
  $row .= Form::strText("clinic_name", "clinic_name", 40, 128, $postVars["clinic_name"], $pageErrors["clinic_name"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("clinic_image_url", _("Clinic Image") . ":");
  $row .= OPEN_SEPARATOR;

  $dir = "../images/";
  $ext = array("bmp", "gif", "jpe", "jpeg", "jpg", "png");
  $array = File::getDirContent($dir, false, $ext);

  $row .= Form::strSelect("clinic_image_url", "clinic_image_url", $array, basename($postVars["clinic_image_url"]));
  unset($array);
  unset($ext);

  $row .= '<br />' . _("(must be in /images/ directory)");

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("use_image", _("Use Image in place of Name") . ":");
  $row .= OPEN_SEPARATOR;
  $row .= Form::strCheckBox("use_image", "use_image", 1, $postVars["use_image"] != "");

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("clinic_hours", _("Clinic Hours") . ":");
  $row .= OPEN_SEPARATOR;
  $row .= Form::strText("clinic_hours", "clinic_hours", 40, 128, $postVars["clinic_hours"], $pageErrors["clinic_hours"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("clinic_address", _("Clinic Address") . ":");
  $row .= OPEN_SEPARATOR;
  $row .= Form::strTextArea("clinic_address", "clinic_address", 3, 30, $postVars["clinic_address"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("clinic_phone", _("Clinic Phone") . ":");
  $row .= OPEN_SEPARATOR;
  $row .= Form::strText("clinic_phone", "clinic_phone", 40, 40, $postVars["clinic_phone"], $pageErrors["clinic_phone"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("clinic_url", _("Clinic URL") . ":");
  $row .= OPEN_SEPARATOR;
  $row .= Form::strText("clinic_url", "clinic_url", 40, 300, $postVars["clinic_url"], $pageErrors["clinic_url"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    $row = Form::strLabel("language", _("Language") . ":");
    $row .= OPEN_SEPARATOR;
    $row .= Form::strSelect("language", "language", I18n::languageList(), $postVars["language"]);

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  }

  $row = Form::strLabel("id_theme", _("Theme by default") . ":");
  $row .= OPEN_SEPARATOR;
  $row .= Form::strSelectTable("theme_tbl", "id_theme", $postVars["id_theme"], "theme_name");

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("session_timeout", _("Session Timeout") . ":", true);
  $row .= OPEN_SEPARATOR;
  $row .= Form::strText("session_timeout", "session_timeout", 3, 3, $postVars["session_timeout"], $pageErrors["session_timeout"]);
  $row .= _("minutes");

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("items_per_page", _("Search Results") . ":", true);
  $row .= OPEN_SEPARATOR;
  $row .= Form::strText("items_per_page", "items_per_page", 2, 2, $postVars["items_per_page"], $pageErrors["items_per_page"]);
  $row .= _("items per page") . "**";

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $tfoot = array(Form::strButton("button1", "button1", _("Update")));

  $options = array(
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  HTML::table($thead, $tbody, $tfoot, $options);
?>
