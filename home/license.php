<?php
/**
 * license.php
 *
 * License page of the home tab
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: license.php,v 1.6 2006/03/27 18:35:32 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "home";
  $nav = "license";

  require_once("../shared/read_settings.php");

  $licenseFile = (is_file("../locale/" . OPEN_LANGUAGE . "/copying.txt"))
    ? "../locale/" . OPEN_LANGUAGE . "/copying.txt"
    : "../LICENSE";

  $lines = file($licenseFile);

  /**
   * Show page
   */
  $title = _("License");
  require_once("../shared/header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Home") => "../home/index.php",
    $title => ""
  );
  HTML::breadCrumb($links);
  unset($links);

  echo '<pre>';
  if ($lines === false)
  {
    // End Of Text
    echo <<<EOT
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
EOT;
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
