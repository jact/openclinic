<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_del.php,v 1.11 2005/08/03 17:39:28 jact Exp $
 */

/**
 * theme_del.php
 *
 * Theme deletion process
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "themes";
  //$restrictInDemo = true; // To prevent users' malice
  $returnLocation = "../admin/theme_list.php";

  /**
   * Checking for post vars. Go back to theme list if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Theme_Query.php");

  /**
   * Retrieving post vars
   */
  $idTheme = intval($_POST["id_theme"]);
  $name = Check::safeText($_POST["name"]);
  $file = Check::safeText($_POST["file"]);

  /**
   * Delete theme
   */
  $themeQ = new Theme_Query();
  $themeQ->connect();
  if ($themeQ->isError())
  {
    Error::query($themeQ);
  }

  $themeQ->delete($idTheme);
  if ($themeQ->isError())
  {
    $themeQ->close();
    Error::query($themeQ);
  }
  $themeQ->close();
  unset($themeQ);

  if ( !in_array($file, $reservedCSSFiles) )
  {
    @unlink(dirname($_SERVER['SCRIPT_FILENAME']) . '/../css/' . basename($file));
  }

  /**
   * Redirect to theme list to avoid reload problem
   */
  $info = urlencode($name);
  header("Location: " . $returnLocation . "?deleted=Y&info=" . $info);
?>
