<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: read_settings.php,v 1.7 2004/07/07 17:23:37 jact Exp $
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
    ? error_reporting(E_ALL) // debug mode
    : error_reporting(E_ALL & ~E_NOTICE); // normal mode

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
  // Making session user info available on all pages
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/session_info.php");

  ////////////////////////////////////////////////////////////////////
  // Reading general settings
  ////////////////////////////////////////////////////////////////////
  $setQ = new Setting_Query();
  $setQ->connect();
  if ($setQ->isError())
  {
    showQueryError($setQ);
  }

  $setQ->select();
  if ($setQ->isError())
  {
    $setQ->close();
    showQueryError($setQ);
  }

  $set = $setQ->fetch();
  if ($setQ->isError())
  {
    $setQ->close();
    showFetchError($setQ);
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
  // i18n l10n (after OPEN_LANGUAGE is defined)
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/i18n.php");

  ////////////////////////////////////////////////////////////////////
  // Reading theme settings
  ////////////////////////////////////////////////////////////////////
  $themeQ = new Theme_Query();
  $themeQ->connect();
  if ($themeQ->isError())
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
  if ($themeQ->isError())
  {
    $themeQ->close();
    showQueryError($themeQ);
  }

  $theme = $themeQ->fetch();
  if ($themeQ->isError())
  {
    $themeQ->close();
    showFetchError($themeQ);
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
