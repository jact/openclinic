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
 * @version   CVS: $Id: index.php,v 1.20 2006/10/13 20:01:12 jact Exp $
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

  HTML::start('div', array('class' => 'menuBar'));

  $array = array(
    array(HTML::strTag('span', _("Help")), array('id' => 'first'))
  );
  HTML::itemList($array, array('id' => 'tabs'));

  HTML::end('div'); // .menuBar

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

  HTML::start('div', array('id' => 'sideBarLogo'));

  HTML::para(
    HTML::strLink(
      HTML::strStart('img',
        array(
          'src' => '../img/openclinic-2.png',
          'width' => 130,
          'height' => 29,
          'alt' => _("Powered by OpenClinic"),
          'title' => _("Powered by OpenClinic")
        ),
        true
      ),
      'http://openclinic.sourceforge.net'
    )
  );

  $thankCoresis = HTML::strStart('img',
    array(
      'src' => '../img/thank.png',
      'width' => 65,
      'height' => 30,
      'alt' => 'OpenClinic Logo thanks to Coresis',
      'title' => 'OpenClinic Logo thanks to Coresis'
    ),
    true
  );
  $thankCoresis .= HTML::strStart('img',
    array(
      'src' => '../img/coresis.png',
      'width' => 65,
      'height' => 30,
      'alt' => 'OpenClinic Logo thanks to Coresis',
      'title' => 'OpenClinic Logo thanks to Coresis'
    ),
    true
  );
  $thankCoresis = str_replace("\n", '', $thankCoresis);
  HTML::para(HTML::strLink($thankCoresis, 'http://www.coresis.com'));
  unset($thankCoresis);

  HTML::para(
    HTML::strLink(
      HTML::strStart('img',
        array(
          'src' => '../img/sf-logo.png',
          'width' => 130,
          'height' => 37,
          'alt' => "Project hosted in SourceForge.net",
          'title' => "Project hosted in SourceForge.net"
        ),
        true
      ),
      'http://sourceforge.net'
    )
  );

  HTML::para(
    HTML::strLink(
      HTML::strStart('img',
        array(
          'src' => '../img/php-logo.gif',
          'width' => 80,
          'height' => 15,
          'alt' => "Powered by PHP",
          'title' => "Powered by PHP"
        ),
        true
      ),
      'http://www.php.net'
    )
  );

  HTML::para(
    HTML::strLink(
      HTML::strStart('img',
        array(
          'src' => '../img/mysql-logo.png',
          'width' => 80,
          'height' => 15,
          'alt' => "Works with MySQL",
          'title' => "Works with MySQL"
        ),
        true
      ),
      'http://www.mysql.com'
    )
  );

  HTML::para(
    HTML::strLink(
      HTML::strStart('img',
        array(
          'src' => '../img/valid-xhtml11.png',
          'width' => 80,
          'height' => 15,
          'alt' => "Valid XHTML 1.1",
          'title' => "Valid XHTML 1.1"
        ),
        true
      ),
      'http://validator.w3.org/check/referer'
    )
  );

  HTML::para(
    HTML::strLink(
      HTML::strStart('img',
        array(
          'src' => '../img/valid-css.png',
          'width' => 80,
          'height' => 15,
          'alt' => "Valid CSS",
          'title' => "Valid CSS"
        ),
        true
      ),
      'http://jigsaw.w3.org/css-validator',
      array('uri' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'])
    )
  );

  HTML::end('div'); // #sideBarLogo
  HTML::end('div'); // #sideBar

  HTML::start('div', array('id' => 'mainZone'));

  HTML::section(3, _("Sample Help Page:"));

  Error::trace($_GET); // debug

  require_once("../layout/footer.php");
?>
