<?php
/**
 * exe_protect.php
 *
 * Contains executionProtection function
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: exe_protect.php,v 1.1 2007/10/29 20:12:41 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.8
 */

  executionProtection(__FILE__);

/**
 * void executionProtection(string $file, string $redirect = '../index.php')
 *
 * If complete path (__FILE__ in calling archive) is equal than execution script,
 * function redirects to a well-known page
 * Serves to protect incorrect code execution
 *
 * Use case: (in the begining of the file, include these lines)
 *  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
 *  executionProtection(__FILE__);
 *
 * @param string $file
 * @param string $redirect (optional)
 * @return void
 * @access public
 */
function executionProtection($file, $redirect = '../index.php')
{
  if (empty($file))
  {
    exit(); // if not parameter, exit before continue with script execution
  }

  $_serverVar = (strpos(PHP_SAPI, 'cgi') !== false)
    ? $_SERVER['PATH_TRANSLATED']
    : $_SERVER['SCRIPT_FILENAME'];

  if (str_replace("\\", "/", $file) == str_replace("\\", "/", $_serverVar))
  {
    header("Location: " . $redirect);
    exit();
  }
}
?>
