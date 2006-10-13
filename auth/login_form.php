<?php
/**
 * login_form.php
 *
 * User login form
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: login_form.php,v 1.1 2006/10/13 19:55:56 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "home";
  $nav = "login";
  $isMd5 = true;

  require_once("../config/environment.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");

  /**
   * this must be here, after environment.php (session_start())
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
  require_once("../layout/header.php");

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

  /**
   * Login form
   */
  HTML::start('form',
    array(
      'method' => 'post',
      'action' => '../auth/login.php',
      'onsubmit' => 'return md5Login(this);'
    )
  );

  $tbody = array();

  $row = Form::strHidden("md5_session");
  $tbody[] = $row;

  $row = Form::strLabel("login_session", _("Login") . ":");
  $row .= Form::strText("login_session", 20,
    isset($formVar["login_session"]) ? $formVar["login_session"] : null,
    isset($formError["login_session"]) ? array('error' => $formError["login_session"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("pwd_session", _("Password") . ":");
  $row .= Form::strPassword("pwd_session", 20,
    isset($formVar["pwd_session"]) ? $formVar["pwd_session"] : null,
    isset($formError["pwd_session"]) ? array('error' => $formError["pwd_session"]) : null
  );
  $tbody[] = $row;

  $tfoot = array(Form::strButton("button1", _("Enter")));

  Form::fieldset($title, $tbody, $tfoot);
  HTML::end('form');

  HTML::message(_("You must have cookies enabled to access your account."));

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  //Error::debug($_SESSION, "session variables", true);

  require_once("../layout/footer.php");
?>
