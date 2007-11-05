<?php
/**
 * login_form.php
 *
 * User login form
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: login_form.php,v 1.9 2007/11/05 14:29:47 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "home";
  $nav = "login";
  $isMd5 = true;

  require_once("../config/environment.php");
  require_once("../lib/Check.php");

  /**
   * this must be here, after environment.php (session_start())
   */
  if (isset($_GET["ret"]))
  {
    $_SESSION['auth']['return_page'] = Check::safeText($_GET["ret"]);
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
   * Error message if not session exists
   */
  if ( !is_dir(ini_get('session.save_path')) && ini_get('session.save_handler') == 'files' )
  {
    Msg::error(_("No session support. Authentication process will fail. Check your PHP configuration."));
  }

  /**
   * Cookies disabled?
   */
  if ( !isset($_COOKIE[session_name()]) )
  {
    Msg::error(_("You must have cookies enabled to access your account."));
  }

  /**
   * Warning message if loginAttempts == (OPEN_MAX_LOGIN_ATTEMPTS - 1)
   */
  if (OPEN_MAX_LOGIN_ATTEMPTS && isset($_SESSION['auth']['login_attempts'])
      && $_SESSION['auth']['login_attempts'] == (OPEN_MAX_LOGIN_ATTEMPTS - 1))
  {
    Msg::warning(_("Last attempt to type correct password before suspend this user account."));
  }

  /**
   * Login form
   */
  HTML::start('form',
    array(
      'id' => 'loginForm',
      'method' => 'post',
      'action' => '../auth/login.php'
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

  $tfoot = array(
    Form::strButton("login", _("Enter"))
    . Form::generateToken()
  );

  Form::fieldset($title, $tbody, $tfoot);
  HTML::end('form');

  /**
   * Destroy form values and errors
   */
  Form::unsetSession(OPEN_UNSET_ONLY_ERROR); // if unset all, OPEN_MAX_LOGIN_ATTEMPTS does not work!

  //Error::debug($_SESSION, "session variables", true);

  require_once("../layout/footer.php");
?>
