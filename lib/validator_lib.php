<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: validator_lib.php,v 1.1 2004/01/29 15:06:08 jact Exp $
 */

/**
 * validator_lib.php
 ********************************************************************
 * Functions to validate data (actually is not used in the project)
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 29/01/04 16:06
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

/**
 * Functions:
 *  bool hasMetas(string $text)
 *  mixed stripMetas(string $text)
 *  mixed customStrip(array $chars, string $text)
 */

/*
 * bool hasMetas(string $text)
 ********************************************************************
 * Checks if a string has meta characters in it . \\ + * ? [ ^ ] ( $ )
 ********************************************************************
 * @param string $text
 * @return bool true if the submitted text has meta characters in it
 * @access public
 */
function hasMetas($text)
{
  if (empty($text))
  {
    return false;
  }

  $new = quotemeta($text);

  return ($new != $text);
}

/*
 * mixed stripMetas(string $text)
 ********************************************************************
 * Strips " . \\ + * ? [ ^ ] ( $ ) " from submitted string
 * Metas are a virtual MINE FIELD for regular expressions
 ********************************************************************
 * @param string $text
 * @return mixed false if submitted string is empty, string otherwise
 * @access public
 * @see customStrip() for how they are removed
 */
function stripMetas($text)
{
  if (empty($text))
  {
    return false;
  }

  $metas = array('.', '+', '*', '?', '[', '^', ']', '(', '$', ')', '\\');
  $new = customStrip($metas, $text);

  return $new;
}

/*
 * mixed customStrip(array $chars, string $text)
 ********************************************************************
 * $chars must be an array of characters to remove
 * This method is meta-character safe
 ********************************************************************
 * @param array (string) $chars
 * @param string $text
 * @return mixed false if submitted string is empty, string otherwise
 * @access public
 */
function customStrip($chars, $text)
{
  if (empty($text))
  {
    return false;
  }

  if (gettype($chars) != "array")
  {
    $this->_error = "customStrip: [$chars] is not an array";
    return false;
  }

  while (list($key, $val) = each($chars))
  {
    if ( !empty($val) )
    {
      // str_replace is meta-safe, ereg_replace is not
      $text = str_replace($val, "", $text);
    }
  }

  return $text;
}
?>
