<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: logout.php,v 1.8 2006/03/26 15:25:04 jact Exp $
 */

/**
 * logout.php
 *
 * Session destruction process
 *
 * @author jact <jachavar@gmail.com>
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
