<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_new.php,v 1.9 2006/03/12 18:37:15 jact Exp $
 */

/**
 * theme_new.php
 *
 * Theme addition process
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  //$restrictInDemo = true;
  $errorLocation = "../admin/theme_new_form.php";
  $returnLocation = "../admin/theme_list.php";

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: " . $errorLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Theme_Query.php");

  /**
   * Validate data
   */
  $theme = new Theme();

  require_once("../admin/theme_validate_post.php");

  /**
   * Insert new theme
   */
  $themeQ = new Theme_Query();
  $themeQ->connect();

  if ($themeQ->existCSSFile($theme->getCSSFile()))
  {
    $fileUsed = true;
  }
  else
  {
    $themeQ->insert($theme);
  }
  $themeQ->close();
  unset($themeQ);

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  $info = urlencode($theme->getName());
  $returnLocation .= ((isset($fileUsed) && $fileUsed) ? "?file" : "?added") . "=Y&info=" . $info;
  unset($theme);
  header("Location: " . $returnLocation);
?>
