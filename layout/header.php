<?php
/**
 * header.php
 *
 * Contains the common header of the web pages
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: header.php,v 1.10 2007/12/01 13:00:13 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../lib/Msg.php"); // include HTML.php

  /**
   * XHTML Start (XML prolog, DOCTYPE, title page and meta data)
   */
  require_once("../layout/xhtml_start.php");

  HTML::start('link', array('rel' => 'home', 'title' => _("Clinic Home"), 'href' => '../home/index.php'), true);

  HTML::start('link', array('rel' => 'icon', 'type' => 'image/png', 'href' => '../img/miniopc.png'), true);
  HTML::start('link', array('rel' => 'shortcut icon', 'type' => 'image/png', 'href' => '../img/miniopc.png'), true);
  HTML::start('link', array('rel' => 'bookmark icon', 'type' => 'image/png', 'href' => '../img/miniopc.png'), true);

  if ( !(isset($_GET['css']) && ($_GET['css'] == 'off' || $_GET['css'] == 'print')) )
  {
    HTML::start('link',
      array(
        'rel' => 'stylesheet',
        'type' => 'text/css',
        'href' => '../css/' . OPEN_THEME_CSS_FILE,
        'title' => OPEN_THEME_NAME,
        'media' => 'screen'
      ),
      true
    );

    echo '<!--[if lt IE 7]>';
    HTML::start('link',
      array(
        'rel' => 'stylesheet',
        'type' => 'text/css',
        'href' => '../css/ie6_fix.css',
        'title' => 'IE 6 Fix'
      ),
      true
    );
    echo '<![endif]-->';
  }
  HTML::start('link',
    array(
      'rel' => 'stylesheet',
      'type' => 'text/css',
      'href' => '../css/print.css',
      'media' => (isset($_GET['css']) && $_GET['css'] == 'print') ? 'all' : 'print'
    ),
    true
  );

  echo HTML::insertScript('event.js');
  echo HTML::insertScript('pop_window.js');

  if (isset($isMd5) && $isMd5)
  {
    echo HTML::insertScript('md5.js');
    echo HTML::insertScript('password.php');
  }

  if (isset($focusFormField) && !empty($focusFormField))
  {
    HTML::start('script', array('src' => '../js/focus.php?field=' . $focusFormField, 'type' => 'text/javascript'));
    HTML::end('script');
  }

  HTML::end('head');
  HTML::start('body');

  HTML::start('div', array('id' => 'wrap'));
  HTML::start('div', array('id' => 'header'));

  $logo = '../img/' . 'openclinic-1.png'; // @fixme OPEN_APP_LOGO
  list($width, $height, $type, $attr) = getimagesize($logo);
  $logo = HTML::strImage($logo, 'OpenClinic' /* @fixme OPEN_APP_NAME */, array('width' => $width, 'height' => $height));
  $logo = HTML::strLink($logo, '../index.php', null, array('accesskey' => 1));
  HTML::para($logo, array('id' => 'logo'));
  unset($logo);

  HTML::para(HTML::strLink(_("Skip over navigation"), '#main', null, array('accesskey' => 2)),
    array('id' => 'skip_navigation')
  );

  require_once("../layout/component.php");

  echo shortcuts(isset($tab) ? $tab : null, isset($nav) ? $nav : null);

  if (isset($tab))
  {
    echo menuBar($tab);
  }

  HTML::end('div'); // #header

  HTML::rule();

  HTML::start('div', array('id' => 'main'));
  HTML::start('div', array('id' => 'content'));

  if (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    Msg::info(_("This is a demo version"));
  }

  /**
   * Display "public" message(s) from controller if available
   */
  echo FlashMsg::get();
?>
