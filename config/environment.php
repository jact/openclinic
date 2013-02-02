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
 * @version   CVS: $Id: environment.php,v 1.8 2013/02/02 18:26:13 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

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
  require_once("../model/Query/Setting.php");
  require_once("../model/Query/Theme.php");

  /**
   * Reading general settings
   */
  $setQ = new Query_Setting();
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

  unset($set);

  if (OPEN_VERSION != OPEN_DB_SCHEMA_VERSION)
  {
    header("Location: ../install/upgrade.php"); // try upgrade database
  }

  /**
   * i18n l10n (after OPEN_LANGUAGE is defined)
   */
  require_once("../config/i18n.php");

  /**
   * Reading theme settings
   */
  $themeQ = new Query_Theme();
  $themeQ->select((isset($_SESSION['auth']['user_theme']) ? $_SESSION['auth']['user_theme'] : OPEN_THEME_ID));

  $theme = $themeQ->fetch();
  if ( !$theme )
  {
    $themeQ->close();
    Error::fetch($themeQ);
  }

  /**
   * Theme related constants
   */
  define("OPEN_THEME_NAME",     $theme->getName());
  define("OPEN_THEME_CSS_FILE", $theme->getCssFile());

  $themeQ->freeResult();
  $themeQ->close();
  unset($themeQ);
  unset($theme);

  /**
   * Getting form errors and previous form variables from session
   */
  require_once("../lib/Form.php");

  $formSession = Form::getSession();
  $formVar = (isset($formSession['var'])) ? $formSession['var'] : null;
  $formError = (isset($formSession['error'])) ? $formSession['error'] : null;
  unset($formSession);
?>
