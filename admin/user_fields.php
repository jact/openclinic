<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_fields.php,v 1.18 2006/03/12 18:35:26 jact Exp $
 */

/**
 * user_fields.php
 *
 * Fields of user data
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  $tbody = array();

  $row = ($action == "new") ? _("Login") . ":" : Form::strLabel("login", _("Login") . ":", true);
  $row .= ($action == "new") ? $postVars["login"] : Form::strText("login", 20, $postVars["login"], array('error' => $pageErrors["login"]));
  $tbody[] = $row;

  if (isset($_GET["all"]))
  {
    $row = Form::strLabel("old_pwd", _("Current Password") . ":", true);
    $row .= Form::strPassword("old_pwd", 20,
      isset($postVars["old_pwd"]) ? $postVars["old_pwd"] : null,
      isset($pageErrors["old_pwd"]) ? array('error' => $pageErrors["old_pwd"]) : null
    );
    $row .= Form::strHidden("md5_old");
    $tbody[] = $row;
  }

  if ($action == "new" || isset($_GET["all"]))
  {
    $row = Form::strLabel("pwd", _("Password") . ":", true);
    $row .= Form::strPassword("pwd", 20,
      isset($postVars["pwd"]) ? $postVars["pwd"] : null,
      isset($pageErrors["pwd"]) ? array('error' => $pageErrors["pwd"]) : null
    );
    $row .= Form::strHidden("md5");
    $tbody[] = $row;

    $row = Form::strLabel("pw2", _("Re-enter Password") . ":", true);
    $row .= Form::strPassword("pwd2", 20,
      isset($postVars["pwd2"]) ? $postVars["pwd2"] : null,
      isset($pageErrors["pwd2"]) ? array('error' => $pageErrors["pwd2"]) : null
    );
    $row .= Form::strHidden("md5_confirm");
    $tbody[] = $row;
  }

  $row = Form::strLabel("email", _("Email") . ":");
  $row .= Form::strText("email", 40, isset($postVars["email"]) ? $postVars["email"] : null, array('error' => $pageErrors["email"]));
  $tbody[] = $row;

  if ( !isset($_GET["all"]) )
  {
    $row = Form::strLabel("actived", _("Actived") . ":");
    $row .= Form::strCheckBox("actived", 1, isset($postVars["actived"]) ? $postVars["actived"] != "" : false);
    $tbody[] = $row;

    if ( !isset($postVars["id_profile"]) || $postVars["id_profile"] == "" )
    {
      $postVars["id_profile"] = OPEN_PROFILE_DOCTOR; // by default doctor profile
    }

    $array = array(
      OPEN_PROFILE_ADMINISTRATOR => _("Administrator"),
      OPEN_PROFILE_ADMINISTRATIVE => _("Administrative"),
      OPEN_PROFILE_DOCTOR => _("Doctor")
    );

    $row = Form::strLabel("id_profile", _("Profile") . ":", true);
    $row .= Form::strSelect("id_profile", $array, $postVars["id_profile"]);
    unset($array);
    $tbody[] = $row;
  }

  $row = Form::strLabel("id_theme", _("Theme") . ":");
  $row .= Form::strSelectTable("theme_tbl", "id_theme", isset($postVars["id_theme"]) ? $postVars["id_theme"] : null, "theme_name");
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("button1", _("Submit"))
    . Form::strButton("return", _("Return"), "button", array('onclick' => 'parent.location=\'' . $returnLocation . '\'"'))
  );

  Form::fieldset($title, $tbody, $tfoot);

  if (isset($_GET["all"]))
  {
    Form::hidden("actived", "checked");
    Form::hidden("id_profile", $postVars["id_profile"]);
  }
?>
