<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: lang_lib.php,v 1.2 2004/04/18 14:25:40 jact Exp $
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
        setlocale(LC_ALL, "en");
        $newLang = "en";
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
      setlocale(LC_ALL, "en");
      $newLang = "en";
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
    $textDomain = $lang . "-openclinic";
    bindtextdomain($textDomain, realpath("../locale/"));
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

  $filename = "../locale/" . $lang . "-openclinic.po";
  if (file_exists($filename))
  {
    return $filename;
  }

  $filename = "../locale/" . $lang . "/openclinic.po";
  if (file_exists($filename))
  {
    return $filename;
  }

  $filename = "../locale/" . $lang . "/" . $lang . "-openclinic.po";
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
  if ($lang == 'en')
  {
    return true;
  }

  $check = (in_array("gettext", get_loaded_extensions()) && function_exists('gettext'));
  if ($check)
  {
    return (file_exists("../locale/" . $lang . "/LC_MESSAGES/" . $lang . "-openclinic.mo"));
  }
  else
  {
    return (poFilename($lang) ? true : false);
  }
}
?>
