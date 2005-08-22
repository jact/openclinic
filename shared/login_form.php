<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: login_form.php,v 1.15 2005/08/22 15:12:33 jact Exp $
 */

/**
 * login_form.php
 *
 * User login form
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "home";
  $nav = "login";

  require_once("../shared/read_settings.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");

  /**
   * this must be here, after read_settings.php (session_start())
   */
  if (isset($_GET["ret"]))
  {
    $_SESSION["returnPage"] = Check::safeText($_GET["ret"]);
  }

  /**
   * Show page
   */
  $title = _("User Login");
  $focusFormField = "login_session";
  require_once("../shared/header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Home") => "../home/index.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon userIcon");
  unset($links);

  /**
   * Warning message if loginAttempts == (OPEN_MAX_LOGIN_ATTEMPTS - 1)
   */
  if (OPEN_MAX_LOGIN_ATTEMPTS && isset($_SESSION["loginAttempts"])
      && $_SESSION["loginAttempts"] == (OPEN_MAX_LOGIN_ATTEMPTS - 1))
  {
    HTML::message(_("Last attempt to type correct password before suspend this user account."));
  }
?>

<script src="../scripts/md5.js" type="text/javascript"></script>

<script type="text/javascript">
<!--/*--><![CDATA[/*<!--*/
function md5Login(f)
{
  f['md5'].value = hex_md5(f['pwd_session'].value);
  f['pwd_session'].value = '';

  return true;
}
/*]]>*///-->
</script>

<?php
  /**
   * Login form
   */
  echo '<form method="post" action="../shared/login.php" onsubmit="return md5Login(this);">' . "\n";

  $tbody = array();

  $row = Form::strHidden("md5", "md5");
  $tbody[] = $row;

  $row = Form::strLabel("login_session", _("Login") . ":");
  $row .= Form::strText("login_session", "login_session", 20, 20,
    isset($postVars["login_session"]) ? $postVars["login_session"] : null,
    isset($pageErrors["login_session"]) ? $pageErrors["login_session"] : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("pwd_session", _("Password") . ":");
  $row .= Form::strPassword("pwd_session", "pwd_session", 20, 20,
    isset($postVars["pwd_session"]) ? $postVars["pwd_session"] : null,
    isset($pageErrors["pwd_session"]) ? $pageErrors["pwd_session"] : null
  );
  $tbody[] = $row;

  $tfoot = array(Form::strButton("button1", "button1", _("Enter")));

  Form::fieldset($title, $tbody, $tfoot);
  echo "</form>\n";

  HTML::message(_("You must have cookies enabled to access your account."));

  //Error::debug($_SESSION, "session variables", true);

  require_once("../shared/footer.php");
?>
