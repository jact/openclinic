<?php
/**
 * user_fields.php
 *
 * Fields of user data
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_fields.php,v 1.21 2006/03/28 19:15:33 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  $tbody = array();

  $row = ($action == "new") ? _("Login") . ":" : Form::strLabel("login", _("Login") . ":", true);
  $row .= ($action == "new") ? $formVar["login"] : Form::strText("login", 20, isset($formVar["login"]) ? $formVar["login"] : null, isset($formError["login"]) ? array('error' => $formError["login"]) : null);
  $tbody[] = $row;

  if (isset($_GET["all"]))
  {
    $row = Form::strLabel("old_pwd", _("Current Password") . ":", true);
    $row .= Form::strPassword("old_pwd", 20,
      isset($formVar["old_pwd"]) ? $formVar["old_pwd"] : null,
      isset($formError["old_pwd"]) ? array('error' => $formError["old_pwd"]) : null
    );
    $row .= Form::strHidden("md5_old");
    $tbody[] = $row;
  }

  if ($action == "new" || isset($_GET["all"]))
  {
    $row = Form::strLabel("pwd", _("Password") . ":", true);
    $row .= Form::strPassword("pwd", 20,
      isset($formVar["pwd"]) ? $formVar["pwd"] : null,
      isset($formError["pwd"]) ? array('error' => $formError["pwd"]) : null
    );
    $row .= Form::strHidden("md5");
    $tbody[] = $row;

    $row = Form::strLabel("pw2", _("Re-enter Password") . ":", true);
    $row .= Form::strPassword("pwd2", 20,
      isset($formVar["pwd2"]) ? $formVar["pwd2"] : null,
      isset($formError["pwd2"]) ? array('error' => $formError["pwd2"]) : null
    );
    $row .= Form::strHidden("md5_confirm");
    $tbody[] = $row;
  }

  $row = Form::strLabel("email", _("Email") . ":");
  $row .= Form::strText("email", 40, isset($formVar["email"]) ? $formVar["email"] : null,
    isset($formError["email"]) ? array('error' => $formError["email"]) : null
  );
  $tbody[] = $row;

  if ( !isset($_GET["all"]) )
  {
    $row = Form::strLabel("actived", _("Actived") . ":");
    $row .= Form::strCheckBox("actived", 1, isset($formVar["actived"]) ? $formVar["actived"] != "" : false);
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

    $row = Form::strLabel("id_profile", _("Profile") . ":", true);
    $row .= Form::strSelect("id_profile", $array, $formVar["id_profile"]);
    unset($array);
    $tbody[] = $row;
  }

  $row = Form::strLabel("id_theme", _("Theme") . ":");
  $row .= Form::strSelectTable("theme_tbl", "id_theme", isset($formVar["id_theme"]) ? $formVar["id_theme"] : null, "theme_name");
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("button1", _("Submit"))
    . Form::strButton("return", _("Return"), "button", array('onclick' => "parent.location='" . $returnLocation . "'"))
  );

  Form::fieldset($title, $tbody, $tfoot);

  if (isset($_GET["all"]))
  {
    Form::hidden("actived", "checked");
    Form::hidden("id_profile", $formVar["id_profile"]);
  }
?>
