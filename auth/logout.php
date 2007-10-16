<?php
/**
 * logout.php
 *
 * Session destruction process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: logout.php,v 1.2 2007/10/16 19:57:55 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once("../config/session_info.php");

  /**
   * Session destroy
   */
  //echo session_encode(); // debug
  $_SESSION = array(); // deregister all current session variables

  /**
   * Cookie destroy
   */
  $params = session_get_cookie_params();
  setcookie(session_name(), 0, 1, $params['path']);
  unset($params);
  /*if (isset($_COOKIE[session_name()])) // PHP Manual (session_destroy)
  {
    setcookie(session_name(), '', time() - 42000, '/');
  }*/

  session_destroy(); // clean up session ID

  header("Location: ../home/index.php");
?>
