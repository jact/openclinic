<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: read_settings.php,v 1.4 2004/06/16 19:31:54 jact Exp $
 */

/**
 * read_settings.php
 ********************************************************************
 * Contains general, i18n, theme and system constants of the program
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Application constants
  ////////////////////////////////////////////////////////////////////
  define("OPEN_DEMO",  false);
  define("OPEN_DEBUG", false);

  (defined("OPEN_DEBUG") && OPEN_DEBUG)
    ? error_reporting(63) // E_ALL - debug
    : error_reporting(55); // E_ALL & ~E_NOTICE - normal

  require_once("../lib/debug_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Loading global constants
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/global_constants.php");

  ////////////////////////////////////////////////////////////////////
  // Reading settings from database
  ////////////////////////////////////////////////////////////////////
  require_once("../classes/Setting_Query.php");
  require_once("../classes/Theme_Query.php");
  require_once("../lib/error_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Making session user info available on all pages.
  ////////////////////////////////////////////////////////////////////
  session_name("OpenClinic");
  session_cache_limiter("nocache");
  session_start();

  ////////////////////////////////////////////////////////////////////
  // Reading general settings
  ////////////////////////////////////////////////////////////////////
  $setQ = new Setting_Query();
  $setQ->connect();
  if ($setQ->errorOccurred())
  {
    showQueryError($setQ);
  }

  $setQ->select();
  if ($setQ->errorOccurred())
  {
    $setQ->close();
    showQueryError($setQ);
  }

  $set = $setQ->fetch();
  if ( !$set )
  {
    $setQ->close();
    showQueryError($setQ);
  }

  $setQ->freeResult();
  $setQ->close();
  unset($setQ);

  ////////////////////////////////////////////////////////////////////
  // General settings constants
  ////////////////////////////////////////////////////////////////////
  define("OPEN_CLINIC_NAME",      $set->getClinicName());
  define("OPEN_CLINIC_HOURS",     $set->getClinicHours());
  define("OPEN_CLINIC_ADDRESS",   $set->getClinicAddress());
  define("OPEN_CLINIC_PHONE",     $set->getClinicPhone());
  define("OPEN_CLINIC_URL",       $set->getClinicUrl());
  define("OPEN_SESSION_TIMEOUT",  $set->getSessionTimeout());
  define("OPEN_ITEMS_PER_PAGE",   $set->getItemsPerPage());
  define("OPEN_VERSION",          $set->getVersion());
  define("OPEN_THEMEID",          $set->getIdTheme());
  define("OPEN_LANGUAGE",         $set->getLanguage());
  define("OPEN_CLINIC_USE_IMAGE", $set->isUseImageSet());
  define("OPEN_CLINIC_IMAGE_URL", $set->getClinicImageUrl());

  ////////////////////////////////////////////////////////////////////
  // i18n l10n
  ////////////////////////////////////////////////////////////////////
  require_once("../lib/lang_lib.php");
  require_once("../lib/nls.php");

  $nls = getNLS();
  setLanguage(OPEN_LANGUAGE);
  initLanguage(OPEN_LANGUAGE);

  define("OPEN_CHARSET", (isset($nls['charset'][OPEN_LANGUAGE]) ? $nls['charset'][OPEN_LANGUAGE] : $nls['default']['charset']));
  define("OPEN_DIRECTION", (isset($nls['direction'][OPEN_LANGUAGE]) ? $nls['charset'][OPEN_LANGUAGE] : $nls['default']['direction']));
  define("OPEN_ENCODING", (isset($nls['encoding'][OPEN_LANGUAGE]) ? $nls['encoding'][OPEN_LANGUAGE] : $nls['default']['encoding']));

  ////////////////////////////////////////////////////////////////////
  // Reading theme settings
  ////////////////////////////////////////////////////////////////////
  $themeQ = new Theme_Query();
  $themeQ->connect();
  if ($themeQ->errorOccurred())
  {
    showQueryError($themeQ);
  }

  if (isset($_SESSION["userTheme"]))
  {
    $themeQ->select($_SESSION["userTheme"]);
  }
  else
  {
    $themeQ->select($set->getIdTheme());
  }
  if ($themeQ->errorOccurred())
  {
    $themeQ->close();
    showQueryError($themeQ);
  }

  $theme = $themeQ->fetch();
  if ( !$theme )
  {
    $themeQ->close();
    showQueryError($themeQ);
  }

  $themeQ->freeResult();
  $themeQ->close();
  unset($themeQ);

  ////////////////////////////////////////////////////////////////////
  // Theme related constants
  ////////////////////////////////////////////////////////////////////
  define("STYLE_NAME",               $theme->getThemeName());

  define("STYLE_TITLE_BG_COLOR",     $theme->getTitleBgColor());
  define("STYLE_TITLE_FONT_FAMILY",  $theme->getTitleFontFamily());
  define("STYLE_TITLE_FONT_SIZE",    $theme->getTitleFontSize());
  define("STYLE_TITLE_FONT_BOLD",    $theme->isTitleFontBold());
  define("STYLE_TITLE_TEXT_ALIGN",   $theme->getTitleAlign());
  define("STYLE_TITLE_FONT_COLOR",   $theme->getTitleFontColor());

  define("STYLE_BODY_BG_COLOR",      $theme->getBodyBgColor());
  define("STYLE_BODY_FONT_FAMILY",   $theme->getBodyFontFamily());
  define("STYLE_BODY_FONT_SIZE",     $theme->getBodyFontSize());
  define("STYLE_BODY_FONT_COLOR",    $theme->getBodyFontColor());
  define("STYLE_BODY_LINK_COLOR",    $theme->getBodyLinkColor());

  define("STYLE_ERROR_COLOR",        $theme->getErrorColor());

  define("STYLE_NAVBAR_BG_COLOR",    $theme->getNavbarBgColor());
  define("STYLE_NAVBAR_FONT_FAMILY", $theme->getNavbarFontFamily());
  define("STYLE_NAVBAR_FONT_SIZE",   $theme->getNavbarFontSize());
  define("STYLE_NAVBAR_FONT_COLOR",  $theme->getNavbarFontColor());
  define("STYLE_NAVBAR_LINK_COLOR",  $theme->getNavbarLinkColor());

  define("STYLE_TAB_BG_COLOR",       $theme->getTabBgColor());
  define("STYLE_TAB_FONT_FAMILY",    $theme->getTabFontFamily());
  define("STYLE_TAB_FONT_SIZE",      $theme->getTabFontSize());
  define("STYLE_TAB_FONT_COLOR",     $theme->getTabFontColor());
  define("STYLE_TAB_LINK_COLOR",     $theme->getTabLinkColor());
  define("STYLE_TAB_FONT_BOLD",      $theme->isTabFontBold());

  define("STYLE_TABLE_BORDER_COLOR", $theme->getTableBorderColor());
  define("STYLE_TABLE_BORDER_WIDTH", $theme->getTableBorderWidth());
  define("STYLE_TABLE_CELL_PADDING", $theme->getTableCellPadding());

  unset($theme);
  unset($set); // $theme needs $set->getIdTheme()
?>
