<?php
/**
 * user_list.php
 *
 * List of defined users screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_list.php,v 1.25 2006/09/30 16:49:53 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "users";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/User_Query.php");
  require_once("../lib/Form.php");

  /**
   * Retrieving get vars
   */
  $info = (isset($_GET["info"]) ? urldecode(Check::safeText($_GET["info"])) : "");

  $userQ = new User_Query();
  $userQ->connect();

  $userQ->selectLogins();

  $array = null;
  while ($user = $userQ->fetch())
  {
    $array[$user->getIdMember() . OPEN_SEPARATOR . $user->getLogin()] = $user->getLogin();
  }
  $userQ->freeResult();

  /**
   * Show page
   */
  $title = _("Users");
  require_once("../shared/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon userIcon");
  unset($links);

  /**
   * Display insertion message if coming from new with a successful insert.
   */
  if (isset($_GET["added"]) && !empty($info))
  {
    HTML::message(sprintf(_("User, %s, has been added."), $info), OPEN_MSG_INFO);
  }

  /**
   * Display update message if coming from edit with a successful update.
   */
  if (isset($_GET["updated"]) && !empty($info))
  {
    HTML::message(sprintf(_("User, %s, has been updated."), $info), OPEN_MSG_INFO);
  }

  /**
   * Display deletion message if coming from del with a successful delete.
   */
  if (isset($_GET["deleted"]) && !empty($info))
  {
    HTML::message(sprintf(_("User, %s, has been deleted."), $info), OPEN_MSG_INFO);
  }

  /**
   * Display password reset message if coming from pwd_reset with a succesful update.
   */
  if (isset($_GET["password"]) && !empty($info))
  {
    HTML::message(sprintf(_("Password of user, %s, has been reset."), $info), OPEN_MSG_INFO);
  }

  /**
   * Display login used message.
   */
  if (isset($_GET["login"]) && !empty($info))
  {
    HTML::message(sprintf(_("Login, %s, already exists. The changes have no effect."), $info), OPEN_MSG_INFO);
  }

  $legend = _("Create New User");

  if (empty($array))
  {
    $content = _("There no more users to create. You must create more staff members first.");
  }
  else
  {
    $content = Form::strLabel("id_member_login", _("Select a login to create a new user") . ": ");
    $content .= Form::strSelect("id_member_login", $array);
    $tfoot = array(Form::strButton("button1", _("Create")));
  }

  $tbody = array($content);

  /**
   * New user form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../admin/user_new_form.php'));
  Form::fieldset($legend, $tbody, isset($tfoot) ? $tfoot : null);
  HTML::end('form');

  HTML::section(2, _("Users List:"));

  if ( !$userQ->select() )
  {
    $userQ->close();
    HTML::message(_("No results found."), OPEN_MSG_INFO);
    include_once("../shared/footer.php");
    exit();
  }

  $profiles = array(
    OPEN_PROFILE_ADMINISTRATOR => _("Administrator"),
    OPEN_PROFILE_ADMINISTRATIVE => _("Administrative"),
    OPEN_PROFILE_DOCTOR => _("Doctor")
  );

  $thead = array(
    _("Function") => array('colspan' => 6),
    _("Login"),
    _("Email"),
    _("Actived"),
    _("Profile")
  );

  $options = array(
    8 => array('align' => 'center'),
    9 => array('align' => 'center')
  );

  $tbody = array();
  while ($user = $userQ->fetch())
  {
    /**
     * to protect 'big brother' user
     */
    if ($user->getIdProfile() == OPEN_PROFILE_ADMINISTRATOR && $user->getIdUser() == 1)
    {
      continue;
    }

    /**
     * Row construction
     */
    $row = HTML::strLink(_("edit"), '../admin/user_edit_form.php', array('key' => $user->getIdUser()));
    $row .= OPEN_SEPARATOR;
    $row .= HTML::strLink(_("pwd"), '../admin/user_pwd_reset_form.php', array('key' => $user->getIdUser()));
    $row .= OPEN_SEPARATOR;
    if (isset($_SESSION["userId"]) && $user->getIdUser() == $_SESSION["userId"])
    {
      $row .= '*' . _("del");
    }
    else
    {
      $row .= HTML::strLink(_("del"), '../admin/user_del_confirm.php',
        array(
          'key' => $user->getIdUser(),
          'login' => $user->getLogin()
        )
      );
    }
    $row .= OPEN_SEPARATOR;
    $row .= HTML::strLink(_("edit member"), '../admin/staff_edit_form.php', array('key' => $user->getIdMember()));
    $row .= OPEN_SEPARATOR;
    $row .= HTML::strLink(_("accesses"), '../admin/user_access_log.php',
      array(
        'key' => $user->getIdUser(),
        'login' => $user->getLogin()
      )
    );
    $row .= OPEN_SEPARATOR;
    $row .= HTML::strLink(_("transactions"), '../admin/user_record_log.php',
      array(
        'key' => $user->getIdUser(),
        'login' => $user->getLogin()
      )
    );
    $row .= OPEN_SEPARATOR;
    $row .= $user->getLogin();
    $row .= OPEN_SEPARATOR;
    $row .= $user->getEmail();
    $row .= OPEN_SEPARATOR;
    $row .= ($user->isActived() ? _("yes") : HTML::strTag('strong', _("no")));
    $row .= OPEN_SEPARATOR;
    $row .= $profiles[$user->getIdProfile()];

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end while
  $userQ->freeResult();
  $userQ->close();

  HTML::table($thead, $tbody, null, $options);

  unset($user);
  unset($userQ);
  unset($profiles);

  HTML::message('* ' . _("Note: The del function will not be applicated to the session user."));

  require_once("../shared/footer.php");
?>
