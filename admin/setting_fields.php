<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: setting_fields.php,v 1.20 2006/03/12 18:26:13 jact Exp $
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
    $row = Form::strHidden("language", "en");
    $tbody[] = $row;
  }

  $row = Form::strLabel("clinic_name", _("Clinic Name") . ":");
  $row .= Form::strText("clinic_name", 40,
    isset($postVars["clinic_name"]) ? $postVars["clinic_name"] : null,
    array(
      'maxlength' => 128,
      'error' => isset($pageErrors["clinic_name"]) ? $pageErrors["clinic_name"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::strLabel("clinic_image_url", _("Clinic Image") . ":");

  $dir = "../images/";
  $ext = array("bmp", "gif", "jpe", "jpeg", "jpg", "png");
  $array = File::getDirContent($dir, false, $ext);

  $row .= Form::strSelect("clinic_image_url", $array, basename($postVars["clinic_image_url"]));
  unset($array);
  unset($ext);

  $row .= _("(must be in /images/ directory)");
  $tbody[] = $row;

  $row = Form::strLabel("use_image", _("Use Image in place of Name") . ":");
  $row .= Form::strCheckBox("use_image", 1, $postVars["use_image"] != "");
  $tbody[] = $row;

  $row = Form::strLabel("clinic_hours", _("Clinic Hours") . ":");
  $row .= Form::strText("clinic_hours", 40,
    isset($postVars["clinic_hours"]) ? $postVars["clinic_hours"] : null,
    array(
      'maxlength' => 128,
      'error' => isset($pageErrors["clinic_hours"]) ? $pageErrors["clinic_hours"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::strLabel("clinic_address", _("Clinic Address") . ":");
  $row .= Form::strTextArea("clinic_address", 3, 30, isset($postVars["clinic_address"]) ? $postVars["clinic_address"] : null);
  $tbody[] = $row;

  $row = Form::strLabel("clinic_phone", _("Clinic Phone") . ":");
  $row .= Form::strText("clinic_phone", 40,
    isset($postVars["clinic_phone"]) ? $postVars["clinic_phone"] : null,
    isset($pageErrors["clinic_phone"]) ? array('error' => $pageErrors["clinic_phone"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("clinic_url", _("Clinic URL") . ":");
  $row .= Form::strText("clinic_url", 40,
    isset($postVars["clinic_url"]) ? $postVars["clinic_url"] : null,
    array(
      'maxlength' => 300,
      'error' => isset($pageErrors["clinic_url"]) ? $pageErrors["clinic_url"] : null
    )
  );
  $tbody[] = $row;

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    $row = Form::strLabel("language", _("Language") . ":");
    $row .= Form::strSelect("language", I18n::languageList(), $postVars["language"]);
    $tbody[] = $row;
  }

  $row = Form::strLabel("id_theme", _("Theme by default") . ":");
  $row .= Form::strSelectTable("theme_tbl", "id_theme", $postVars["id_theme"], "theme_name");
  $tbody[] = $row;

  $row = Form::strLabel("session_timeout", _("Session Timeout") . ":", true);
  $row .= Form::strText("session_timeout", 3, $postVars["session_timeout"], $pageErrors["session_timeout"]);
  $row .= _("minutes");
  $tbody[] = $row;

  $row = Form::strLabel("items_per_page", _("Search Results") . ":", true);
  $row .= Form::strText("items_per_page", 2, $postVars["items_per_page"], $pageErrors["items_per_page"]);
  $row .= _("items per page") . "**";
  $tbody[] = $row;

  $tfoot = array(Form::strButton("button1", _("Update")));

  Form::fieldset($title, $tbody, $tfoot);
?>
