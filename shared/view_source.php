<?php
/**
 * view_source.php
 *
 * View source code of a file screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: view_source.php,v 1.16 2008/03/23 12:00:28 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR);

  require_once("../lib/Msg.php"); // include HTML.php

  /**
   * Retrieving get var
   */
  $_file = Check::safeText($_GET['file']);

  /**
   * XHTML Start (XML prolog, DOCTYPE, title page and meta data)
   */
  $title = sprintf(_("Source file: %s"), $_file);
  require_once("../layout/xhtml_start.php");

  echo HTML::start('link',
    array(
      'rel' => 'stylesheet',
      'type' => 'text/css',
      'href' => '../css/style.css'
    ),
    true
  );
  echo HTML::end('head');

  $array['id'] = 'viewSource';
  if (count($_GET) == 0 || empty($_file))
  {
    $array['onload'] = 'window.close();';
  }
  echo HTML::start('body', $array);

  if (isset($_SESSION['auth']['is_admin']))
  {
    if (is_file($_file))
    {
      highlight_file($_file);
    }
    else
    {
      echo Msg::error(_("No file found."));

      echo HTML::para(HTML::link(_("Close Window"), '#', null, array('onclick' => 'window.close(); return false;')));
    }
  }
  else
  {
    echo Msg::warning(sprintf(_("You are not authorized to use %s tab."), _("Admin"))); // maybe change
  }

  echo HTML::end('body');
  echo HTML::end('html');
?>
