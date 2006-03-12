<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_validate_post.php,v 1.8 2006/03/12 18:37:15 jact Exp $
 */

/**
 * theme_validate_post.php
 *
 * Validate post data of a theme
 *
 * Author: jact <jachavar@gmail.com>
 * @since 0.6
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  $theme->setName($_POST["theme_name"]);
  $_POST["theme_name"] = $theme->getName();

  $theme->setCSSFile($_POST["css_file"]);
  $_POST["css_file"] = $theme->getCSSFile();

  $theme->setCSSRules($_POST["css_rules"]);
  $_POST["css_rules"] = $theme->getCSSRules();

  if ( !$theme->validateData() )
  {
    $pageErrors["theme_name"] = $theme->getNameError();
    $pageErrors["css_file"] = $theme->getCSSFileError();
    $pageErrors["css_rules"] = $theme->getCSSRulesError();

    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;

    header("Location: " . $errorLocation);
    exit();
  }
?>
