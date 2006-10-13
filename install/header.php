<?php
/**
 * header.php
 *
 * Contains the common header of the installation pages
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: header.php,v 1.10 2006/10/13 20:14:10 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  /**
   * i18n l10n
   */
  require_once("../config/i18n.php");

  require_once("../lib/HTML.php");

  /**
   * XHTML Start (XML prolog, DOCTYPE, title page and meta data)
   */
  $title = _("OpenClinic Install");
  require_once("../layout/xhtml_start.php");

  HTML::start('link', array('rel' => 'icon', 'type' => 'image/png', 'href' => '../img/miniopc.png'), true);
  HTML::start('link', array('rel' => 'shortcut icon', 'type' => 'image/png', 'href' => '../img/miniopc.png'), true);
  HTML::start('link', array('rel' => 'bookmark icon', 'type' => 'image/png', 'href' => '../img/miniopc.png'), true);

  HTML::start('link',
    array(
      'rel' => 'stylesheet',
      'type' => 'text/css',
      'href' => '../css/style.css',
      'title' => 'OpenClinic',
      'media' => 'all'
    ),
    true
  );

  HTML::end('head');
  HTML::start('body', array('id' => 'top'));

  HTML::start('div', array('id' => 'content'));
?>
