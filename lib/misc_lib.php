<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: misc_lib.php,v 1.3 2004/05/24 21:15:29 jact Exp $
 */

/**
 * misc_lib.php
 ********************************************************************
 * Set of miscelanean functions
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
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
  $preview = "";
  if ( !defined("FIELD_PREVIEW_LIMIT") )
  {
    return $preview; // global_constants.php is not included
  }

  $array = explode(" ", $field);
  $preview = array_shift($array); // first word at least
  foreach ($array as $value)
  {
    if (strlen($preview . $value) < FIELD_PREVIEW_LIMIT)
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
