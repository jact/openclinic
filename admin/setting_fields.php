<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: setting_fields.php,v 1.18 2005/08/17 16:52:34 jact Exp $
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

  $tbody = array();

  if (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    $row = Form::strHidden("language", "language", "en");
    $tbody[] = $row;
  }

  $row = Form::strLabel("clinic_name", _("Clinic Name") . ":");
  $row .= Form::strText("clinic_name", "clinic_name", 40, 128, $postVars["clinic_name"], $pageErrors["clinic_name"]);
  $tbody[] = $row;

  $row = Form::strLabel("clinic_image_url", _("Clinic Image") . ":");

  $dir = "../images/";
  $ext = array("bmp", "gif", "jpe", "jpeg", "jpg", "png");
  $array = File::getDirContent($dir, false, $ext);

  $row .= Form::strSelect("clinic_image_url", "clinic_image_url", $array, basename($postVars["clinic_image_url"]));
  unset($array);
  unset($ext);

  $row .= '<br />' . _("(must be in /images/ directory)");
  $tbody[] = $row;

  $row = Form::strLabel("use_image", _("Use Image in place of Name") . ":");
  $row .= Form::strCheckBox("use_image", "use_image", 1, $postVars["use_image"] != "");
  $tbody[] = $row;

  $row = Form::strLabel("clinic_hours", _("Clinic Hours") . ":");
  $row .= Form::strText("clinic_hours", "clinic_hours", 40, 128, $postVars["clinic_hours"], $pageErrors["clinic_hours"]);
  $tbody[] = $row;

  $row = Form::strLabel("clinic_address", _("Clinic Address") . ":");
  $row .= Form::strTextArea("clinic_address", "clinic_address", 3, 30, $postVars["clinic_address"]);
  $tbody[] = $row;

  $row = Form::strLabel("clinic_phone", _("Clinic Phone") . ":");
  $row .= Form::strText("clinic_phone", "clinic_phone", 40, 40, $postVars["clinic_phone"], $pageErrors["clinic_phone"]);
  $tbody[] = $row;

  $row = Form::strLabel("clinic_url", _("Clinic URL") . ":");
  $row .= Form::strText("clinic_url", "clinic_url", 40, 300, $postVars["clinic_url"], $pageErrors["clinic_url"]);
  $tbody[] = $row;

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    $row = Form::strLabel("language", _("Language") . ":");
    $row .= Form::strSelect("language", "language", I18n::languageList(), $postVars["language"]);
    $tbody[] = $row;
  }

  $row = Form::strLabel("id_theme", _("Theme by default") . ":");
  $row .= Form::strSelectTable("theme_tbl", "id_theme", $postVars["id_theme"], "theme_name");
  $tbody[] = $row;

  $row = Form::strLabel("session_timeout", _("Session Timeout") . ":", true);
  $row .= Form::strText("session_timeout", "session_timeout", 3, 3, $postVars["session_timeout"], $pageErrors["session_timeout"]);
  $row .= _("minutes");
  $tbody[] = $row;

  $row = Form::strLabel("items_per_page", _("Search Results") . ":", true);
  $row .= Form::strText("items_per_page", "items_per_page", 2, 2, $postVars["items_per_page"], $pageErrors["items_per_page"]);
  $row .= _("items per page") . "**";
  $tbody[] = $row;

  $tfoot = array(Form::strButton("button1", "button1", _("Update")));

  Form::fieldset($title, $tbody, $tfoot);
?>
