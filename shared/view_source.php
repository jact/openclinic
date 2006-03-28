<?php
/**
 * view_source.php
 *
 * View source code of a file screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: view_source.php,v 1.9 2006/03/28 19:20:42 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/HTML.php");

  $_GET = Check::safeArray($_GET); // sanitizing parameters

  /**
   * XHTML Start (XML prolog, DOCTYPE, title page and meta data)
   */
  $title = sprintf(_("Source file: %s"), $_GET["file"]);
  require_once("../shared/xhtml_start.php");

  echo '<link rel="stylesheet" type="text/css" href="../css/style.css" />';
  echo "</head>\n";

  echo '<body id="viewSource"';
  if (count($_GET) == 0 || empty($_GET["file"]) || empty($_GET["tab"]))
  {
    echo ' onload="window.close()"';
  }
  echo ">\n";

  if (isset($_SESSION["hasAdminAuth"]))
  {
    $file = basename($_GET["file"]);

    if (is_file('../' . $_GET["tab"] . '/' . $file))
    {
      highlight_file('../' . $_GET["tab"] . '/' . $file);
    }
    elseif (is_file('../shared/' . $file))
    {
      highlight_file('../shared/' . $file);
    }
    else
    {
      HTML::message(_("No file found."), OPEN_MSG_ERROR);

      echo '<p>' . HTML::strLink(_("Close Window"), '#', null, array('onclick' => 'window.close(); return false;')) . "</p>\n";
    }
  }
  else
  {
    HTML::message(sprintf(_("You are not authorized to use %s tab."), _("Admin"))); // maybe change
  }

  echo "</body>\n</html>\n";
?>
