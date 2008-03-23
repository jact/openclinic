<?php
/**
 * license.php
 *
 * License page of the home tab
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: license.php,v 1.11 2008/03/23 11:59:18 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "home";
  $nav = "license";

  require_once("../config/environment.php");
  if (isset($_SESSION['auth']['token']))
  {
    /**
     * Checking permissions
     */
    include_once("../auth/login_check.php");
    loginCheck();
  }

  $licenseFile = (is_file("../locale/" . OPEN_LANGUAGE . "/copying.txt"))
    ? "../locale/" . OPEN_LANGUAGE . "/copying.txt"
    : "../LICENSE";

  $lines = file($licenseFile);

  /**
   * Show page
   */
  $title = _("License");
  require_once("../layout/header.php");

  /**
   * Breadcrumb
   */
  $links = array(
    _("Home") => "../home/index.php",
    $title => ""
  );
  echo HTML::breadcrumb($links);
  unset($links);

  if ($lines === false)
  {
    // End Of Text
    $license = <<<EOT
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
    $license = '';
    foreach ($lines as $line)
    {
      $license .= htmlspecialchars($line);
    }
  }
  echo HTML::tag('pre', $license);

  require_once("../layout/footer.php");
?>
