<?php
/**
 * environment.php
 *
 * Contains general, i18n, theme and system constants of the program
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: environment.php,v 1.2 2007/10/16 19:59:08 jact Exp $
 * @author    jact <jachavar@gmail.com>
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
  require_once("../config/global_constants.php");

  /**
   * Making session user info available on all pages
   */
  require_once("../config/session_info.php");

  /**
   * Intercommunication available on all pages
   */
  require_once("../lib/FlashMsg.php");

  /**
   * Reading settings from database
   */
  require_once("../model/Setting_Query.php");
  require_once("../model/Theme_Query.php");

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
  require_once("../config/i18n.php");

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
  define("OPEN_THEME_NAME",     $theme->getName());
  define("OPEN_THEME_CSS_FILE", $theme->getCSSFile());

  unset($theme);
?>
