<?php
/**
 * FlashMsg.php
 *
 * Contains the class FlashMsg
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: FlashMsg.php,v 1.1 2007/10/16 20:00:14 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/HTML.php");

/**
 * FlashMsg set of intercommunications between pages functions
 *
 * Methods:
 *  bool add(string $message, int $type = OPEN_MSG_INFO, string $key = null)
 *  mixed get(string $key = null)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 * @todo array of messages
 */
class FlashMsg
{
  /**
   * bool add(string $message, int $type = OPEN_MSG_INFO, string $key = null)
   *
   * @param string $message
   * @param int $type (optional)
   * @param string $key (optional)
   * @return bool
   * @access public
   * @static
   */
  function add($message, $type = OPEN_MSG_INFO, $key = null)
  {
    if (empty($message))
    {
      return false;
    }

    if (isset($key)) // "private" message
    {
      $_SESSION['flash_msg'][$key]['msg'] = $message;
      $_SESSION['flash_msg'][$key]['type'] = $type;
    }
    else // "public" message
    {
      $_SESSION['flash_msg']['msg'] = $message;
      $_SESSION['flash_msg']['type'] = $type;
    }

    return true;
  }

  /**
   * mixed get(string $key = null)
   *
   * @param string $key (optional) for "private" message
   * @return mixed if success string, null otherwise
   * @access public
   * @static
   */
  function get($key = null)
  {
    if ( !isset($key) )
    {
      if (isset($_SESSION['flash_msg']['msg']))
      {
        $message = $_SESSION['flash_msg']['msg'];
        $type = $_SESSION['flash_msg']['type'];
        unset($_SESSION['flash_msg']['msg']);
        unset($_SESSION['flash_msg']['type']);
      }
    }
    else
    {
      if (isset($_SESSION['flash_msg'][$key]['msg']))
      {
        $message = $_SESSION['flash_msg'][$key]['msg'];
        $type = $_SESSION['flash_msg'][$key]['type'];
        unset($_SESSION['flash_msg'][$key]['msg']);
        unset($_SESSION['flash_msg'][$key]['type']);
      }
    }

    if ( !isset($message) )
    {
      return null;
    }

    return HTML::strMessage($message, $type);
  }
}
?>
