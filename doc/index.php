<?php
/**
 * index.php
 *
 * Home page of documentation project
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: index.php,v 1.21 2006/10/14 11:18:34 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /*if (count($_GET) == 0 || !isset($_GET['tab']) || !isset($_GET['nav']))
  {
    header("Location: ../index.php");
    exit();
  }
  header("Location: book-manual_usuario.htm#" . $_GET['tab'] . "-" . $_GET['nav']);*/

  $tab = "doc";

  require_once("../config/environment.php");
  require_once("../lib/HTML.php");

  /**
   * XHTML Start (XML prolog, DOCTYPE, title page and meta data)
   */
  $title = _("OpenClinic Help");
  require_once("../layout/xhtml_start.php");

  HTML::start('link',
    array(
      'rel' => 'stylesheet',
      'type' => 'text/css',
      'href' => '../css/' . OPEN_THEME_CSS_FILE,
      'title' => OPEN_THEME_NAME
    ),
    true
  );

  HTML::start('script', array('type' => 'text/javascript', 'src' => '../js/pop_window.js', 'defer' => true));
  HTML::end('script');

  HTML::end('head');
  HTML::start('body');

  HTML::start('div', array('id' => 'header'));
  HTML::start('div', array('id' => 'subHeader'));
  HTML::section(1, _("OpenClinic Help"));
  HTML::end('div'); // #subHeader

  require_once("../layout/component.php");
  $array = array(
    "help" => array(_("Help"), "#"),
  );
  echo menuBar('help', $array);

  HTML::start('div', array('id' => 'sourceForgeLinks'));
  HTML::link(_("Close Window"), '#', null, array('onclick' => 'window.close(); return false;'));
  HTML::end('div'); // #sourceForgeLinks

  HTML::end('div'); // #header

  HTML::start('div', array('id' => 'sideBar'));

  $array = array(
    array(_("Help Topic"), array('class' => 'selected')),
    HTML::strLink(_("Help Topic"), '#')
  );
  HTML::itemList($array, array('class' => 'linkList'));

  HTML::rule();
  echo logoInfo();
  HTML::end('div'); // #sideBar

  HTML::start('div', array('id' => 'mainZone'));

  HTML::section(3, _("Sample Help Page:"));

  Error::trace($_GET); // debug

  require_once("../layout/footer.php");
?>
