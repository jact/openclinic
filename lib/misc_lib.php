<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: misc_lib.php,v 1.8 2005/02/19 10:50:24 jact Exp $
 */

/**
 * misc_lib.php
 ********************************************************************
 * Set of miscellanean functions
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * @since 0.7
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['PATH_TRANSLATED']))
  {
    header("Location: ../index.php");
    exit();
  }

/**
 * Functions:
 *  string fieldPreview(string $field)
 *  string translateBrowser(string $text)
 *  string unTranslateBrowser(string $text)
 */

/**
 * string fieldPreview(string $field)
 ********************************************************************
 * Returns a preview of a memo field
 ********************************************************************
 * @param string $field
 * @return string preview of field
 * @access public
 */
function fieldPreview($field)
{
  if ( !defined("OPEN_FIELD_PREVIEW_LIMIT") )
  {
    return $field; // global_constants.php is not included
  }

  $array = explode(" ", $field);
  $preview = array_shift($array); // first word at least
  foreach ($array as $value)
  {
    if (strlen($preview . $value) < OPEN_FIELD_PREVIEW_LIMIT)
    {
      $preview .= " " . $value;
    }
  }
  if (strlen($preview) < strlen($field))
  {
    $preview .= " ...";
  }

  return $preview;
}

/*
 * string translateBrowser(string $text)
 ********************************************************************
 * Returns a string ready to see in a web browser
 ********************************************************************
 * @param string $text
 * @return string
 * @access public
 * @since 0.8
 */
function translateBrowser($text)
{
  return ((strpos($_SERVER["HTTP_USER_AGENT"], "Gecko") === false) ? $text : utf8_encode($text));
}

/*
 * string unTranslateBrowser(string $text)
 ********************************************************************
 * Returns a string ready to insert it in a database
 ********************************************************************
 * @param string $text
 * @return string
 * @access public
 * @since 0.8
 */
function unTranslateBrowser($text)
{
  return ((strpos($_SERVER["HTTP_USER_AGENT"], "Gecko") === false) ? $text : utf8_decode($text));
}
?>
