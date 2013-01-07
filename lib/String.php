<?php
/**
 * String.php
 *
 * Contains the class String
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: String.php,v 1.2 2013/01/07 18:37:35 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

define("OPEN_FIELD_PREVIEW_LIMIT", 30);  // max length to preview text fields

/**
 * String set of text functions
 *
 * Methods:
 *  string fieldPreview(string $field)
 *  string translateBrowser(string $text)
 *  string unTranslateBrowser(string $text)
 *  string numberToAlphabet(int $number)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class String
{
  /**
   * string fieldPreview(string $field)
   *
   * Returns a preview of a memo field
   *
   * @param string $field
   * @return string preview of field
   * @access public
   * @static
   */
  public static function fieldPreview($field)
  {
    if ( !defined("OPEN_FIELD_PREVIEW_LIMIT") )
    {
      return $field; // constant is not defined!
    }

    $array = explode(" ", $field);
    $preview = array_shift($array); // first word at least
    foreach ($array as $value)
    {
      if (strlen($preview . $value) < OPEN_FIELD_PREVIEW_LIMIT)
      {
        $preview .= " " . $value;
      }
    }
    if (strlen($preview) < strlen($field))
    {
      $preview .= "...";
    }

    return $preview;
  }

  /**
   * string translateBrowser(string $text)
   *
   * Returns a string ready to see in a web browser
   *
   * @param string $text
   * @return string
   * @access public
   * @static
   */
  public static function translateBrowser($text)
  {
    return ((strpos($_SERVER["HTTP_USER_AGENT"], "Gecko") === false) ? $text : utf8_encode($text));
  }

  /**
   * string unTranslateBrowser(string $text)
   *
   * Returns a string ready to insert it in a database
   *
   * @param string $text
   * @return string
   * @access public
   * @static
   */
  public static function unTranslateBrowser($text)
  {
    return ((strpos($_SERVER["HTTP_USER_AGENT"], "Gecko") === false) ? $text : utf8_decode($text));
  }

  /**
   * string numberToAlphabet(int $number)
   *
   * Converts an integer in an alphabetical string (1 <= $number <= 702)
   *
   * @author PHP manual, user contributes notes for chr() function
   * @param int $number integer to convert
   * @return string alphabetical string
   * @access public
   * @static
   */
  public static function numberToAlphabet($number)
  {
    return ($number-- > 26 ? chr(($number / 26 + 25) % 26 + ord('A')) : '') . chr($number % 26 + ord('A'));
  }
} // end class
?>
