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
 * @version   CVS: $Id: header.php,v 1.9 2006/09/30 16:55:58 jact Exp $
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
  require_once("../shared/i18n.php");

  require_once("../lib/HTML.php");

  /**
   * XHTML Start (XML prolog, DOCTYPE, title page and meta data)
   */
  $title = _("OpenClinic Install");
  require_once("../shared/xhtml_start.php");

  HTML::start('link', array('rel' => 'icon', 'type' => 'image/png', 'href' => '../images/miniopc.png'), true);
  HTML::start('link', array('rel' => 'shortcut icon', 'type' => 'image/png', 'href' => '../images/miniopc.png'), true);
  HTML::start('link', array('rel' => 'bookmark icon', 'type' => 'image/png', 'href' => '../images/miniopc.png'), true);

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
