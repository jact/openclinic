<?php
/**
 * index.php
 *
 * Home page of documentation project
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: index.php,v 1.24 2008/03/23 11:59:12 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /*if (count($_GET) == 0 || !isset($_GET['tab']) || !isset($_GET['nav']))
  {
    header("Location: ../index.php");
    exit();
  }
  header("Location: book-manual_usuario.htm#" . $_GET['tab'] . "-" . $_GET['nav']);*/

  $tab = "doc";
  $nav = "help";

  require_once("../config/environment.php");
  require_once("../lib/HTML.php");

  /**
   * XHTML Start (XML prolog, DOCTYPE, title page and meta data)
   */
  $title = _("OpenClinic Help");
  require_once("../layout/xhtml_start.php");
  require_once("../layout/component.php");

  echo HTML::start('link',
    array(
      'rel' => 'stylesheet',
      'type' => 'text/css',
      'href' => '../css/' . OPEN_THEME_CSS_FILE,
      'title' => OPEN_THEME_NAME
    ),
    true
  );

  echo HTML::insertScript('pop_window.js');

  echo HTML::end('head');
  echo HTML::start('body');
  echo HTML::start('div', array('id' => 'wrap'));

  echo HTML::start('div', array('id' => 'header'));

  echo appLogo();

  $array = array(
    HTML::link(_("Close Window"), '#', null, array('onclick' => 'window.close(); return false;'))
  );
  echo HTML::itemList($array, array('id' => 'shortcuts'));

  //echo menuBar($nav);

  echo HTML::end('div'); // #header

  echo HTML::start('div', array('id' => 'main'));
  echo HTML::start('div', array('id' => 'content'));

  echo HTML::section(1, $title);

  Error::trace($_GET); // debug

  require_once("../layout/footer.php");
?>
