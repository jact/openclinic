<?php
/**
 * session_info.php
 *
 * Making session user info available on all pages
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: session_info.php,v 1.8 2006/03/28 19:20:42 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.7
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  session_name("OpenClinic");
  session_cache_limiter("nocache");
  session_start();
?>
