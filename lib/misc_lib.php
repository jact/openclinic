<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: misc_lib.php,v 1.12 2006/03/26 15:02:57 jact Exp $
 */

/**
 * misc_lib.php
 *
 * Set of miscellanean functions
 *
 * @author jact <jachavar@gmail.com>
 * @since 0.7
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

/**
 * Functions:
 *  string fieldPreview(string $field)
 *  string translateBrowser(string $text)
 *  string unTranslateBrowser(string $text)
 *  string numberToAlphabet(int $number)
 */

/**
 * string fieldPreview(string $field)
 *
 * Returns a preview of a memo field
 *
 * @param string $field
 * @return string preview of field
 * @access public
 */
function fieldPreview($field)
{
  if ( !defined("OPEN_FIELD_PREVIEW_LIMIT") )
  {
    return $field; // global_constants.php is not included
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
    $preview .= " ...";
  }

  return $preview;
}

/*
 * string translateBrowser(string $text)
 *
 * Returns a string ready to see in a web browser
 *
 * @param string $text
 * @return string
 * @access public
 * @since 0.8
 */
function translateBrowser($text)
{
  return ((strpos($_SERVER["HTTP_USER_AGENT"], "Gecko") === false) ? $text : utf8_encode($text));
}

/*
 * string unTranslateBrowser(string $text)
 *
 * Returns a string ready to insert it in a database
 *
 * @param string $text
 * @return string
 * @access public
 * @since 0.8
 */
function unTranslateBrowser($text)
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
 * @since 0.8
 */
function numberToAlphabet($number)
{
  return ($number-- > 26 ? chr(($number / 26 + 25) % 26 + ord('A')) : '') . chr($number % 26 + ord('A'));
}
?>
