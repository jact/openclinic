<?php
/**
 * session_info.php
 *
 * Making session user info available on all pages
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: session_info.php,v 1.3 2007/11/03 18:23:01 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.7
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  /**
   * @since 0.8
   */
  ini_set('session.use_cookies', 1); // cookies will be used for propagation of the session ID
  ini_set('session.use_only_cookies', 1); // a session ID passed in the URL to the script will be discarded

  session_name("OpenClinic");
  session_cache_limiter("nocache");
  session_start();
?>
