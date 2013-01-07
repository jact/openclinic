<?php
/**
 * FlashMsg.php
 *
 * Contains the class FlashMsg
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: FlashMsg.php,v 1.4 2013/01/07 18:33:50 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/HTML.php");

/**
 * FlashMsg set of intercommunications between pages functions
 *
 * Methods:
 *  bool add(string $message, int $type = OPEN_MSG_INFO, string $key = null)
 *  string get(string $key = null)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
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
  public static function add($message, $type = OPEN_MSG_INFO, $key = null)
  {
    if (empty($message))
    {
      return false;
    }

    if (isset($key)) // "private" message
    {
      $_SESSION['flash_msg'][$key][] = array('msg' => $message, 'type' => $type);
    }
    else // "public" message
    {
      $_SESSION['flash_msg_public'][] = array('msg' => $message, 'type' => $type);
    }

    return true;
  }

  /**
   * string get(string $key = null)
   *
   * @param string $key (optional) for "private" message
   * @return string
   * @access public
   * @static
   */
  public static function get($key = null)
  {
    $_html = '';

    if ( !isset($key) ) // "public" message(s)
    {
      if (isset($_SESSION['flash_msg_public']))
      {
        foreach ($_SESSION['flash_msg_public'] as $_value)
        {
          $_html .= HTML::message($_value['msg'], $_value['type']);
        }
        unset($_SESSION['flash_msg_public']);
      }
    }
    else // "private" message(s)
    {
      if (isset($_SESSION['flash_msg'][$key]))
      {
        foreach ($_SESSION['flash_msg'][$key] as $_value)
        {
          $_html .= HTML::message($_value['msg'], $_value['type']);
        }
        unset($_SESSION['flash_msg'][$key]);
      }
    }

    return $_html;
  }
}
?>
