<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: license.php,v 1.3 2005/02/17 19:26:17 jact Exp $
 */

/**
 * license.php
 ********************************************************************
 * License page of the home tab
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "home";
  $nav = "license";

  require_once("../shared/read_settings.php");

  $licenseFile = (is_file("../locale/" . OPEN_LANGUAGE . "/copying.txt"))
    ? "../locale/" . OPEN_LANGUAGE . "/copying.txt"
    : "../LICENSE";

  $lines = file($licenseFile);

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
  if ($lines === false)
  {
    echo <<<END
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
 
  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
END;
  }
  else
  {
    foreach ($lines as $line)
    {
      echo htmlspecialchars($line);
    }
  }
  echo "</pre>\n";

  require_once("../shared/footer.php");
?>