<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: validator_lib.php,v 1.8 2004/10/17 14:56:40 jact Exp $
 */

/**
 * validator_lib.php
 ********************************************************************
 * Functions to validate data
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * @since 0.4
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['PATH_TRANSLATED'])
  {
    header("Location: ../index.php");
    exit();
  }

/**
 * Functions:
 *  bool hasMetas(string $text)
 *  mixed stripMetas(string $text)
 *  mixed customStrip(array $chars, string $text, bool $insensitive = false)
 *  string safeText(string $text, bool $allowTags = true, bool $includeEvents = true)
 */

/*
 * bool hasMetas(string $text)
 ********************************************************************
 * Checks if a string has meta characters in it . \\ + * ? [ ^ ] ( $ )
 ********************************************************************
 * @param string $text
 * @return bool true if the submitted text has meta characters in it
 * @access public
 */
function hasMetas($text)
{
  if (empty($text))
  {
    return false;
  }

  $new = quotemeta($text);

  return ($new != $text);
}

/*
 * mixed stripMetas(string $text)
 ********************************************************************
 * Strips " . \\ + * ? [ ^ ] ( $ ) " from submitted string
 * Metas are a virtual MINE FIELD for regular expressions
 ********************************************************************
 * @param string $text
 * @return mixed false if submitted string is empty, string otherwise
 * @access public
 * @see customStrip() for how they are removed
 */
function stripMetas($text)
{
  if (empty($text))
  {
    return false;
  }

  $metas = array('.', '+', '*', '?', '[', '^', ']', '(', '$', ')', '\\');
  $new = customStrip($metas, $text);

  return $new;
}

/*
 * mixed customStrip(array $chars, string $text, bool $insensitive = false)
 ********************************************************************
 * $chars must be an array of characters to remove
 * This method is meta-character safe
 ********************************************************************
 * @param array (string) $chars
 * @param string $text
 * @param bool $insensitive (optional)
 * @return mixed false if submitted string is empty, string otherwise
 * @access public
 */
function customStrip($chars, $text, $insensitive = false)
{
  if (empty($text))
  {
    return false;
  }

  if (gettype($chars) != "array")
  {
    $this->_error = "customStrip: [$chars] is not an array";
    return false;
  }

  while (list($key, $val) = each($chars))
  {
    if ( !empty($val) )
    {
      if ($insensitive)
      {
        if (function_exists('str_ireplace')) // in PHP 5.0, use str_ireplace()
        {
          $text = str_ireplace($val, "", $text);
        }
        else
        {
          $text = eregi_replace($val, "", $text);
        }
      }
      else
      {
        // str_replace is meta-safe, ereg_replace is not
        $text = str_replace($val, "", $text);
      }
    }
  }

  return $text;
}

/*
 * string safeText(string $text, bool $allowTags = true, bool $includeEvents = true)
 ********************************************************************
 * This function sanitize a string value of suspicious contents
 ********************************************************************
 * @param string $text
 * @param bool $allowTags (optional) to allow allowed tags
 * @param bool $includeEvents (optional) to strip JavaScript event handlers
 * @return string sanitized text
 * @access public
 * @see customStrip() for how they are removed
 */
function safeText($text, $allowTags = true, $includeEvents = true)
{
  if ($allowTags)
  {
    $value = trim(htmlspecialchars(strip_tags($text, OPEN_ALLOWED_HTML_TAGS)));
  }
  else
  {
    $value = trim(htmlspecialchars(strip_tags($text)));
  }

  if ($includeEvents)
  {
    $events = array(
      "onmousedown", "onmouseup", "onclick", "ondblclick", "onmouseover", "onmouseout", "onselect",
      "onkeydown", "onkeypress", "onkeyup",
      "onblur", "onfocus",
      "onreset", "onsubmit",
      "onload", "onunload", "onresize",
      "onabort", "onchange", "onerror"
    );
    $value = customStrip($events, $value, true); // case insensitive
    unset($events);
  }

  $value = ((get_magic_quotes_gpc()) ? $value : addslashes($value));

  return $value;
}

/*
 * array safeArray(array &$array)
 ********************************************************************
 * This function sanitize an array values of suspicious contents
 ********************************************************************
 * @param array &$array
 * @return array sanitized array
 * @access public
 * @see safeText() for how they are removed
 * @since 0.7
 */
function safeArray(&$array)
{
  if ( !is_array($array) )
  {
    return null;
  }

  $safeArray = array();
  foreach ($array as $key => $value)
  {
    if (is_array($value))
    {
      $safeArray[$key] = safeArray($value);
    }
    else
    {
      $safeArray[$key] = safeText($value, false, false);
    }
  }

  return $safeArray;
}
?>
