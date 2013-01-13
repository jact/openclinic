<?php
/**
 * I18n.php
 *
 * Contains the class I18n
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: I18n.php,v 1.14 2013/01/13 16:51:11 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

define("OPEN_LANG_DEFAULT",  "en");
define("OPEN_LANG_DIR",      "../locale/");
define("OPEN_LANG_FILENAME", "openclinic");

/**
 * I18n set of i18n and l10n functions
 *
 * Methods:
 *  string setLanguage(string $lang = "")
 *  void initLanguage(string $lang)
 *  void emulateGetText(void)
 *  mixed poFilename(string $lang = "")
 *  bool languageExists(string $lang)
 *  string localDate(string $date)
 *  mixed languageList(void)
 *  array getNLS(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class I18n
{
  /**
   * string setLanguage(string $lang = "")
   *
   * Sets a language to locale options
   *
   * @param string $lang (optional)
   * @return string new language setted
   * @access public
   * @static
   * @see OPEN_LANG_DEFAULT
   */
  public static function setLanguage($lang = "")
  {
    $newLang = OPEN_LANG_DEFAULT;
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
        if (self::languageExists($browserLanguage))
        {
          $newLang = $browserLanguage;
        }
      }
    }
    else
    {
      if (self::languageExists($lang))
      {
        $newLang = $lang;
      }
    }
    putenv("LANG=" . $newLang);
    //setlocale(LC_ALL, $newLang);

    $nls = I18n::getNLS();
    if (defined("PHP_OS") && preg_match("/win/i", PHP_OS))
    {
      setlocale(LC_ALL, (isset($nls['win32'][$newLang]) ? $nls['win32'][$newLang] : $newLang));
    }
    else
    {
      setlocale(LC_ALL, $newLang);
    }

    return $newLang;
  }

  /**
   * void initLanguage(string $lang)
   *
   * Initializes a language for gettext or "emulateGetText"
   *
   * @param string $lang
   * @return void
   * @access public
   * @static
   * @see OPEN_LANG_FILENAME, OPEN_LANG_DIR, OPEN_CHARSET
   */
  public static function initLanguage($lang)
  {
    /**
     * Test if we're using gettext. If yes, do some gettext settings.
     * If not emulate _() function
     */
    $check = (in_array("gettext", get_loaded_extensions()) && function_exists('gettext'));
    if ($check)
    {
      $textDomain = $lang . "-" . OPEN_LANG_FILENAME;
      bindtextdomain($textDomain, realpath(OPEN_LANG_DIR));
      textdomain($textDomain);
      bind_textdomain_codeset($textDomain, OPEN_CHARSET);
    }
    else
    {
      self::emulateGetText();
    }
  }

  /**
   * void emulateGetText(void)
   *
   * Emulates gettext's mecanism
   *
   * @global array $translation
   * @return void
   * @access public
   * @static
   */
  public static function emulateGetText()
  {
    global $translation;

    $filename = self::poFilename();
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
   *
   * PO file will be in OPEN_LANG_DIR
   *
   * @param string $lang (optional)
   * @return mixed false if .po file doesn't exist or string with filename if it exists
   * @access public
   * @static
   * @see OPEN_LANGUAGE, OPEN_LANG_DIR, OPEN_LANG_FILENAME
   */
  public static function poFilename($lang = "")
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
   *
   * Checks .po .mo files
   *
   * @param string $lang
   * @return bool returns true if gettext is defined, and the .mo file is found for language, or no gettext, and a .po file is found
   * @access public
   * @static
   * @see OPEN_LANG_DEFAULT, OPEN_LANG_DIR, OPEN_LANG_FILENAME
   */
  public static function languageExists($lang)
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
      return (self::poFilename($lang) ? true : false);
    }
  }

  /**
   * string localDate(string $date = "")
   *
   * Returns a date in a local format
   *
   * @param string $date (optional) ISO date (Ymd or Y-m-d or YmdHis or Y-m-d H:i:s)
   * @return string returns local formated date
   * @access public
   * @static
   * @since 0.7
   */
  public static function localDate($date = "")
  {
    $local = "";
    $winOS = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');

    $date = preg_replace("/[\D]*/", "", $date); // only numbers
    switch (strlen($date))
    {
      case 0:
        $local = date(_("Y-m-d H:i:s"));
        break;

      case 8: // Ymd
        if ($date != str_repeat("0", 8))
        {
          if (($winOS && $date < '19700101') || (!$winOS && $date < '19000101'))
          {
            $local = $date;
          }
          else
          {
            $local = date(_("Y-m-d"), mktime(0, 0, 0, substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4)));
          }
        }
        break;

      case 14: // YmdHis
        if ($date != str_repeat("0", 14))
        {
          if (($winOS && $date < '19700101000000') || (!$winOS && $date < '19000101000000'))
          {
            $local = $date;
          }
          else
          {
            $local = date(_("Y-m-d H:i:s"), mktime(substr($date, 8, 2), substr($date, 10, 2), substr($date, 12, 2), substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4)));
          }
        }
        break;

      default:
        $local = $date;
        break;
    }

    return $local;
  }

  /**
   * mixed languageList(void)
   *
   * Returns an array with available languages
   *
   * @return mixed array with available languages or null if empty
   * @access public
   * @static
   * @since 0.7
   * @see OPEN_LANG_DIR, OPEN_LANG_DIR, OPEN_CHARSET
   */
  public static function languageList()
  {
    $nls = self::getNLS();
    $array = null;
    $handle = opendir(OPEN_LANG_DIR);

    while (($file = readdir($handle)) !== false)
    {
      if ($file != 'CVS' && $file != '.' && $file != '..' && is_dir(OPEN_LANG_DIR . $file))
      {
        if (function_exists('html_entity_decode'))
        {
          $array["$file"] = html_entity_decode($nls['language'][$file], ENT_COMPAT, OPEN_CHARSET);
        }
        else
        {
          $array["$file"] = strtr($nls['language'][$file], array_flip(get_html_translation_table(HTML_ENTITIES)));
        }
      }
    }
    closedir($handle);

    return $array;
  }

  /**
   * array getNLS(void)
   *
   * Returns an associative array with NLS application settings
   * If you add a new language please use alphabetical order by name.
   *
   * The basic idea and values was taken from then Horde Framework (http://horde.org)
   * The original filename was horde/config/nls.php.dist and it was
   * maintained by Jan Schneider (mail@janschneider.de)
   *
   * @return array (associative)
   * @access public
   * @static
   * @since 0.6
   */
  public static function getNLS()
  {
    $nls['language']['bg_BG'] = '&#x0411;&#x044a;&#x043b;&#x0433;&#x0430;&#x0440;&#x0441;&#x043a;&#x0438;';
    $nls['language']['zh_CN'] = 'Simplified Chinese (&#31616;&#20307;&#20013;&#25991;)';
    $nls['language']['zh_TW'] = 'Traditional Chinese (&#32321;&#39636;&#20013;&#25991;)';
    $nls['language']['zh_TW.utf8'] = 'Traditional Chinese (&#32321;&#39636;&#20013;&#25991;) (UTF-8)';
    $nls['language']['cs_CZ'] = '&#x010c;esky';
    $nls['language']['da_DK'] = 'Dansk';
    $nls['language']['de_DE'] = 'Deutsch';
    $nls['language']['en'] = 'English';
    $nls['language']['en_GB'] = 'English (UK)';
    $nls['language']['en_US'] = 'English (US)';
    $nls['language']['es_ES'] = 'Espa&#241;ol';
    $nls['language']['fr_FR'] = 'Fran&#231;ais';
    $nls['language']['it_IT'] = 'Italiano';
    $nls['language']['he_IL'] = 'Hebrew';
    $nls['language']['is_IS'] = '&#205;slenska';
    $nls['language']['ja_JP'] = '&#x65e5;&#x672c;&#x8a9e; (EUC-JP)';
    $nls['language']['lt_LT'] = 'Lietuvi&#x0173;';
    $nls['language']['nl_NL'] = 'Nederlands';
    $nls['language']['nl_BE'] = 'Nederlands (Belgium)';
    $nls['language']['no_NO'] = 'Norsk bokm&#229;l';
    $nls['language']['pl_PL'] = 'Polski';
    $nls['language']['pt_PT'] = 'Portugu&#234;s';
    $nls['language']['pt_BR'] = 'Portugu&#234;s Brasileiro';
    $nls['language']['ru_RU'] = '&#x0420;&#x0443;&#x0441;&#x0441;&#x043a;&#x0438;&#x0439; (Windows)';
    $nls['language']['ru_RU.koi8r'] = '&#x0420;&#x0443;&#x0441;&#x0441;&#x043a;&#x0438;&#x0439; (KOI8-R)';
    $nls['language']['sl_SI'] = 'Sloven&#x0161;&#x010d;ina';
    $nls['language']['fi_FI'] = 'Suomi';
    $nls['language']['sv_SE'] = 'Svenska';
    $nls['language']['tr_TR'] = 'T&#252;rk&#231;e';
    $nls['language']['uk_UA'] = '&#x0423;&#x043a;&#x0440;&#x0430;&#x0457;&#x043d;&#x0441;&#x044c;&#x043a;&#x0430;';

    /**
     * Aliases for languages with different browser and gettext codes
     */
    $nls['alias']['bg'] = 'bg_BG';
    $nls['alias']['bg_BG.CP1251'] = 'bg_BG';
    $nls['alias']['cs'] = 'cs_CZ';
    $nls['alias']['da'] = 'da_DK';
    $nls['alias']['de'] = 'de_DE';
    $nls['alias']['en'] = 'en_US';
    $nls['alias']['es'] = 'es_ES';
    $nls['alias']['fi'] = 'fi_FI';
    $nls['alias']['fr'] = 'fr_FR';
    $nls['alias']['is'] = 'is_IS';
    $nls['alias']['it'] = 'it_IT';
    $nls['alias']['ja'] = 'ja_JP';
    $nls['alias']['lt'] = 'lt_LT';
    $nls['alias']['nl'] = 'nl_NL';
    $nls['alias']['no'] = 'no_NO';
    $nls['alias']['nb'] = 'no_NO';
    $nls['alias']['pl'] = 'pl_PL';
    $nls['alias']['pt'] = 'pt_PT';
    $nls['alias']['ru'] = 'ru_RU';
    $nls['alias']['sl'] = 'sl_SI';
    $nls['alias']['sv'] = 'sv_SE';
    $nls['alias']['tr'] = 'tr_TR';
    $nls['alias']['uk'] = 'uk_UA';

    /**
     * Aliases for languages in win32 systems (ISO 3166-Alpha-3)
     */
    $nls['win32']['bg_BG'] = 'bgr';
    $nls['win32']['en']    = 'eng';
    $nls['win32']['es_ES'] = 'esp';
    $nls['win32']['nl_BE'] = 'nld';
    $nls['win32']['zh_TW'] = 'chn';

    /**
     * Charsets
     *
     * Add your own charsets, if your system uses others than "normal"
     */
    $nls['default']['charset'] = 'ISO-8859-1';
    //$nls['default']['charset'] = 'UTF-8';

    $nls['charset']['es_ES'] = 'ISO-8859-1';
    $nls['charset']['bg_BG'] = 'windows-1251';
    $nls['charset']['cs_CZ'] = 'ISO-8859-2';
    $nls['charset']['he_IL'] = 'windows-1255';
    $nls['charset']['ja_JP'] = 'EUC-JP';
    $nls['charset']['lt_LT'] = 'windows-1257';
    $nls['charset']['pl_PL'] = 'ISO-8859-2';
    $nls['charset']['ru_RU'] = 'windows-1251';
    $nls['charset']['ru_RU.KOI8-R'] = 'KOI8-R';
    $nls['charset']['sl_SI'] = 'ISO-8859-2';
    $nls['charset']['tr_TR'] = 'ISO-8859-9';
    $nls['charset']['uk_UA'] = 'KOI8-U';
    $nls['charset']['zh_CN'] = 'GB2312';
    $nls['charset']['zh_TW'] = 'BIG5';
    $nls['charset']['zh_TW.utf8'] = 'UTF-8';

    $nls['charset']['de_DE'] = 'de_DE.ISO-8859-15@euro';
    $nls['charset']['lt_LT'] = 'ISO-8859-13';

    /**
     * Multibyte charsets
     */
    $nls['multibyte']['BIG5'] = true;
    $nls['multibyte']['EUC-JP'] = true;
    $nls['multibyte']['GB2312'] = true;
    $nls['multibyte']['UTF-8'] = true;

    /**
     * Encoding
     */
    //$nls['default']['encoding'] = 'UTF-8';
    $nls['default']['encoding'] = 'ISO-8859-1';
    $nls['encoding']['es_ES'] = 'ISO-8859-1';
    //$nls['encoding']['bg_BG'] = 'UTF-8';

    /**
     * Direction
     */
    $nls['default']['direction'] = 'ltr';
    //$nls['direction']['he_IL'] = 'rtl';

    /**
     * Alignment
     */
    $nls['default']['alignment'] = 'left';
    //$nls['alignment']['he_IL'] = 'right';

    /**
     * Flags "alias"
     */
    $nls['flag']['ru_RU.koi8r'] = 'ru_RU';
    $nls['flag']['zh_TW.utf8']  =  'zh_TW';

    return $nls;
  }
} // end class
?>
