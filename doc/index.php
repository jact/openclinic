<?php
/**
 * index.php
 *
 * Home page of documentation project
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: index.php,v 1.23 2007/12/15 13:05:31 jact Exp $
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

  HTML::start('link',
    array(
      'rel' => 'stylesheet',
      'type' => 'text/css',
      'href' => '../css/' . OPEN_THEME_CSS_FILE,
      'title' => OPEN_THEME_NAME
    ),
    true
  );

  echo HTML::insertScript('pop_window.js');

  HTML::end('head');
  HTML::start('body');
  HTML::start('div', array('id' => 'wrap'));

  HTML::start('div', array('id' => 'header'));

  echo appLogo();

  $array = array(
    HTML::strLink(_("Close Window"), '#', null, array('onclick' => 'window.close(); return false;'))
  );
  HTML::itemList($array, array('id' => 'shortcuts'));

  //echo menuBar($nav);

  HTML::end('div'); // #header

  HTML::start('div', array('id' => 'main'));
  HTML::start('div', array('id' => 'content'));

  HTML::section(1, $title);

  Error::trace($_GET); // debug

  require_once("../layout/footer.php");
?>
