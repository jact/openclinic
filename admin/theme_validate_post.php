<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_validate_post.php,v 1.6 2004/10/18 17:24:03 jact Exp $
 */

/**
 * theme_validate_post.php
 ********************************************************************
 * Validate post data of a theme
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * @since 0.6
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['PATH_TRANSLATED']))
  {
    header("Location: ../index.php");
    exit();
  }

  $theme->setThemeName($_POST["theme_name"]);
  $_POST["theme_name"] = $theme->getThemeName();

  $theme->setCSSFile($_POST["css_file"]);
  $_POST["css_file"] = $theme->getCSSFile();

  $theme->setCSSRules($_POST["css_rules"]);
  $_POST["css_rules"] = $theme->getCSSRules();

  if ( !$theme->validateData() )
  {
    $pageErrors["theme_name"] = $theme->getThemeNameError();
    $pageErrors["css_file"] = $theme->getCSSFileError();
    $pageErrors["css_rules"] = $theme->getCSSRulesError();

    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;

    header("Location: " . $errorLocation);
    exit();
  }
?>
