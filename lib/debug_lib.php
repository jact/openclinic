<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: debug_lib.php,v 1.8 2005/02/17 20:24:15 jact Exp $
 */

/**
 * debug_lib.php
 ********************************************************************
 * Set of debug functions
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
 *  void debug(mixed $expression, string $message = "", bool $goOut = false)
 */

/**
 * void debug(mixed $expression, string $message = "", bool $goOut = false)
 ********************************************************************
 * Displays the content of $expression
 ********************************************************************
 * @param mixed $expression
 * @param string $message (optional)
 * @param bool $goOut (optional) if true, execute an exit()
 * @return void
 * @access public
 */
function debug($expression, $message = "", $goOut = false)
{
  /*if ( defined("OPEN_DEBUG") && !OPEN_DEBUG )
  {
    return;
  }*/

  echo "\n<!-- debug -->\n";
  echo "<pre>\n";
  if ( !empty($message) )
  {
    echo $message . "\n";
  }
  $output = var_export($expression, true);
  echo htmlspecialchars($output);
  echo "</pre>\n";
  echo "<!-- end debug -->\n";

  if ($goOut)
  {
    exit();
  }
}
?>
