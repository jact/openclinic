<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: misc_lib.php,v 1.6 2004/10/17 14:56:40 jact Exp $
 */

/**
 * misc_lib.php
 ********************************************************************
 * Set of miscellanean functions
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * @since 0.7
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['PATH_TRANSLATED'])
  {
    header("Location: ../index.php");
    exit();
  }

/**
 * Functions:
 *  string fieldPreview(string $field)
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
?>
