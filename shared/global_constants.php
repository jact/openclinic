<?php
/**
 * global_constants.php
 *
 * Contains the global constants of the project
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: global_constants.php,v 1.15 2006/03/28 19:20:42 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  /**
   * Application constants
   */
  define("OPEN_DEMO",               false);
  define("OPEN_BUFFER",             false); // if true, use ob_start(), ob_end_flush() functions
  define("OPEN_XML_ACTIVED",        false); // if true and is possible, application/xhtml+xml, otherwise text/html
  define("OPEN_MAX_LOGIN_ATTEMPTS", 3); // if zero, no limit login attempts

  /**
   * Custom error handler constants
   */
  define("OPEN_DEBUG",         false); // if false, no NOTICE messages
  define("OPEN_SCREEN_ERRORS", false); // Show errors to the screen?
  define("OPEN_LOG_ERRORS",    false); // Save errors to a file?
  define("OPEN_LOG_FILE",      "/tmp/error_log.txt"); // Allways use / separator (Win32 too)

  require_once("../lib/Error.php");
  set_error_handler(array("Error", "customHandler")); // Error::customHandler

  /**
   * Search types
   */
  define("OPEN_SEARCH_SURNAME1",      1);
  define("OPEN_SEARCH_SURNAME2",      2);
  define("OPEN_SEARCH_FIRSTNAME",     3);
  define("OPEN_SEARCH_NIF",           4);
  define("OPEN_SEARCH_NTS",           5);
  define("OPEN_SEARCH_NSS",           6);
  define("OPEN_SEARCH_BIRTHPLACE",    7);
  define("OPEN_SEARCH_ADDRESS",       8);
  define("OPEN_SEARCH_PHONE",         9);
  define("OPEN_SEARCH_INSURANCE",    10);
  define("OPEN_SEARCH_COLLEGIATE",   11);
  define("OPEN_SEARCH_WORDING",      12);
  define("OPEN_SEARCH_SUBJECTIVE",   13);
  define("OPEN_SEARCH_OBJECTIVE",    14);
  define("OPEN_SEARCH_APPRECIATION", 15);
  define("OPEN_SEARCH_ACTIONPLAN",   16);
  define("OPEN_SEARCH_PRESCRIPTION", 17);

  /**
   * Logical operators
   */
  define("OPEN_AND", "AND");
  define("OPEN_OR",  "OR");
  define("OPEN_NOT", "NOT");

  /**
   * Staff types
   */
  define("OPEN_ADMINISTRATIVE", "Administrative");
  define("OPEN_DOCTOR",         "Doctor");

  /**
   * Profile constants
   */
  define("OPEN_PROFILE_ADMINISTRATOR",  1);
  define("OPEN_PROFILE_ADMINISTRATIVE", 2);
  define("OPEN_PROFILE_DOCTOR",         3);

  /**
   * Others constants:
   *
   * OPEN_EXEC_TIME_LIMIT - to dump proccesses
   * OPEN_VISITED_ITEMS - number of items of visited patients list
   * OPEN_ALLOWED_HTML_TAGS - tags which should not be stripped by strip_tags() function
   * OPEN_FIELD_PREVIEW_LIMIT - max lenght to preview text fields
   * OPEN_SEPARATOR - separation character in explode() implode() functions
   */
  define("OPEN_EXEC_TIME_LIMIT", 300);
  define("OPEN_VISITED_ITEMS", 3);
  define("OPEN_ALLOWED_HTML_TAGS", "<a><b><blockquote><br><code><div><em><i><li><ol><p><pre><strike><strong><sub><sup><tt><u><ul><hr>");
  define("OPEN_FIELD_PREVIEW_LIMIT", 30);
  define("OPEN_SEPARATOR", "|");
?>
