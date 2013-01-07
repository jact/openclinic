<?php
/**
 * Msg.php
 *
 * Contains the class Msg
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Msg.php,v 1.3 2013/01/07 18:36:19 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/HTML.php");

/**
 * Msg set of message functions
 *
 * Methods:
 *  string hint(string $text)
 *  string info(string $text)
 *  string warning(string $text)
 *  string error(string $text)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class Msg
{
  /**
   * string hint(string $text)
   *
   * @param string $text
   * @return string HTML message
   * @access public
   * @static
   */
  public static function hint($text)
  {
    return HTML::message($text, OPEN_MSG_HINT);
  }

  /**
   * string info(string $text)
   *
   * @param string $text
   * @return string HTML message
   * @access public
   * @static
   */
  public static function info($text)
  {
    return HTML::message($text, OPEN_MSG_INFO);
  }

  /**
   * string warning(string $text)
   *
   * @param string $text
   * @return string HTML message
   * @access public
   * @static
   */
  public static function warning($text)
  {
    return HTML::message($text, OPEN_MSG_WARNING);
  }

  /**
   * string error(string $text)
   *
   * @param string $text
   * @return string HTML message
   * @access public
   * @static
   */
  public static function error($text)
  {
    return HTML::message($text, OPEN_MSG_ERROR);
  }
} // end class
?>
