<?php
/**
 * user_del_confirm.php
 *
 * Confirmation screen of an user deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_del_confirm.php,v 1.23 2008/03/23 11:58:57 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "users";
  $returnLocation = "../admin/user_list.php";

  /**
   * Checking for query string. Go back to $returnLocation if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["id_user"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR);

  require_once("../lib/Form.php");
  require_once("../lib/Check.php");

  
  /**
   * Retrieving get vars
   */
  $idUser = intval($_GET["id_user"]);
  
  if( $idUser == $_SESSION['auth']['user_id'] )
  {
	FlashMsg::add(_("You can't delete yourself!"), OPEN_MSG_ERROR);
    header("Location: " . $returnLocation);
    exit(); 
  }
  
  require_once("../model/Query/User.php");
  $userQ = new Query_User();
  
  if ( !$userQ->select($idUser) )
  {
    $userQ->close();

    FlashMsg::add(_("That user does not exist."), OPEN_MSG_ERROR);
    header("Location: " . $returnLocation);
    exit();
  }

  $user = $userQ->fetch();
  $userQ->close();
  unset($userQ);

  $login = $user->getLogin();
  unset($user);

  /**
   * Show page
   */
  $title = _("Delete User");
  require_once("../layout/header.php");

  /**
   * Breadcrumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Users") => $returnLocation,
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_user");
  unset($links);

  /**
   * Form
   */
  echo HTML::start('form', array('method' => 'post', 'action' => '../admin/user_del.php'));

  $tbody = array();

  $tbody[] = Msg::warning(sprintf(_("Are you sure you want to delete user: %s?"), $login));

  $row = Form::hidden("id_user", $idUser);
  $tbody[] = $row;

  $tfoot = array(
    Form::button("delete", _("Delete"))
    . Form::generateToken()
  );

  $options = array('class' => 'center');

  echo Form::fieldset($title, $tbody, $tfoot, $options);

  echo HTML::end('form');

  echo HTML::para(HTML::link(_("Return"), $returnLocation));

  require_once("../layout/footer.php");
?>
