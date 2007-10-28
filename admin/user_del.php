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
 * @version   CVS: $Id: user_del.php,v 1.16 2007/10/28 19:48:12 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  //$restrictInDemo = true;
  $returnLocation = "../admin/user_list.php";

  /**
   * Checking for post vars. Go back to $returnLocation if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/Form.php");

  Form::compareToken($returnLocation);

  require_once("../model/Query/User.php");
  require_once("../lib/Check.php");

  /**
   * Retrieving post vars
   */
  $idUser = intval($_POST["id_user"]);
  $login = Check::safeText($_POST["login"]);

  /**
   * Delete user
   */
  $userQ = new Query_User();
  $userQ->connect();

  $userQ->delete($idUser);

  $userQ->close();
  unset($userQ);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  FlashMsg::add(sprintf(_("User, %s, has been deleted."), $login));
  header("Location: " . $returnLocation);
?>
