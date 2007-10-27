<?php
/**
 * Msg.php
 *
 * Contains the class Msg
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Msg.php,v 1.1 2007/10/27 17:16:45 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/HTML.php");

/**
 * Msg set of message functions
 *
 * Methods:
 *  string strHint(string $text)
 *  void hint(string $text)
 *  string strInfo(string $text)
 *  void info(string $text)
 *  string strWarning(string $text)
 *  void warning(string $text)
 *  string strError(string $text)
 *  void error(string $text)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class Msg
{
  /**
   * string strHint(string $text)
   *
   * @param string $text
   * @return string HTML message
   * @access public
   * @static
   */
  function strHint($text)
  {
    return HTML::strMessage($text, OPEN_MSG_HINT);
  }

  /**
   * void hint(string $text)
   *
   * @param string $text
   * @return void
   * @access public
   * @static
   */
  function hint($text)
  {
    HTML::message($text, OPEN_MSG_HINT);
  }

  /**
   * string strInfo(string $text)
   *
   * @param string $text
   * @return string HTML message
   * @access public
   * @static
   */
  function strInfo($text)
  {
    return HTML::strMessage($text, OPEN_MSG_INFO);
  }

  /**
   * void info(string $text)
   *
   * @param string $text
   * @return void
   * @access public
   * @static
   */
  function info($text)
  {
    HTML::message($text, OPEN_MSG_INFO);
  }

  /**
   * string strWarning(string $text)
   *
   * @param string $text
   * @return string HTML message
   * @access public
   * @static
   */
  function strWarning($text)
  {
    return HTML::strMessage($text, OPEN_MSG_WARNING);
  }

  /**
   * void warning(string $text)
   *
   * @param string $text
   * @return void
   * @access public
   * @static
   */
  function warning($text)
  {
    HTML::message($text, OPEN_MSG_WARNING);
  }

  /**
   * string strError(string $text)
   *
   * @param string $text
   * @return string HTML message
   * @access public
   * @static
   */
  function strError($text)
  {
    return HTML::strError($text, OPEN_MSG_ERROR);
  }

  /**
   * void error(string $text)
   *
   * @param string $text
   * @return void
   * @access public
   * @static
   */
  function error($text)
  {
    HTML::message($text, OPEN_MSG_ERROR);
  }
} // end class
?>
