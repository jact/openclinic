<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: misc_lib.php,v 1.10 2005/08/03 17:39:59 jact Exp $
 */

/**
 * misc_lib.php
 *
 * Set of miscellanean functions
 *
 * Author: jact <jachavar@gmail.com>
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
 * Converts an integer in an alphabetical string
 *
 * @author Mike (PHP manual, user contributes notes for chr() function)
 * @param int $number integer to convert
 * @return string alphabetical string
 * @access public
 * @since 0.8
 */
function numberToAlphabet($number)
{
  if ($number % 26 >= 1)
  {
    $alphaString = chr(($number % 26) + 64) . (isset($alphaString) ? $alphaString : "");
    $alphaString = numberToAlphabet($number / 26) . $alphaString;
  }

  return (isset($alphaString) ? $alphaString : "");
}
?>
