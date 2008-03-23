<?php
/**
 * header.php
 *
 * Contains the common header of the web pages
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: header.php,v 1.12 2008/03/23 11:59:38 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../lib/Msg.php"); // include HTML.php

  /**
   * XHTML Start (XML prolog, DOCTYPE, title page and meta data)
   */
  require_once("../layout/xhtml_start.php");

  echo HTML::start('link', array('rel' => 'home', 'title' => _("Clinic Home"), 'href' => '../home/index.php'), true);

  echo HTML::start('link', array('rel' => 'icon', 'type' => 'image/png', 'href' => '../img/miniopc.png'), true);
  echo HTML::start('link', array('rel' => 'shortcut icon', 'type' => 'image/png', 'href' => '../img/miniopc.png'), true);
  echo HTML::start('link', array('rel' => 'bookmark icon', 'type' => 'image/png', 'href' => '../img/miniopc.png'), true);

  if ( !(isset($_GET['css']) && ($_GET['css'] == 'off' || $_GET['css'] == 'print')) )
  {
    echo HTML::start('link',
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
    echo HTML::start('link',
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
  echo HTML::start('link',
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
    echo HTML::start('script', array('src' => '../js/focus.php?field=' . $focusFormField, 'type' => 'text/javascript'));
    echo HTML::end('script');
  }

  echo HTML::end('head');
  echo HTML::start('body');

  require_once("../layout/component.php");

  echo HTML::start('div', array('id' => 'wrap'));
  echo HTML::start('div', array('id' => 'header'));

  echo appLogo();

  echo HTML::para(HTML::link(_("Skip over navigation"), '#main', null, array('accesskey' => 2)),
    array('id' => 'skip_navigation')
  );

  echo shortcuts(isset($tab) ? $tab : null, isset($nav) ? $nav : null);

  if (isset($tab))
  {
    echo menuBar($tab);
  }

  echo HTML::end('div'); // #header

  echo HTML::rule();

  echo HTML::start('div', array('id' => 'main'));
  echo HTML::start('div', array('id' => 'content'));

  if (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    echo Msg::info(_("This is a demo version"));
  }

  /**
   * Display "public" message(s) from controller if available
   */
  echo FlashMsg::get();
?>
