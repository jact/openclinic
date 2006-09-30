<?php
/**
 * header.php
 *
 * Contains the common header of the web pages
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: header.php,v 1.29 2006/09/30 17:27:36 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../lib/HTML.php");

  /**
   * XHTML Start (XML prolog, DOCTYPE, title page and meta data)
   */
  require_once("../shared/xhtml_start.php");

  HTML::start('link', array('rel' => 'home', 'title' => _("Clinic Home"), 'href' => '../home/index.php'), true);

  HTML::start('link', array('rel' => 'icon', 'type' => 'image/png', 'href' => '../images/miniopc.png'), true);
  HTML::start('link', array('rel' => 'shortcut icon', 'type' => 'image/png', 'href' => '../images/miniopc.png'), true);
  HTML::start('link', array('rel' => 'bookmark icon', 'type' => 'image/png', 'href' => '../images/miniopc.png'), true);

  if ( !(isset($_GET['css']) && $_GET['css'] == "off") )
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
  }

  if (isset($isMd5) && $isMd5)
  {
    HTML::start('script', array('src' => '../scripts/md5.js', 'type' => 'text/javascript', 'defer' => true));
    HTML::end('script');

    HTML::start('script', array('src' => '../scripts/password.php', 'type' => 'text/javascript', 'defer' => true));
    HTML::end('script');
  }

  HTML::start('script', array('src' => '../scripts/pop_window.js', 'type' => 'text/javascript', 'defer' => true));
  HTML::end('script');

  HTML::end('head');
  HTML::start('body',
    isset($focusFormField) && !empty($focusFormField)
      ? array('onload' => 'self.focus(); var field = document.getElementById(\'' . $focusFormField . '\'); if (field != null) field.focus();')
      : null
  );

  HTML::start('div', array('id' => 'header'));
  HTML::start('div', array('id' => 'subHeader'));

  if (defined("OPEN_CLINIC_USE_IMAGE") && OPEN_CLINIC_USE_IMAGE)
  {
    list($width, $height, $type, $attr) = @getimagesize(OPEN_CLINIC_IMAGE_URL);
    $logo = HTML::strStart('img',
      array(
        'src' => OPEN_CLINIC_IMAGE_URL,
        'alt' => OPEN_CLINIC_NAME,
        'title' => OPEN_CLINIC_NAME,
        'width' => $width,
        'height' => $height
      ),
      true
    );
  }
  else
  {
    $logo = OPEN_CLINIC_NAME;
  }
  if (defined("OPEN_CLINIC_URL") && OPEN_CLINIC_URL)
  {
    $logo = HTML::strLink($logo, OPEN_CLINIC_URL);
  }
  HTML::para($logo, array('id' => 'logo'));
  unset($logo);

  HTML::start('div', array('id' => 'headerInformation'));

  HTML::para(sprintf(_("Today's date: %s"), date(_("Y-m-d"))));

  if (defined("OPEN_CLINIC_HOURS") && OPEN_CLINIC_HOURS)
  {
    HTML::para(sprintf(_("Clinic hours: %s"), OPEN_CLINIC_HOURS));
  }

  if (defined("OPEN_CLINIC_ADDRESS") && OPEN_CLINIC_ADDRESS)
  {
    HTML::tag('address', sprintf(_("Clinic address: %s"), OPEN_CLINIC_ADDRESS));
  }

  if (defined("OPEN_CLINIC_PHONE") && OPEN_CLINIC_PHONE)
  {
    HTML::tag('address', sprintf(_("Clinic phone: %s"), OPEN_CLINIC_PHONE));
  }

  HTML::end('div'); // #headerInformation
  HTML::end('div'); // #subHeader

  HTML::rule();

  HTML::para(HTML::strLink(_("Skip over navigation"), '#mainZone', null, array('accesskey' => 2)),
    array('id' => 'skipLink')
  );

  HTML::start('div', array('class' => 'menuBar'));

  $mainNav = array(
    "home" => array(_("Home"), "../home/index.php"),
    "medical" => array(_("Medical Records"), "../medical/index.php"),
    //"stats" => array("Statistics", "../stats/index.php"),
    "admin" => array(_("Admin"), "../admin/index.php")
  );

  $array = null;
  $sentinel = true;
  foreach ($mainNav as $key => $value)
  {
    $options = null;
    if ($sentinel)
    {
      $sentinel = false;
      $options = array('id' => 'first');
    }

    $item = ($tab == $key)
      ? HTML::strTag('span', $value[0])
      : HTML::strLink($value[0], $value[1]);
    $array[] = (is_null($options) ? $item : array($item, $options));
  }
  HTML::itemList($array, array('id' => 'tabs'));
  unset($mainNav, $array);

  HTML::end('div'); // .menuBar

  $sfLinks = array(
    _("Project Page") => 'http://sourceforge.net/projects/openclinic/',
    //_("Mailing Lists") => 'http://sourceforge.net/mail/?group_id=70742',
    _("Downloads") => 'http://sourceforge.net/project/showfiles.php?group_id=70742',
    _("Report Bugs") => 'http://sourceforge.net/tracker/?group_id=70742&atid=528857',
    //_("Tasks") => 'http://sourceforge.net/pm/?group_id=70742',
    _("Forums") => 'http://sourceforge.net/forum/?group_id=70742',
    //_("Developers"), 'http://sourceforge.net/project/memberlist.php?group_id=70742'
  );

  $array = null;
  foreach ($sfLinks as $key => $value)
  {
    $array[] = HTML::strLink($key, $value);
  }
  HTML::itemList($array, array('id' => 'sourceForgeLinks'));
  unset($sfLinks, $array);

  HTML::end('div'); // #header

  HTML::rule();

  HTML::start('div', array('id' => 'sideBar'));
  require_once("../navbars/" . $tab . ".php");

  HTML::rule();

  HTML::start('div', array('id' => 'sideBarLogo'));

  HTML::para(
    HTML::strLink(
      HTML::strStart('img',
        array(
          'src' => '../images/openclinic-2.png',
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
      'src' => '../images/thank.png',
      'width' => 65,
      'height' => 30,
      'alt' => 'OpenClinic Logo thanks to Coresis',
      'title' => 'OpenClinic Logo thanks to Coresis'
    ),
    true
  );
  $thankCoresis .= HTML::strStart('img',
    array(
      'src' => '../images/coresis.png',
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
          'src' => '../images/sf-logo.png',
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
          'src' => '../images/php-logo.gif',
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
          'src' => '../images/mysql-logo.png',
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
          'src' => '../images/valid-xhtml11.png',
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
          'src' => '../images/valid-css.png',
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

  HTML::rule();

  HTML::start('div', array('id' => 'mainZone'));

  if (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    HTML::message(_("This is a demo version"), OPEN_MSG_INFO);
  }
?>
