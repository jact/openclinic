<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_del.php,v 1.5 2004/07/08 18:46:59 jact Exp $
 */

/**
 * theme_del.php
 ********************************************************************
 * Theme deletion process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "themes";
  //$restrictInDemo = true;
  $returnLocation = "../admin/theme_list.php";

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to theme list if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Theme_Query.php");
  require_once("../lib/error_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving post vars
  ////////////////////////////////////////////////////////////////////
  $idTheme = intval($_POST["id_theme"]);
  $name = $_POST["name"];

  ////////////////////////////////////////////////////////////////////
  // Delete theme
  ////////////////////////////////////////////////////////////////////
  $themeQ = new Theme_Query();
  $themeQ->connect();
  if ($themeQ->isError())
  {
    showQueryError($themeQ);
  }

  $themeQ->delete($idTheme);
  if ($themeQ->isError())
  {
    $themeQ->close();
    showQueryError($themeQ);
  }
  $themeQ->close();
  unset($themeQ);

  ////////////////////////////////////////////////////////////////////
  // Redirect to theme list to avoid reload problem
  ////////////////////////////////////////////////////////////////////
  $info = urlencode($name);
  header("Location: " . $returnLocation . "?deleted=Y&info=" . $info);
?>
