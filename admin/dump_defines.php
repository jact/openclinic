<?php
/**
 * dump_defines.php
 *
 * Definition constants needed for the dump process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: dump_defines.php,v 1.9 2006/10/16 18:07:44 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

/**
 * Overview:
 *  DLIB_MYSQL_INT_VERSION    (int)    - eg: 32339 instead of 3.23.39
 *  DLIB_MYSQL_VERSION        (string) - eg: 3.23.39
 *  DLIB_USR_OS               (string) - the platform (os) of the user
 *  DLIB_USR_BROWSER_AGENT    (string) - the browser of the user
 *  DLIB_USR_BROWSER_VER      (double) - the version of this browser
 *  DLIB_CRLF                 (string) - CR LF sequence
 */

/**
 * DLIB_MYSQL_INT_VERSION, DLIB_MYSQL_VERSION
 */
if ( !defined('DLIB_MYSQL_INT_VERSION') )
{
  if (defined('OPEN_HOST'))
  {
    $auxConn = new DbConnection();
    $result = $auxConn->connect();
    if ($result != false)
    {
      $result = $auxConn->exec("SELECT VERSION() AS version;");
      if ($result != false && $auxConn->numRows() > 0)
      {
        $row   = $auxConn->fetchRow(MYSQL_ASSOC);
        define('DLIB_MYSQL_VERSION', $row['version']);
        $match = explode('.', $row['version']);
      }
      else
      {
        $result = $auxConn->exec("SHOW VARIABLES LIKE 'version';");
        if ($result != false && $auxConn->numRows() > 0)
        {
          $row   = $auxConn->fetchRow(MYSQL_NUM);
          define('DLIB_MYSQL_VERSION', $row[1]);
          $match = explode('.', $row[1]);
        }
      }
    }
    $auxConn->close();
    unset($auxConn);
  } // end server id is defined case

  if ( !isset($match) || !isset($match[0]) )
  {
    $match[0] = 3;
  }
  if ( !isset($match[1]) )
  {
    $match[1] = 21;
  }
  if ( !isset($match[2]) )
  {
    $match[2] = 0;
  }

  define('DLIB_MYSQL_INT_VERSION', (int)sprintf('%d%02d%02d', $match[0], $match[1], intval($match[2])));
  unset($match);
}

/**
 * DLIB_USR_OS, DLIB_USR_BROWSER_VER, DLIB_USR_BROWSER_AGENT
 * Determines platform (OS), browser and version of the user
 * Based on a phpBuilder article:
 * @see http://www.phpbuilder.net/columns/tim20000821.php
 */
if ( !defined('DLIB_USR_OS') )
{
  if ( !empty($_SERVER['HTTP_USER_AGENT']) )
  {
    $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
  }
  elseif ( !isset($HTTP_USER_AGENT) )
  {
    $HTTP_USER_AGENT = '';
  }

  // 1. Platform
  if (strstr($HTTP_USER_AGENT, 'Win'))
  {
    define('DLIB_USR_OS', 'Win');
  }
  elseif (strstr($HTTP_USER_AGENT, 'Mac'))
  {
    define('DLIB_USR_OS', 'Mac');
  }
  elseif (strstr($HTTP_USER_AGENT, 'Linux'))
  {
    define('DLIB_USR_OS', 'Linux');
  }
  elseif (strstr($HTTP_USER_AGENT, 'Unix'))
  {
    define('DLIB_USR_OS', 'Unix');
  }
  elseif (strstr($HTTP_USER_AGENT, 'OS/2'))
  {
    define('DLIB_USR_OS', 'OS/2');
  }
  else
  {
    define('DLIB_USR_OS', 'Other');
  }

  // 2. browser and version
  if (ereg('Opera(/| )([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $logVersion))
  {
    define('DLIB_USR_BROWSER_VER', $logVersion[2]);
    define('DLIB_USR_BROWSER_AGENT', 'OPERA');
  }
  elseif (ereg('MSIE ([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $logVersion))
  {
    define('DLIB_USR_BROWSER_VER', $logVersion[1]);
    define('DLIB_USR_BROWSER_AGENT', 'IE');
  }
  elseif (ereg('OmniWeb/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $logVersion))
  {
    define('DLIB_USR_BROWSER_VER', $logVersion[1]);
    define('DLIB_USR_BROWSER_AGENT', 'OMNIWEB');
  }
  elseif (ereg('Mozilla/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $logVersion))
  {
    define('DLIB_USR_BROWSER_VER', $logVersion[1]);
    define('DLIB_USR_BROWSER_AGENT', 'MOZILLA');
  }
  elseif (ereg('Konqueror/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $logVersion))
  {
    define('DLIB_USR_BROWSER_VER', $logVersion[1]);
    define('DLIB_USR_BROWSER_AGENT', 'KONQUEROR');
  }
  else
  {
    define('DLIB_USR_BROWSER_VER', 0);
    define('DLIB_USR_BROWSER_AGENT', 'OTHER');
  }
}

if (defined("DLIB_USR_OS") && DLIB_USR_OS == 'Win')
{
  define("DLIB_CRLF", "\r\n");
}
// Mac case
elseif (defined("DLIB_USR_OS") && DLIB_USR_OS == 'Mac')
{
  define("DLIB_CRLF", "\r");
}
// Others
else
{
  define("DLIB_CRLF", "\n");
}
?>
