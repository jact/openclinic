<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_use.php,v 1.3 2004/07/07 17:21:53 jact Exp $
 */

/**
 * theme_use.php
 ********************************************************************
 * Theme by default updating process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "themes";
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
  require_once("../classes/Setting_Query.php");
  require_once("../lib/error_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Update theme in use
  ////////////////////////////////////////////////////////////////////
  $idTheme = $_POST["id_theme"];

  $setQ = new Setting_Query();
  $setQ->connect();
  if ($setQ->isError())
  {
    showQueryError($setQ);
  }

  $setQ->updateTheme($idTheme);
  if ($setQ->isError())
  {
    $setQ->close();
    showQueryError($setQ);
  }
  $setQ->close();
  unset($setQ);

  ////////////////////////////////////////////////////////////////////
  // Destroy form values and errors
  ////////////////////////////////////////////////////////////////////
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  header("Location: " . $returnLocation);
?>
