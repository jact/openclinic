<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: lang_lib.php,v 1.3 2004/05/20 18:31:21 jact Exp $
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
 */

define("LANG_DEFAULT",  "en");
define("LANG_DIR",      "../locale/");
define("LANG_FILENAME", "openclinic");

/**
 * string setLanguage(string $lang = "")
 ********************************************************************
 * Change this
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
  //putenv("LANG=" . $newLang);

  return $newLang;
}

/**
 * void initLanguage(string $lang)
 ********************************************************************
 * Change this
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
 * Change this
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
 * PO file will be in openclinic/locale/
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
 * Change this
 ********************************************************************
 * @param string $lang
 * @return bool returns true if gettext is defined, and the mo file is found for language, or no gettext, and a po file is found
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
?>
