<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: login_form.php,v 1.4 2004/07/14 18:16:58 jact Exp $
 */

/**
 * login_form.php
 ********************************************************************
 * User login form
 ********************************************************************
 * Author: jact <jachavar@terra.es>
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

  // Advice message if loginAttempts == 2
  if (isset($_SESSION["loginAttempts"]) && $_SESSION["loginAttempts"] == 2)
  {
    echo '<p class="error">' . _("Last attempt to type correct password before suspend this user account.") . "</p>\n";
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
    <?php showInputHidden("md5"); ?>

    <table>
      <thead>
        <tr>
          <th colspan="2">
            <?php echo _("User Login"); ?>
          </th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>
            <label for="login_session"><?php echo _("Login:"); ?></label>
          </td>

          <td>
            <?php showInputText("login_session", 20, 20, $postVars["login_session"], $pageErrors["login_session"]); ?>
          </td>
        </tr>

        <tr>
          <td>
            <label for="pwd_session"><?php echo _("Password:"); ?></label>
          </td>

          <td>
            <?php showInputText("pwd_session", 20, 20, $postVars["pwd_session"], $pageErrors["pwd_session"], "password"); ?>
          </td>
        </tr>

        <tr>
          <td colspan="2" class="center">
            <?php showInputButton("button1", _("Enter")); ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</form>

<?php
  //debug($_SESSION, "session variables:", true);

  require_once("../shared/footer.php");
?>
