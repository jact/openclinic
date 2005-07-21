<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_fields.php,v 1.13 2005/07/21 16:55:57 jact Exp $
 */

/**
 * user_fields.php
 *
 * Fields of user data
 *
 * Author: jact <jachavar@gmail.com>
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

  $row = ($action == "new") ? _("Login") . ":" : '* <label for="login" class="requiredField">' . _("Login") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= ($action == "new") ? $postVars["login"] : htmlInputText("login", 20, 20, $postVars["login"], $pageErrors["login"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  if (isset($_GET["all"]))
  {
    $row = '* <label for="old_pwd" class="requiredField">' . _("Current Password") . ":" . "</label>\n";
    $row .= OPEN_SEPARATOR;
    $row .= htmlInputText("old_pwd", 20, 20, isset($postVars["old_pwd"]) ? $postVars["old_pwd"] : null, $pageErrors["old_pwd"], "password");
    $row .= htmlInputHidden("md5_old");

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  }

  if ($action == "new" || isset($_GET["all"]))
  {
    $row = '* <label for="pwd" class="requiredField">' . _("Password") . ":" . "</label>\n";
    $row .= OPEN_SEPARATOR;
    $row .= htmlInputText("pwd", 20, 20, isset($postVars["pwd"]) ? $postVars["pwd"] : null, $pageErrors["pwd"], "password");
    $row .= htmlInputHidden("md5");

    $tbody[] = explode(OPEN_SEPARATOR, $row);

    $row = '* <label for="pw2" class="requiredField">' . _("Re-enter Password") . ":" . "</label>\n";
    $row .= OPEN_SEPARATOR;
    $row .= htmlInputText("pwd2", 20, 20, isset($postVars["pwd2"]) ? $postVars["pwd2"] : null, $pageErrors["pwd2"], "password");
    $row .= htmlInputHidden("md5_confirm");

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  }

  $row = '<label for="email">' . _("Email") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("email", 40, 40, isset($postVars["email"]) ? $postVars["email"] : null, $pageErrors["email"]);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  if ( !isset($_GET["all"]) )
  {
    $row = '<label for="actived">' . _("Actived") . ":" . "</label>\n";
    $row .= OPEN_SEPARATOR;
    $row .= htmlCheckBox("actived", "actived", 1, isset($postVars["actived"]) ? $postVars["actived"] != "" : false);

    $tbody[] = explode(OPEN_SEPARATOR, $row);

    if ( !isset($postVars["id_profile"]) || $postVars["id_profile"] == "" )
    {
      $postVars["id_profile"] = OPEN_PROFILE_DOCTOR; // by default doctor profile
    }

    $array = array(
      OPEN_PROFILE_ADMINISTRATOR => _("Administrator"),
      OPEN_PROFILE_ADMINISTRATIVE => _("Administrative"),
      OPEN_PROFILE_DOCTOR => _("Doctor")
    );

    $row = '* <label for="id_profile" class="requiredField">' . _("Profile") . ":" . "</label>\n";
    $row .= OPEN_SEPARATOR;
    $row .= htmlSelectArray("id_profile", $array, $postVars["id_profile"]);
    unset($array);

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  }

  $row = '<label for="id_theme">' . _("Theme") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlSelect("theme_tbl", "id_theme", isset($postVars["id_theme"]) ? $postVars["id_theme"] : null, "theme_name");

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $tfoot = array(
    htmlInputButton("button1", _("Submit"))
    . htmlInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array(
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  HTML::table($thead, $tbody, $tfoot, $options);

  if (isset($_GET["all"]))
  {
    showInputHidden("actived", "checked");
    showInputHidden("id_profile", $postVars["id_profile"]);
  }
?>
