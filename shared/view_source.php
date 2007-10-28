<?php
/**
 * view_source.php
 *
 * View source code of a file screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: view_source.php,v 1.13 2007/10/28 12:07:09 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/HTML.php");

  $_GET = Check::safeArray($_GET); // sanitizing parameters

  /**
   * XHTML Start (XML prolog, DOCTYPE, title page and meta data)
   */
  $title = sprintf(_("Source file: %s"), $_GET["file"]);
  require_once("../layout/xhtml_start.php");

  HTML::start('link',
    array(
      'rel' => 'stylesheet',
      'type' => 'text/css',
      'href' => '../css/style.css'
    ),
    true
  );
  HTML::end('head');

  $array = array('id' => 'viewSource');
  if (count($_GET) == 0 || empty($_GET["file"]) || empty($_GET["tab"]))
  {
    $array['onload'] = 'window.close();';
  }
  HTML::start('body', $array);

  if (isset($_SESSION['auth']['is_admin']))
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
      Msg::error(_("No file found."));

      HTML::para(HTML::strLink(_("Close Window"), '#', null, array('onclick' => 'window.close(); return false;')));
    }
  }
  else
  {
    Msg::warning(sprintf(_("You are not authorized to use %s tab."), _("Admin"))); // maybe change
  }

  HTML::end('body');
  HTML::end('html');
?>
