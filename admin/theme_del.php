<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_del.php,v 1.4 2004/07/07 17:21:52 jact Exp $
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
  // Show success page
  ////////////////////////////////////////////////////////////////////
  $title = _("Delete Theme");
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

  echo '<p>' . sprintf(_("Theme, %s, has been deleted."), $name) . "</p>\n";

  echo '<p><a href="' . $returnLocation . '">' . _("Return to themes list") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
