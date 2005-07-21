<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: login_form.php,v 1.9 2005/07/21 16:01:20 jact Exp $
 */

/**
 * login_form.php
 ********************************************************************
 * User login form
 ********************************************************************
 * Author: jact <jachavar@gmail.com>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "home";
  $nav = "login";
  $focusFormName = "forms[0]";
  $focusFormField = "login_session";

  require_once("../shared/read_settings.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors
  require_once("../lib/input_lib.php");

  // this must be here, after read_settings.php (session_start())
  if (isset($_GET["ret"]))
  {
    $_SESSION["returnPage"] = $_GET["ret"];
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("User Login");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Home") => "../home/index.php",
    $title => ""
  );
  showNavLinks($links, "users.png");
  unset($links);

  // Warning message if loginAttempts == (OPEN_MAX_LOGIN_ATTEMPTS - 1)
  if (OPEN_MAX_LOGIN_ATTEMPTS && isset($_SESSION["loginAttempts"])
      && $_SESSION["loginAttempts"] == (OPEN_MAX_LOGIN_ATTEMPTS - 1))
  {
    showMessage(_("Last attempt to type correct password before suspend this user account."));
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

<form method="post" action="../shared/login.php" onsubmit="return md5Login(this);">
  <div class="center">
<?php
  showInputHidden("md5");

  $thead = array(
    _("User Login") => array('colspan' => 2)
  );

  $tbody = array();

  $row = '<label for="login_session">' . _("Login") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("login_session", 20, 20,
    isset($postVars["login_session"]) ? $postVars["login_session"] : null,
    isset($pageErrors["login_session"]) ? $pageErrors["login_session"] : null
  );

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="pwd_session">' . _("Password") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("pwd_session", 20, 20,
    isset($postVars["pwd_session"]) ? $postVars["pwd_session"] : null,
    isset($pageErrors["pwd_session"]) ? $pageErrors["pwd_session"] : null,
    "password"
  );

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $tfoot = array(htmlInputButton("button1", _("Enter")));

  $options = array(
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  showTable($thead, $tbody, $tfoot, $options);
?>
  </div>
</form>

<?php
  showMessage(_("You must have cookies enabled to access your account."));

  //Error::debug($_SESSION, "session variables:", true);

  require_once("../shared/footer.php");
?>
