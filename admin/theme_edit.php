<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_edit.php,v 1.1 2004/03/24 19:55:02 jact Exp $
 */

/**
 * theme_edit.php
 ********************************************************************
 * Theme edition process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 24/03/04 20:55
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
  if ($themeQ->errorOccurred())
  {
    showQueryError($themeQ);
  }

  if ( !$themeQ->update($theme) )
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
  // Show success page
  ////////////////////////////////////////////////////////////////////
  $title = _("Edit Theme");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Themes") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "themes.png");
  unset($links);

  echo '<p>' . sprintf(_("Theme, %s, has been updated."), $theme->getThemeName()) . "</p>\n";
  unset($theme);

  echo '<p><a href="' . $returnLocation . '">' . _("Return to themes list") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
