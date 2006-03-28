<?php
/**
 * logout.php
 *
 * Session destruction process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: logout.php,v 1.9 2006/03/28 19:20:42 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once("../shared/session_info.php");

  /**
   * Session destroy
   */
  //echo session_encode(); // debug
  $_SESSION = array(); // deregister all current session variables
  session_destroy(); // clean up session ID

  /**
   * Cookie destroy
   */
  $params = session_get_cookie_params();
  setcookie(session_name(), 0, 1, $params['path']);
  unset($params);

  header("Location: ../home/index.php");
?>
