<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Check.php,v 1.2 2005/08/16 15:13:59 jact Exp $
 */

/**
 * Check.php
 *
 * Contains the class Check
 *
 * Author: jact <jachavar@gmail.com>
 */

/**
 * Check set of functions to validate data
 *
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 *
 * Methods:
 *  bool hasMetas(string $text)
 *  mixed stripMetas(string $text)
 *  mixed customStrip(array $chars, string $text, bool $insensitive = false)
 *  string safeText(string $text, bool $allowTags = true, bool $includeEvents = true)
 *  array safeArray(array &$array)
 *  string basicClean(string $string)
 *  mixed removeMagicQuotes(mixed $data)
 */
class Check
{
  /*
   * bool hasMetas(string $text)
   *
   * Checks if a string has meta characters in it . \\ + * ? [ ^ ] ( $ )
   *
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
   *
   * Strips " . \\ + * ? [ ^ ] ( $ ) " from submitted string
   * Metas are a virtual MINE FIELD for regular expressions
   *
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
    $new = Check::customStrip($metas, $text);

    return $new;
  }

  /*
   * mixed customStrip(array $chars, string $text, bool $insensitive = false)
   *
   * $chars must be an array of characters to remove
   * This method is meta-character safe
   *
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
   *
   * This function sanitize a string value of suspicious contents
   *
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
      $value = Check::customStrip($events, $value, true); // case insensitive
      unset($events);
    }

    $value = ((get_magic_quotes_gpc()) ? $value : addslashes($value));

    return $value;
  }

  /*
   * array safeArray(array &$array)
   *
   * This function sanitize an array values of suspicious contents
   *
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
        $safeArray[$key] = Check::safeArray($value);
      }
      else
      {
        $safeArray[$key] = Check::safeText($value, false, false);
      }
    }

    return $safeArray;
  }

  /*
   * string basicClean(string $string)
   *
   * this basic clean should clean html code from
   * lot of possible malicious code for Cross Site Scripting
   * use it whereever you get external input
   *
   * @param string $string
   * @return string sanitized
   * @access public
   * @since 0.8
   */
  function basicClean($string)
  {
    if (get_magic_quotes_gpc())
    {
      $string = stripslashes($string);
    }
    $string = str_replace(array("&amp;", "&lt;", "&gt;"), array("&amp;amp;", "&amp;lt;", "&amp;gt;"), $string);

    // fix &entitiy\n;
    $string = preg_replace('#(&\#*\w+)[\x00-\x20]+;#u', "$1;", $string);
    $string = preg_replace('#(&\#x*)([0-9A-F]+);*#iu', "$1$2;", $string);
    $string = html_entity_decode($string, ENT_COMPAT, "UTF-8");

    // remove any attribute starting with "on" or xmlns
    $string = preg_replace('#(<[^>]+[\x00-\x20\"\'])(on|xmlns)[^>]*>#iUu', "$1>", $string);

    // remove javascript: and vbscript: protocol
    $string = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*)[\\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iUu', '$1=$2nojavascript...', $string);
    $string = preg_replace('#([a-z]*)[\x00-\x20]*=([\'\"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iUu', '$1=$2novbscript...', $string);

    // <span style="width: expression(alert('Ping!'));"></span>
    // only works in ie...
    $string = preg_replace('#(<[^>]+)style[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*).*expression[\x00-\x20]*\([^>]*>#iU', "$1>", $string);
    $string = preg_replace('#(<[^>]+)style[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*).*behaviour[\x00-\x20]*\([^>]*>#iU', "$1>", $string);
    $string = preg_replace('#(<[^>]+)style[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*).*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*>#iUu', "$1>", $string);

    // remove namespaced elements (we do not need them...)
    $string = preg_replace('#</*\w+:\w[^>]*>#i', "", $string);

    // remove really unwanted tags
    do {
      $oldstring = $string;
      $string = preg_replace('#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i', "", $string);
    } while ($oldstring != $string);

    return $string;
  }

  /*
   * mixed removeMagicQuotes(mixed $data)
   *
   * @param mixed $data
   * @return mixed stripslashed $data
   * @access public
   * @since 0.8
   */
  function removeMagicQuotes($data)
  {
    if (get_magic_quotes_gpc())
    {
      $newdata = array();
      foreach ($data as $name => $value)
      {
        $name = stripslashes($name);
        if (is_array($value))
        {
          $newdata[$name] = Check::removeMagicQuotes($value); //self::removeMagicQuotes($value);
        }
        else
        {
          $newdata[$name] = stripslashes($value);
        }
      }

      return $newdata;
    }

    return $data;
  }
} // end class
?>
