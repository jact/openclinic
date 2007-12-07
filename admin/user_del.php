<?php
/**
 * user_del.php
 *
 * User deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_del.php,v 1.19 2007/12/07 16:50:50 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $returnLocation = "../admin/user_list.php";

  /**
   * Checking for post vars. Go back to $returnLocation if none found.
   */
  if (count($_POST) == 0)
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

  Form::compareToken($returnLocation);

  require_once("../lib/Check.php");

  /**
   * Retrieving post vars
   */
  $idUser = intval($_POST["id_user"]);

  /**
   * Delete user
   */
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

  $userQ->delete($idUser);

  $userQ->close();
  unset($userQ);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  FlashMsg::add(sprintf(_("User, %s, has been deleted."), $user->getLogin()));
  header("Location: " . $returnLocation);
?>
