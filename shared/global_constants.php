<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: global_constants.php,v 1.4 2004/06/16 19:15:33 jact Exp $
 */

/**
 * global_constants.php
 ********************************************************************
 * Contains the global constants of the project
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

/**
 ********************************************************************
 * Search types:
 ********************************************************************
 * OPEN_SEARCH_SURNAME1
 * OPEN_SEARCH_SURNAME2
 * OPEN_SEARCH_FIRSTNAME
 * OPEN_SEARCH_NIF
 * OPEN_SEARCH_NTS
 * OPEN_SEARCH_NSS
 * OPEN_SEARCH_BIRTHPLACE
 * OPEN_SEARCH_ADDRESS
 * OPEN_SEARCH_PHONE
 * OPEN_SEARCH_INSURANCE
 * OPEN_SEARCH_COLLEGIATE
 * OPEN_SEARCH_WORDING
 * OPEN_SEARCH_SUBJECTIVE
 * OPEN_SEARCH_OBJECTIVE
 * OPEN_SEARCH_APPRECIATION
 * OPEN_SEARCH_ACTIONPLAN
 * OPEN_SEARCH_PRESCRIPTION
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
 ********************************************************************
 * Logical operators:
 ********************************************************************
 * OPEN_AND
 * OPEN_OR
 * OPEN_NOT
 */
  define("OPEN_AND", "AND");
  define("OPEN_OR",  "OR");
  define("OPEN_NOT", "NOT");

/**
 ********************************************************************
 * Staff types:
 ********************************************************************
 * OPEN_ADMINISTRATIVE
 * OPEN_DOCTOR
 */
  define("OPEN_ADMINISTRATIVE", "Administrative");
  define("OPEN_DOCTOR",         "Doctor");

/**
 ********************************************************************
 * Profile constants:
 ********************************************************************
 * OPEN_PROFILE_ADMINISTRATOR
 * OPEN_PROFILE_ADMINISTRATIVE
 * OPEN_PROFILE_DOCTOR
 */
  define("OPEN_PROFILE_ADMINISTRATOR",  1);
  define("OPEN_PROFILE_ADMINISTRATIVE", 2);
  define("OPEN_PROFILE_DOCTOR",         3);

/**
 ********************************************************************
 * Others constants:
 ********************************************************************
 * EXEC_TIME_LIMIT - to dump proccesses
 * VISITED_ITEMS - number of items of visited patients list
 * ALLOWED_HTML_TAGS - specify tags which should not be stripped by strip_tags() function
 * FIELD_PREVIEW_LIMIT
 */
  define("EXEC_TIME_LIMIT", 300);
  define("VISITED_ITEMS", 3);
  define("ALLOWED_HTML_TAGS", "<a><b><blockquote><br><code><div><em><i><li><ol><p><pre><strike><strong><sub><sup><tt><u><ul><hr>");
  define("FIELD_PREVIEW_LIMIT", 30);
?>
