<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: lang_lib.php,v 1.11 2004/10/04 21:30:13 jact Exp $
 */

/**
 * lang_lib.php
 ********************************************************************
 * Set of language functions
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * @since 0.6
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

/**
 * Functions:
 *  string setLanguage(string $lang = "")
 *  void initLanguage(string $lang)
 *  void emulateGetText(void)
 *  mixed poFilename(string $lang = "")
 *  bool languageExists(string $lang)
 *  string localDate(string $date)
 */

define("OPEN_LANG_DEFAULT",  "en");
define("OPEN_LANG_DIR",      "../locale/");
define("OPEN_LANG_FILENAME", "openclinic");

/**
 * string setLanguage(string $lang = "")
 ********************************************************************
 * Sets a language to locale options
 ********************************************************************
 * @param string $lang (optional)
 * @return string new language setted
 * @access public
 */
function setLanguage($lang = "")
{
  if (empty($lang))
  {
    // Detect Browser Language
    if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
    {
      $language = explode(",", $_SERVER["HTTP_ACCEPT_LANGUAGE"]);
      $langPieces = explode("-", $language[0]);

      if (strlen($language[0]) == 2)
      {
        $browserLanguage = $language[0] . "_" . strtoupper($language[0]);
      }
      else
      {
        $browserLanguage = strtolower($langPieces[0]). "_" . strtoupper($langPieces[1]);
      }
      if (languageExists($browserLanguage))
      {
        $newLang = $browserLanguage;
      }
      else
      {
        $newLang = OPEN_LANG_DEFAULT;
      }
    }
  }
  else
  {
    if (languageExists($lang))
    {
      $newLang = $lang;
    }
    else
    {
      $newLang = OPEN_LANG_DEFAULT;
    }
  }
  putenv("LANG=" . $newLang);
  setlocale(LC_ALL, $newLang);

  /*global $nls;
  if (defined("PHP_OS") && eregi("win", PHP_OS))
  {
    setlocale(LC_ALL, (isset($nls['win32'][$newLang]) ? $nls['win32'][$newLang] : $newLang));
    //echo $nls['win32'][$newLang]; echo $newLang; exit(); // debug
  }
  else
  {
    setlocale(LC_ALL, $newLang);
  }*/

  return $newLang;
}

/**
 * void initLanguage(string $lang)
 ********************************************************************
 * Initializes a language for gettext or "emulateGetText"
 ********************************************************************
 * @param string $lang
 * @return void
 * @access public
 */
function initLanguage($lang)
{
  ////////////////////////////////////////////////////////////////////
  // Test if we're using gettext. If yes, do some gettext settings.
  // If not emulate _() function
  ////////////////////////////////////////////////////////////////////
  $check = (in_array("gettext", get_loaded_extensions()) && function_exists('gettext'));
  if ($check)
  {
    $textDomain = $lang . "-" . OPEN_LANG_FILENAME;
    bindtextdomain($textDomain, realpath(OPEN_LANG_DIR));
    textdomain($textDomain);
  }
  else
  {
    emulateGetText();
  }
}

/**
 * void emulateGetText(void)
 ********************************************************************
 * Emulates gettext's mecanism
 ********************************************************************
 * @global array $translation
 * @return void
 * @access public
 */
function emulateGetText()
{
  global $translation;

  $filename = poFilename();
  if ($filename)
  {
    $lines = file($filename);

    foreach ($lines as $key => $value)
    {
      if (stristr($value, "msgid"))
      {
        $newKey = substr($value, 7, -2);
        $translation[$newKey] = substr($lines[$key + 1], 8, -2);
      }
    }

    // Substitute _() gettext function
    function _($search)
    {
      if ( !empty($GLOBALS['translation'][$search]) )
      {
        return $GLOBALS['translation'][$search];
      }
      else
      {
        return $search;
      }
    }
  }
  // There is no translation file, so just return what we got
  else
  {
    function _($search)
    {
      return $search;
    }
  }
}

/**
 * mixed poFilename(string $lang = "")
 ********************************************************************
 * PO file will be in OPEN_LANG_DIR
 ********************************************************************
 * @param string $lang (optional)
 * @return mixed false if .po file doesn't exist or string with filename if it exists
 * @access public
 */
function poFilename($lang = "")
{
  if ($lang == "")
  {
    $lang = OPEN_LANGUAGE;
  }

  $filename = OPEN_LANG_DIR . $lang . "-" . OPEN_LANG_FILENAME . ".po";
  if (file_exists($filename))
  {
    return $filename;
  }

  $filename = OPEN_LANG_DIR . $lang . "/" . OPEN_LANG_FILENAME . ".po";
  if (file_exists($filename))
  {
    return $filename;
  }

  $filename = OPEN_LANG_DIR . $lang . "/" . $lang . "-" . OPEN_LANG_FILENAME . ".po";
  if (file_exists($filename))
  {
    return $filename;
  }

  return false;
}

/**
 * bool languageExists(string $lang)
 ********************************************************************
 * Checks .po .mo files
 ********************************************************************
 * @param string $lang
 * @return bool returns true if gettext is defined, and the .mo file is found for language, or no gettext, and a .po file is found
 * @access public
 */
function languageExists($lang)
{
  if ($lang == OPEN_LANG_DEFAULT)
  {
    return true;
  }

  $check = (in_array("gettext", get_loaded_extensions()) && function_exists('gettext'));
  if ($check)
  {
    return (file_exists(OPEN_LANG_DIR . $lang . "/LC_MESSAGES/" . $lang . "-" . OPEN_LANG_FILENAME . ".mo"));
  }
  else
  {
    return (poFilename($lang) ? true : false);
  }
}

/**
 * string localDate(string $date = "")
 ********************************************************************
 * Returns a date in a local format
 ********************************************************************
 * @param string $date (optional) ISO date (Ymd or Y-m-d or YmdHis or Y-m-d H:i:s)
 * @return string returns local formated date
 * @access public
 * @since 0.7
 */
function localDate($date = "")
{
  switch (strlen($date))
  {
    case 0:
      $local = date(_("Y-m-d H:i:s"));
      break;

    case 8: // Ymd
      if ($date != str_repeat("0", 8))
      {
        $local = date(_("Y-m-d"), mktime(0, 0, 0, substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4)));
      }
      else
      {
        $local = "";
      }
      break;

    case 10: // Y-m-d
      if ($date != "0000-00-00")
      {
        $parts = explode("-", $date);
        $local = date(_("Y-m-d"), mktime(0, 0, 0, $parts[1], $parts[2], $parts[0]));
      }
      else
      {
        $local = "";
      }
      break;

    case 14: // YmdHis
      if ($date != str_repeat("0", 14))
      {
        $local = date(_("Y-m-d H:i:s"), mktime(substr($date, 8, 2), substr($date, 10, 2), substr($date, 12, 2), substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4)));
      }
      else
      {
        $local = "";
      }
      break;

    case 19: // Y-m-d H:i:s
      if ($date != "0000-00-00 00:00:00")
      {
        $parts = sscanf($date, "%d-%d-%d %d:%d:%d");
        $local = date(_("Y-m-d H:i:s"), mktime($parts[3], $parts[4], $parts[5], $parts[1], $parts[2], $parts[0]));
      }
      else
      {
        $local = "";
      }
      break;

    default:
      $local = $date;
      break;
  }

  return $local;
}
?>
