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
 * @version   CVS: $Id: session_info.php,v 1.2 2007/10/29 20:05:05 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.7
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  session_name("OpenClinic");
  session_cache_limiter("nocache");
  session_start();
?>
