<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: lang_lib.php,v 1.5 2004/06/03 18:23:53 jact Exp $
 */

/**
 * lang_lib.php
 ********************************************************************
 * Set of language functions
 ********************************************************************
 * Author: jact <jachavar@terra.es>
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

define("LANG_DEFAULT",  "en");
define("LANG_DIR",      "../locale/");
define("LANG_FILENAME", "openclinic");

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
        setlocale(LC_ALL, $browserLanguage);
        $newLang = $browserLanguage;
      }
      else
      {
        setlocale(LC_ALL, LANG_DEFAULT);
        $newLang = LANG_DEFAULT;
      }
    }
  }
  else
  {
    if (languageExists($lang))
    {
      setlocale(LC_ALL, $lang);
      $newLang = $lang;
    }
    else
    {
      setlocale(LC_ALL, LANG_DEFAULT);
      $newLang = LANG_DEFAULT;
    }
  }
  putenv("LANG=" . $newLang);

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
    $textDomain = $lang . "-" . LANG_FILENAME;
    bindtextdomain($textDomain, realpath(LANG_DIR));
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
 * PO file will be in LANG_DIR
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

  $filename = LANG_DIR . $lang . "-" . LANG_FILENAME . ".po";
  if (file_exists($filename))
  {
    return $filename;
  }

  $filename = LANG_DIR . $lang . "/" . LANG_FILENAME . ".po";
  if (file_exists($filename))
  {
    return $filename;
  }

  $filename = LANG_DIR . $lang . "/" . $lang . "-" . LANG_FILENAME . ".po";
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
  if ($lang == LANG_DEFAULT)
  {
    return true;
  }

  $check = (in_array("gettext", get_loaded_extensions()) && function_exists('gettext'));
  if ($check)
  {
    return (file_exists(LANG_DIR . $lang . "/LC_MESSAGES/" . $lang . "-" . LANG_FILENAME . ".mo"));
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
 * @param string $date ISO date (Ymd or Y-m-d or YmdHis or Y-m-d H:i:s)
 * @return string returns local formated date
 * @access public
 */
function localDate($date = "")
{
  switch (strlen($date))
  {
    case 0:
      $local = date(_("Y-m-d H:i:s"));
      break;

    case 8: // Ymd
      $local = date(_("Y-m-d"), mktime(0, 0, 0, substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4)));
      break;

    case 10: // Y-m-d
      $parts = explode("-", $date);
      $local = date(_("Y-m-d"), mktime(0, 0, 0, $parts[1], $parts[2], $parts[0]));
      break;

    case 14: // YmdHis
      $local = date(_("Y-m-d H:i:s"), mktime(substr($date, 8, 2), substr($date, 10, 2), substr($date, 12, 2), substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4)));
      break;

    case 19: // Y-m-d H:i:s
      $parts = sscanf($date, "%d-%d-%d %d:%d:%d");
      $local = date(_("Y-m-d H:i:s"), mktime($parts[3], $parts[4], $parts[5], $parts[1], $parts[2], $parts[0]));
      break;

    default:
      $local = $date;
      break;
  }

  return $local;
}
?>
