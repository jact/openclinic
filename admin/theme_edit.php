<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_edit.php,v 1.4 2004/07/08 18:46:59 jact Exp $
 */

/**
 * theme_edit.php
 ********************************************************************
 * Theme edition process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "themes";
  //$restrictInDemo = true;
  $errorLocation = "../admin/theme_edit_form.php";
  $returnLocation = "../admin/theme_list.php";

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to form if none found.
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
  // Validate data
  ////////////////////////////////////////////////////////////////////
  $theme = new Theme();

  $theme->setIdTheme($_POST["id_theme"]);
  $_POST["id_theme"] = $theme->getIdTheme();

  require_once("../admin/theme_validate_post.php");

  ////////////////////////////////////////////////////////////////////
  // Update theme
  ////////////////////////////////////////////////////////////////////
  $themeQ = new Theme_Query();
  $themeQ->connect();
  if ($themeQ->isError())
  {
    showQueryError($themeQ);
  }

  $themeQ->update($theme);
  if ($themeQ->isError())
  {
    $themeQ->close();
    showQueryError($themeQ);
  }
  $themeQ->close();
  unset($themeQ);

  ////////////////////////////////////////////////////////////////////
  // Destroy form values and errors
  ////////////////////////////////////////////////////////////////////
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  ////////////////////////////////////////////////////////////////////
  // Redirect to theme list to avoid reload problem
  ////////////////////////////////////////////////////////////////////
  $info = urlencode($theme->getThemeName());
  unset($theme);
  header("Location: " . $returnLocation . "?updated=Y&info=" . $info);
?>
