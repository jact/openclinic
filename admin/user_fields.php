<?php
/**
 * user_fields.php
 *
 * Fields of user data
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_fields.php,v 1.27 2008/03/23 11:58:57 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  $tbody = array();

  $row = ($action == "new") ? _("Login") . ":" : Form::label("login", _("Login") . ":", array('class' => 'required'));
  $row .= ($action == "new")
    ? $formVar["login"]
    : Form::text("login",
        isset($formVar["login"]) ? $formVar["login"] : null,
        array(
          'size' => 20,
          'error' => isset($formError["login"]) ? $formError["login"] : null
        )
      );
  $tbody[] = $row;

  if (isset($_GET["all"]))
  {
    $row = Form::label("old_pwd", _("Current Password") . ":", array('class' => 'required'));
    $row .= Form::password("old_pwd",
      isset($formVar["old_pwd"]) ? $formVar["old_pwd"] : null,
      array(
        'size' => 20,
        'error' => isset($formError["old_pwd"]) ? $formError["old_pwd"] : null
      )
    );
    $row .= Form::hidden("md5_old");
    $tbody[] = $row;
  }

  if ($action == "new" || isset($_GET["all"]))
  {
    $row = Form::label("pwd", _("Password") . ":", array('class' => 'required'));
    $row .= Form::password("pwd",
      isset($formVar["pwd"]) ? $formVar["pwd"] : null,
      array(
        'size' => 20,
        'error' => isset($formError["pwd"]) ? $formError["pwd"] : null
      )
    );
    $row .= Form::hidden("md5");
    $tbody[] = $row;

    $row = Form::label("pw2", _("Re-enter Password") . ":", array('class' => 'required'));
    $row .= Form::password("pwd2",
      isset($formVar["pwd2"]) ? $formVar["pwd2"] : null,
      array(
        'size' => 20,
        'error' => isset($formError["pwd2"]) ? $formError["pwd2"] : null
      )
    );
    $row .= Form::hidden("md5_confirm");
    $tbody[] = $row;
  }

  $row = Form::label("email", _("Email") . ":");
  $row .= Form::text("email",
    isset($formVar["email"]) ? $formVar["email"] : null,
    array(
      'size' => 40,
      'error' => isset($formError["email"]) ? $formError["email"] : null
    )
  );
  $tbody[] = $row;

  if ( !isset($_GET["all"]) )
  {
    $row = Form::label("actived", _("Actived") . ":");
    $row .= Form::checkBox("actived", 1,
      array('checked' => isset($formVar["actived"]) ? $formVar["actived"] != "" : false)
    );
    $tbody[] = $row;

    if ( !isset($formVar["id_profile"]) || $formVar["id_profile"] == "" )
    {
      $formVar["id_profile"] = OPEN_PROFILE_DOCTOR; // by default doctor profile
    }

    $array = array(
      OPEN_PROFILE_ADMINISTRATOR => _("Administrator"),
      OPEN_PROFILE_ADMINISTRATIVE => _("Administrative"),
      OPEN_PROFILE_DOCTOR => _("Doctor")
    );

    $row = Form::label("id_profile", _("Profile") . ":", array('class' => 'required'));
    $row .= Form::select("id_profile", $array, $formVar["id_profile"]);
    unset($array);
    $tbody[] = $row;
  }

  $row = Form::label("id_theme", _("Theme") . ":");
  $row .= Form::selectTable("theme_tbl", "id_theme", isset($formVar["id_theme"]) ? $formVar["id_theme"] : null, "theme_name");
  $tbody[] = $row;

  $tfoot = array(
    Form::button("save", _("Submit"))
    . Form::generateToken()
  );

  echo Form::fieldset($title, $tbody, $tfoot);

  if (isset($_GET["all"]))
  {
    echo Form::hidden("actived", "checked");
    echo Form::hidden("id_profile", $formVar["id_profile"]);
  }
?>
