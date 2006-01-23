<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: read_settings.php,v 1.18 2006/01/23 22:43:46 jact Exp $
 */

/**
 * read_settings.php
 *
 * Contains general, i18n, theme and system constants of the program
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  /**
   * Start server page generation time
   */
  $microTime = explode(" ", microtime());
  $startTime = $microTime[1] + $microTime[0];
  unset($microTime);

  /**
   * Loading global constants
   */
  require_once("../shared/global_constants.php");

  /**
   * Making session user info available on all pages
   */
  require_once("../shared/session_info.php");

  /**
   * Reading settings from database
   */
  require_once("../classes/Setting_Query.php");
  require_once("../classes/Theme_Query.php");

  /**
   * Reading general settings
   */
  $setQ = new Setting_Query();
  $setQ->connect();

  $setQ->select();

  $set = $setQ->fetch();
  if ( !$set )
  {
    $setQ->close();
    Error::fetch($setQ);
  }

  $setQ->freeResult();
  $setQ->close();
  unset($setQ);

  /**
   * General settings constants
   */
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

  /**
   * i18n l10n (after OPEN_LANGUAGE is defined)
   */
  require_once("../shared/i18n.php");

  /**
   * Reading theme settings
   */
  $themeQ = new Theme_Query();
  $themeQ->connect();

  $themeQ->select((isset($_SESSION["userTheme"]) ? $_SESSION["userTheme"] : OPEN_THEME_ID));

  $theme = $themeQ->fetch();
  if ( !$theme )
  {
    $themeQ->close();
    Error::fetch($themeQ);
  }

  $themeQ->freeResult();
  $themeQ->close();
  unset($themeQ);

  /**
   * Theme related constants
   */
  define("OPEN_THEME_NAME",     $theme->getThemeName());
  define("OPEN_THEME_CSS_FILE", $theme->getCSSFile());

  unset($theme);
?>
