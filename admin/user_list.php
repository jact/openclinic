<?php
/**
 * user_list.php
 *
 * List of defined users screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_list.php,v 1.31 2007/10/28 19:48:12 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "users";

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Query/User.php");
  require_once("../lib/Form.php");

  $userQ = new Query_User();
  $userQ->connect();

  $userQ->selectLogins();

  $userArray = null;
  while ($user = $userQ->fetch())
  {
    $userArray[$user->getIdMember() . OPEN_SEPARATOR . $user->getLogin()] = $user->getLogin();
  }
  $userQ->freeResult();

  /**
   * Show page
   */
  $title = _("Users");
  require_once("../layout/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon userIcon");
  unset($links);

  $legend = _("Create New User");

  if (empty($userArray))
  {
    $content = _("There no more users to create. You must create more staff members first.");
  }
  else
  {
    $content = Form::strLabel("id_member_login", _("Select a login to create a new user") . ": ");
    $content .= Form::strSelect("id_member_login", $userArray);
    $tfoot = array(Form::strButton("new", _("Create")) . Form::generateToken());
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

    Msg::info(_("No results found."));
    include_once("../layout/footer.php");
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
    if (isset($_SESSION['auth']['user_id']) && $user->getIdUser() == $_SESSION['auth']['user_id'])
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

  Msg::hint('* ' . _("Note: The del function will not be applicated to the session user."));

  require_once("../layout/footer.php");
?>
