<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: nls.php,v 1.3 2004/05/16 18:12:29 jact Exp $
 */

/**
 * nls.php
 ********************************************************************
 * NLS (National Language System) array
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
 *  array getNLS(void)
 */

/**
 * The basic idea and values was taken from then Horde Framework (http://horde.org)
 * The original filename was horde/config/nls.php.dist and it was
 * maintained by Jan Schneider (mail@janschneider.de)
 */

/**
 * IMPORTANT
 * +++++++++
 *
 * If you add a new language please use alphabetical order by name.
 */

/**
 * array getNLS(void)
 ********************************************************************
 * Returns an associative array with NLS application settings
 ********************************************************************
 * @return array (associative)
 * @access public
 */
function getNLS()
{
  $nls['language']['bg_BG'] = '&#x0411;&#x044a;&#x043b;&#x0433;&#x0430;&#x0440;&#x0441;&#x043a;&#x0438;';
  //$nls['language']['zh_CN'] = 'Simplified Chinese (&#31616;&#20307;&#20013;&#25991;)';
  //$nls['language']['zh_TW'] = 'Traditional Chinese (&#32321;&#39636;&#20013;&#25991;)';
  //$nls['language']['zh_TW.utf8'] = 'Traditional Chinese (&#32321;&#39636;&#20013;&#25991;) (UTF-8)';
  //$nls['language']['cs_CZ'] = '&#x010c;esky';
  //$nls['language']['da_DK'] = 'Dansk';
  //$nls['language']['de_DE'] = 'Deutsch';
  $nls['language']['en'] = 'English';
  $nls['language']['en_GB'] = 'English (UK)';
  $nls['language']['en_US'] = 'English (US)';
  $nls['language']['es_ES'] = 'Espa&#241;ol';
  //$nls['language']['fr_FR'] = 'Fran&#231;ais';
  //$nls['language']['it_IT'] = 'Italiano';
  //$nls['language']['he_IL'] = 'Hebrew';
  //$nls['language']['is_IS'] = '&#205;slenska';
  //$nls['language']['ja_JP'] = '&#x65e5;&#x672c;&#x8a9e; (EUC-JP)';
  //$nls['language']['lt_LT'] = 'Lietuvi&#x0173;';
  //$nls['language']['nl_NL'] = 'Nederlands';
  //$nls['language']['no_NO'] = 'Norsk bokm&#229;l';
  //$nls['language']['pl_PL'] = 'Polski';
  //$nls['language']['pt_PT'] = 'Portugu&#234;s';
  //$nls['language']['ru_RU'] = '&#x0420;&#x0443;&#x0441;&#x0441;&#x043a;&#x0438;&#x0439; (Windows)';
  //$nls['language']['ru_RU.koi8r'] = '&#x0420;&#x0443;&#x0441;&#x0441;&#x043a;&#x0438;&#x0439; (KOI8-R)';
  //$nls['language']['sl_SI'] = 'Sloven&#x0161;&#x010d;ina';
  //$nls['language']['fi_FI'] = 'Suomi';
  //$nls['language']['sv_SE'] = 'Svenska';
  //$nls['language']['tr_TR'] = 'T&#252;rk&#231;e';
  //$nls['language']['uk_UA'] = '&#x0423;&#x043a;&#x0440;&#x0430;&#x0457;&#x043d;&#x0441;&#x044c;&#x043a;&#x0430;';

  ////////////////////////////////////////////////////////////////////
  // Aliases for languages with different browser and gettext codes
  ////////////////////////////////////////////////////////////////////
  $nls['alias']['bg'] = 'bg_BG';
  $nls['alias']['bg_BG.CP1251'] = 'bg_BG';
  //$nls['alias']['cs'] = 'cs_CZ';
  //$nls['alias']['da'] = 'da_DK';
  //$nls['alias']['de'] = 'de_DE';
  $nls['alias']['en'] = 'en_US';
  $nls['alias']['es'] = 'es_ES';
  //$nls['alias']['fi'] = 'fi_FI';
  //$nls['alias']['fr'] = 'fr_FR';
  //$nls['alias']['is'] = 'is_IS';
  //$nls['alias']['it'] = 'it_IT';
  //$nls['alias']['ja'] = 'ja_JP';
  //$nls['alias']['lt'] = 'lt_LT';
  //$nls['alias']['nl'] = 'nl_NL';
  //$nls['alias']['no'] = 'no_NO';
  //$nls['alias']['nb'] = 'no_NO';
  //$nls['alias']['pl'] = 'pl_PL';
  //$nls['alias']['pt'] = 'pt_PT';
  //$nls['alias']['ru'] = 'ru_RU';
  //$nls['alias']['sl'] = 'sl_SI';
  //$nls['alias']['sv'] = 'sv_SE';
  //$nls['alias']['tr'] = 'tr_TR';
  //$nls['alias']['uk'] = 'uk_UA';

  ////////////////////////////////////////////////////////////////////
  // Aliases for languages in win32 systems (ISO 3166-Alpha-3)
  ////////////////////////////////////////////////////////////////////
  //$nls['win32']['es_ES'] = 'esp';

  ////////////////////////////////////////////////////////////////////
  // Charsets
  //
  // Add your own charsets, if your system uses others than "normal"
  ////////////////////////////////////////////////////////////////////
  $nls['default']['charset'] =    'ISO-8859-1';

  $nls['charset']['bg_BG']   =    'windows-1251';
  //$nls['charset']['cs_CZ'] =    'ISO-8859-2';
  //$nls['charset']['he_IL'] =    'windows-1255';
  //$nls['charset']['ja_JP'] =    'EUC-JP';
  //$nls['charset']['lt_LT'] =    'windows-1257';
  //$nls['charset']['pl_PL'] =    'ISO-8859-2';
  //$nls['charset']['ru_RU'] =    'windows-1251';
  //$nls['charset']['ru_RU.KOI8-R'] = 'KOI8-R';
  //$nls['charset']['sl_SI'] =    'ISO-8859-2';
  //$nls['charset']['tr_TR'] =    'ISO-8859-9';
  //$nls['charset']['uk_UA'] =    'KOI8-U';
  //$nls['charset']['zh_CN'] =    'GB2312';
  //$nls['charset']['zh_TW'] =    'BIG5';
  //$nls['charset']['zh_TW.utf8'] = 'UTF-8';

  //$nls['charset']['de_DE'] =    'de_DE.ISO-8859-15@euro';
  //$nls['charset']['lt_LT'] =    'ISO-8859-13';

  ////////////////////////////////////////////////////////////////////
  // Multibyte charsets
  ////////////////////////////////////////////////////////////////////
  $nls['multibyte']['BIG5'] =   true;
  $nls['multibyte']['EUC-JP'] = true;
  $nls['multibyte']['GB2312'] = true;
  $nls['multibyte']['UTF-8'] =  true;

  ////////////////////////////////////////////////////////////////////
  // Direction
  ////////////////////////////////////////////////////////////////////
  $nls['default']['direction'] = 'ltr';
  //$nls['direction']['he_IL'] = 'rtl';

  ////////////////////////////////////////////////////////////////////
  // Alignment
  ////////////////////////////////////////////////////////////////////
  $nls['default']['alignment'] = 'left';
  //$nls['alignment']['he_IL'] = 'right';

  ////////////////////////////////////////////////////////////////////
  // Flags "alias"
  ////////////////////////////////////////////////////////////////////
  //$nls['flag']['ru_RU.koi8r'] = 'ru_RU';
  //$nls['flag']['zh_TW.utf8'] =  'zh_TW';

  return $nls;
}
?>
