<?php
/**
 * login_form.php
 *
 * User login form
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: login_form.php,v 1.12 2008/03/23 11:59:02 jact Exp $
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
   * Breadcrumb
   */
  $links = array(
    _("Home") => "../home/index.php",
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_user");
  unset($links);

  /**
   * Error message if not session exists
   */
  if ( !is_dir(ini_get('session.save_path')) && ini_get('session.save_handler') == 'files' )
  {
    echo Msg::error(_("No session support. Authentication process will fail. Check your PHP configuration."));
  }

  /**
   * Cookies disabled?
   */
  if ( !isset($_COOKIE[session_name()]) )
  {
    echo Msg::error(_("You must have cookies enabled to access your account."));
  }

  /**
   * Warning message if loginAttempts == (OPEN_MAX_LOGIN_ATTEMPTS - 1)
   */
  if (OPEN_MAX_LOGIN_ATTEMPTS && isset($_SESSION['auth']['login_attempts'])
      && $_SESSION['auth']['login_attempts'] == (OPEN_MAX_LOGIN_ATTEMPTS - 1))
  {
    echo Msg::warning(_("Last attempt to type correct password before suspend this user account."));
  }

  /**
   * Login form
   */
  echo HTML::start('form',
    array(
      'id' => 'loginForm',
      'method' => 'post',
      'action' => '../auth/login.php'
    )
  );

  $tbody = array();

  $row = Form::hidden("md5_session");
  $tbody[] = $row;

  $row = Form::label("login_session", _("Login") . ":");
  $row .= Form::text("login_session",
    isset($formVar["login_session"]) ? $formVar["login_session"] : null,
    array(
      'size' => 20,
      'error' => isset($formError["login_session"]) ? $formError["login_session"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::label("pwd_session", _("Password") . ":");
  $row .= Form::password("pwd_session",
    isset($formVar["pwd_session"]) ? $formVar["pwd_session"] : null,
    array(
      'size' => 20,
      'error' => isset($formError["pwd_session"]) ? $formError["pwd_session"] : null
    )
  );
  $tbody[] = $row;

  $tfoot = array(
    Form::button("login", _("Enter"))
    . Form::generateToken()
  );

  echo Form::fieldset($title, $tbody, $tfoot);
  echo HTML::end('form');

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  //Error::debug($_SESSION, "session variables", true);

  require_once("../layout/footer.php");
?>
