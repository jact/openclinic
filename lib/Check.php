<?php
/**
 * Check.php
 *
 * Contains the class Check
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Check.php,v 1.11 2013/01/07 18:32:04 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

/**
 * Sources constants
 * Input Filter extension: Rasmus Lerdorf, Derick Rethans
 */
define("CHK_GET",     1);
define("CHK_POST",    2);
define("CHK_COOKIE",  3);
define("CHK_ENV",     4);
define("CHK_SERVER",  5);
define("CHK_SESSION", 6);

/**
 * Logical filters constants
 * Input Filter extension: Rasmus Lerdorf, Derick Rethans
 */
define("CHK_LF_INT",     0x0101);
define("CHK_LF_BOOLEAN", 0x0102);
define("CHK_LF_FLOAT",   0x0103);
define("CHK_LF_EMAIL",   0x0112);
define("CHK_LF_IP",      0x0113);

/**
 * Sanitizing filters constants
 * Input Filter extension: Rasmus Lerdorf, Derick Rethans
 */
define("CHK_SF_STRING",        0x0201);
define("CHK_SF_ENCODED",       0x0202);
define("CHK_SF_SPECIAL_CHARS", 0x0203);
define("CHK_SF_UNSAFE_RAW",    0x0204);

/**
 * Filter options constants
 * Input Filter extension: Rasmus Lerdorf, Derick Rethans
 */
define("CHK_ALLOW_OCTAL", 0x0001);
define("CHK_ALLOW_HEX",   0x0002);

define("CHK_STRIP_LOW",   0x0004);
define("CHK_STRIP_HIGH",  0x0008);
define("CHK_ENCODE_LOW",  0x0010);
define("CHK_ENCODE_HIGH", 0x0020);
define("CHK_ENCODE_AMP",  0x0040);

define("CHK_IPV4",        0x100000);
define("CHK_IPV6",        0x200000);

/**
 * Other constants
 * Input Filter extension: Rasmus Lerdorf, Derick Rethans
 */
define("CHK_NO_FILTER", 0);

/**
 * CHK_ALLOWED_HTML_TAGS - tags which should not be stripped by strip_tags() function
 */
define("CHK_ALLOWED_HTML_TAGS", "<a><b><blockquote><br><code><div><em><i><li><ol><p><pre><strike><strong><sub><sup><tt><u><ul><hr>");

/**
 * Check set of functions to validate and filter data
 *
 * Methods:
 *  bool hasMetas(string $text)
 *  mixed stripMetas(string $text)
 *  mixed customStrip(array $chars, string $text, bool $insensitive = false)
 *  string safeText(string $text, bool $allowTags = true, bool $includeEvents = true)
 *  array safeArray(array &$array)
 *  string basicClean(string $string)
 *  mixed removeMagicQuotes(mixed $data)
 *  mixed getVar(int $source, string $name, int $filter = CHK_NO_FILTER, mixed $options = null, string $characterset = "")
 *  mixed filter(mixed $value, int $filter, mixed $options = null, string $characterset = "")
 *  bool isVar(int $source, string $name)
 *  mixed _rawVar(int $source, string $name)
 *  mixed postGetSessionInt(string $field, mixed $default = 0)
 *  mixed postGetSessionString(string $field, mixed $default = "")
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class Check
{
  /**
   * bool hasMetas(string $text)
   *
   * Checks if a string has meta characters in it . \\ + * ? [ ^ ] ( $ )
   *
   * @param string $text
   * @return bool true if the submitted text has meta characters in it
   * @access public
   * @static
   */
  public static function hasMetas($text)
  {
    if (empty($text))
    {
      return false;
    }

    $new = quotemeta($text);

    return ($new != $text);
  }

  /**
   * mixed stripMetas(string $text)
   *
   * Strips " . \\ + * ? [ ^ ] ( $ ) " from submitted string
   * Metas are a virtual MINE FIELD for regular expressions
   *
   * @param string $text
   * @return mixed false if submitted string is empty, string otherwise
   * @access public
   * @static
   * @see customStrip() for how they are removed
   */
  public static function stripMetas($text)
  {
    if (empty($text))
    {
      return false;
    }

    $metas = array('.', '+', '*', '?', '[', '^', ']', '(', '$', ')', '\\');
    $new = self::customStrip($metas, $text);

    return $new;
  }

  /**
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
   * @static
   */
  public static function customStrip($chars, $text, $insensitive = false)
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

  /**
   * string safeText(string $text, bool $allowTags = true, bool $includeEvents = true)
   *
   * This function sanitize a string value of suspicious contents
   *
   * @param string $text
   * @param bool $allowTags (optional) to allow allowed tags
   * @param bool $includeEvents (optional) to strip JavaScript event handlers
   * @return string sanitized text
   * @access public
   * @static
   * @see customStrip() for how they are removed
   */
  public static function safeText($text, $allowTags = true, $includeEvents = true)
  {
    if ($allowTags)
    {
      $value = trim(htmlspecialchars(strip_tags($text, CHK_ALLOWED_HTML_TAGS)));
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
      $value = self::customStrip($events, $value, true); // case insensitive
      unset($events);
    }

    $value = ((get_magic_quotes_gpc()) ? $value : addslashes($value));

    return $value;
  }

  /**
   * array safeArray(array &$array)
   *
   * This function sanitize an array values of suspicious contents
   *
   * @param array &$array
   * @return array sanitized array
   * @access public
   * @static
   * @see safeText() for how they are removed
   * @since 0.7
   */
  public static function safeArray(&$array)
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
        $safeArray[$key] = self::safeArray($value);
      }
      else
      {
        $safeArray[$key] = self::safeText($value, false, false);
      }
    }

    return $safeArray;
  }

  /**
   * string basicClean(string $string)
   *
   * this basic clean should clean html code from
   * lot of possible malicious code for Cross Site Scripting
   * use it whereever you get external input
   *
   * @param string $string
   * @return string sanitized
   * @access public
   * @static
   * @since 0.8
   */
  public static function basicClean($string)
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

  /**
   * mixed removeMagicQuotes(mixed $data)
   *
   * @param mixed $data
   * @return mixed stripslashed $data
   * @access public
   * @static
   * @since 0.8
   */
  public static function removeMagicQuotes($data)
  {
    if (get_magic_quotes_gpc())
    {
      $newdata = array();
      foreach ($data as $name => $value)
      {
        $name = stripslashes($name);
        if (is_array($value))
        {
          $newdata[$name] = self::removeMagicQuotes($value);
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

  /**
   * mixed getVar(int $source, string $name, int $filter = CHK_NO_FILTER, mixed $options = null, string $characterset = "")
   *
   * @param int $source (@see Sources constants)
   * @param string $name
   * @param int $filter (optional)
   * @param mixed $options (optional) filter options
   * @param string $characterset (optional)
   * @return mixed
   * @access public
   * @static
   * @since 0.8
   */
  public static function getVar($source, $name, $filter = CHK_NO_FILTER, $options = null, $characterset = "")
  {
    if ( !self::isVar($source, $name) )
    {
      return false;
    }

    $value = self::_rawVar($source, $name);
    if ($filter == CHK_NO_FILTER || $filter == CHK_UNSAFE_RAW)
    {
      return $value;
    }

    return self::filter($value, $filter, $options, $characterset);
  }

  /**
   * mixed filter(mixed $value, int $filter, mixed $options = null, string $characterset = "")
   *
   * @param mixed $value
   * @param int $filter
   * @param mixed $options (optional) filter options
   * @param string $characterset (optional)
   * @return mixed
   * @access public
   * @static
   * @since 0.8
   */
  public static function filter($value, $filter, $options = null, $characterset = "")
  {
    $ret = $value;
    switch ($filter)
    {
      case CHK_LF_INT:
        // @todo filter options CHK_ALLOW_OCTAL, CHK_ALLOW_HEX
        $ret = intval($value);
        break;

      case CHK_LF_FLOAT:
        $ret = floatval($value);
        break;

      case CHK_LF_BOOLEAN:
        if (gettype($value) == 'string')
        {
          $value = strtolower($value);
        }
        $ret = ($value == 1 || $value == 'on' || $value == 'y' || $value == 'yes');
        break;

      case CHK_LF_EMAIL:
        // @todo
        break;

      case CHK_LF_IP:
        // @todo (CHK_IPV4, CHK_IPV6)
        break;

      case CHK_SF_STRING:
        // @todo filter options CHK_STRIP_LOW, CHK_STRIP_HIGH, CHK_ENCODE_LOW, CHK_ENCODE_HIGH, CHK_ENCODE_AMP
        $ret = self::safeText($value);
        break;

      case CHK_SF_ENCODED:
        // @todo
        break;

      case CHK_SF_SPECIAL_CHARS:
        // @todo
        break;
    }

    return $ret;
  }

  /**
   * bool isVar(int $source, string $name)
   *
   * @param int $source
   * @param string $name
   * @return boolean
   * @access public
   * @static
   * @since 0.8
   */
  public static function isVar($source, $name)
  {
    $ret = false;
    switch ($source)
    {
      case CHK_GET:
        $ret = array_key_exists($name, $_GET);
        break;

      case CHK_POST:
        $ret = array_key_exists($name, $_POST);
        break;

      case CHK_COOKIE:
        $ret = array_key_exists($name, $_COOKIE);
        break;

      case CHK_ENV:
        $ret = array_key_exists($name, $_ENV);
        break;

      case CHK_SERVER:
        $ret = array_key_exists($name, $_SERVER);
        break;

      case CHK_SESSION:
        $ret = array_key_exists($name, $_SESSION);
        break;
    }

    return $ret;
  }

  /**
   * mixed _rawVar(int $source, string $name)
   *
   * @param int $source
   * @param string $name
   * @return mixed
   * @access private
   * @static
   * @since 0.8
   */
  private static function _rawVar($source, $name)
  {
    /*if ( !self::isVar($source, $name) )
    {
      return false;
    }*/

    $ret = false;
    switch ($source)
    {
      case CHK_GET:
        $ret = $_GET[$name];
        break;

      case CHK_POST:
        $ret = $_POST[$name];
        break;

      case CHK_COOKIE:
        $ret = $_COOKIE[$name];
        break;

      case CHK_ENV:
        $ret = $_ENV[$name];
        break;

      case CHK_SERVER:
        $ret = $_SERVER[$name];
        break;

      case CHK_SESSION:
        $ret = $_SESSION[$name];
        break;
    }

    return $ret;
  }

  /**
   * mixed postGetSessionInt(string $field, mixed $default = 0)
   *
   * Returns a string value in order POST, GET, SESSION
   *
   * @param string $field
   * @param mixed $default (optional)
   * @return mixed (int if ok, zero or default otherwise)
   * @access public
   * @static
   */
  public static function postGetSessionInt($field, $default = 0)
  {
    $_value = $default;
    if (isset($_POST[$field]))
    {
      $_value = intval($_POST[$field]);
      $_SESSION['breadcrumb'][$field] = $_value;
    }
    elseif (isset($_GET[$field]))
    {
      $_value = intval($_GET[$field]);
      $_SESSION['breadcrumb'][$field] = $_value;
    }
    elseif (isset($_SESSION['breadcrumb'][$field]))
    {
      $_value = $_SESSION['breadcrumb'][$field];
    }

    return $_value;
  }

  /**
   * mixed postGetSessionString(string $field, mixed $default = "")
   *
   * Returns a string value in order POST, GET, SESSION
   *
   * @param string $field
   * @param mixed $default (optional)
   * @return mixed (string if ok, empty string or default otherwise)
   * @access public
   * @static
   */
  public static function postGetSessionString($field, $default = "")
  {
    $_value = $default;
    if (isset($_POST[$field]))
    {
      $_value = self::safeText($_POST[$field]);
      $_SESSION['breadcrumb'][$field] = $_value;
    }
    elseif (isset($_GET[$field]))
    {
      $_value = self::safeText($_GET[$field]);
      $_SESSION['breadcrumb'][$field] = $_value;
    }
    elseif (isset($_SESSION['breadcrumb'][$field]))
    {
      $_value = $_SESSION['breadcrumb'][$field];
    }

    return $_value;
  }
} // end class
?>
