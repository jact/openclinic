<?php
/**
 * user_list.php
 *
 * List of defined users screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_list.php,v 1.36 2008/03/23 11:58:57 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "users";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR);

  require_once("../lib/Form.php");

  require_once("../model/Query/User.php");
  $userQ = new Query_User();
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
   * Breadcrumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_user");
  unset($links);

  $legend = _("Create New User");

  if (empty($userArray))
  {
    $content = _("There no more users to create. You must create more staff members first.");
  }
  else
  {
    $content = Form::label("id_member_login", _("Select a login to create a new user") . ": ");
    $content .= Form::select("id_member_login", $userArray);
    $tfoot = array(Form::button("new", _("Create")) . Form::generateToken());
  }

  $tbody = array($content);

  /**
   * New user form
   */
  echo HTML::start('form', array('method' => 'post', 'action' => '../admin/user_new_form.php'));
  echo Form::fieldset($legend, $tbody, isset($tfoot) ? $tfoot : null, array('id' => 'new_user'));
  echo HTML::end('form');

  echo HTML::section(2, _("Users List:"));

  if ( !$userQ->select() )
  {
    $userQ->close();

    echo Msg::info(_("No results found."));
    include_once("../layout/footer.php");
    exit();
  }

  $profiles = array(
    OPEN_PROFILE_ADMINISTRATOR => _("Administrator"),
    OPEN_PROFILE_ADMINISTRATIVE => _("Administrative"),
    OPEN_PROFILE_DOCTOR => _("Doctor")
  );

  $thead = array(
    _("#"),
    _("Function") => array('colspan' => 6),
    _("Login"),
    _("Email"),
    _("Actived"),
    _("Profile")
  );

  $options = array(
    0 => array('align' => 'right'),
    1 => array('align' => 'center'),
    2 => array('align' => 'center'),
    3 => array('align' => 'center'),
    4 => array('align' => 'center'),
    5 => array('align' => 'center'),
    6 => array('align' => 'center'),
    9 => array('align' => 'center'),
    10 => array('align' => 'center')
  );

  $tbody = array();
  $i = 0;
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
    $row = ++$i . '.';
    $row .= OPEN_SEPARATOR;
    $row .= HTML::link(
      HTML::image('../img/action_edit.png', _("edit")),
      '../admin/user_edit_form.php', array('id_user' => $user->getIdUser())
    );
    $row .= OPEN_SEPARATOR;
    $row .= HTML::link(
      HTML::image('../img/action_password.png', _("password")),
      '../admin/user_pwd_reset_form.php',
      array('id_user' => $user->getIdUser())
    );
    $row .= OPEN_SEPARATOR;
    if (isset($_SESSION['auth']['user_id']) && $user->getIdUser() == $_SESSION['auth']['user_id'])
    {
      $row .= '*'; //'*' . _("del");
    }
    else
    {
      $row .= HTML::link(
        HTML::image('../img/action_delete.png', _("delete")),
        '../admin/user_del_confirm.php',
        array(
          'id_user' => $user->getIdUser(),
          'login' => $user->getLogin()
        )
      );
    }
    $row .= OPEN_SEPARATOR;
    $row .= HTML::link(
      HTML::image('../img/action_edit_user.png', _("edit member")),
      '../admin/staff_edit_form.php',
      array('id_member' => $user->getIdMember())
    );
    $row .= OPEN_SEPARATOR;
    $row .= HTML::link(
      HTML::image('../img/action_access.png', _("accesses")),
      '../admin/user_access_log.php',
      array(
        'id_user' => $user->getIdUser(),
        'login' => $user->getLogin()
      )
    );
    $row .= OPEN_SEPARATOR;
    $row .= HTML::link(
      HTML::image('../img/action_record.png', _("transactions")),
      '../admin/user_record_log.php',
      array(
        'id_user' => $user->getIdUser(),
        'login' => $user->getLogin()
      )
    );
    $row .= OPEN_SEPARATOR;
    $row .= $user->getLogin();
    $row .= OPEN_SEPARATOR;
    $row .= $user->getEmail();
    $row .= OPEN_SEPARATOR;
    $row .= ($user->isActived() ? _("yes") : HTML::tag('strong', _("no")));
    $row .= OPEN_SEPARATOR;
    $row .= $profiles[$user->getIdProfile()];

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end while
  $userQ->freeResult();
  $userQ->close();

  echo HTML::table($thead, $tbody, null, $options);

  unset($user);
  unset($userQ);
  unset($profiles);

  echo Msg::hint('* ' . _("Note: The del function will not be applicated to the session user."));

  require_once("../layout/footer.php");
?>
