<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: read_settings.php,v 1.12 2004/09/23 18:54:20 jact Exp $
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
  // Start server page generation time
  ////////////////////////////////////////////////////////////////////
  $microTime = explode(" ", microtime());
  $startTime = $microTime[1] + $microTime[0];
  unset($microTime);

  ////////////////////////////////////////////////////////////////////
  // Application constants
  ////////////////////////////////////////////////////////////////////
  define("OPEN_DEMO",               false);
  define("OPEN_DEBUG",              false); // if false, no NOTICE messages
  define("OPEN_BUFFER",             false); // if true, use ob_start(), ob_end_flush() functions
  define("OPEN_MAX_LOGIN_ATTEMPTS", 3); // if zero, no limit login attempts

  require_once("../lib/debug_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Custom error handler constants
  ////////////////////////////////////////////////////////////////////
  define("OPEN_SCREEN_ERRORS", false); // Show errors to the screen?
  define("OPEN_LOG_ERRORS",    false); // Save errors to a file?
  define("OPEN_LOG_FILE",      "/tmp/error_log.txt"); // Allways use / separator (Win32 too)

  require_once("../lib/error_lib.php");
  set_error_handler("customErrorHandler");

  ////////////////////////////////////////////////////////////////////
  // Loading global constants
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/global_constants.php");

  ////////////////////////////////////////////////////////////////////
  // Reading settings from database
  ////////////////////////////////////////////////////////////////////
  require_once("../classes/Setting_Query.php");
  require_once("../classes/Theme_Query.php");

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
  define("OPEN_THEME_ID",         $set->getIdTheme());
  define("OPEN_LANGUAGE",         $set->getLanguage());
  define("OPEN_CLINIC_USE_IMAGE", $set->isUseImageSet());
  define("OPEN_CLINIC_IMAGE_URL", $set->getClinicImageUrl());

  unset($set);

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
    $themeQ->select(OPEN_THEME_ID);
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
  define("OPEN_THEME_NAME",     $theme->getThemeName());
  define("OPEN_THEME_CSS_FILE", $theme->getCSSFile());

  unset($theme);
?>
