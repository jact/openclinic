<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: license.php,v 1.1 2004/02/29 16:08:34 jact Exp $
 */

/**
 * license.php
 ********************************************************************
 * License page of the home tab
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 29/02/04 17:08
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "home";
  $nav = "license";

  require_once("../shared/read_settings.php");

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("License");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Home") => "../home/index.php",
    $title => ""
  );
  showNavLinks($links);
  unset($links);

  echo '<pre>';

  (is_file("../locale/" . OPEN_LANGUAGE . "/copying.txt"))
    ? include_once("../locale/" . OPEN_LANGUAGE . "/copying.txt")
    : include_once("../LICENSE");

  echo "</pre>\n";

  require_once("../shared/footer.php");
?>