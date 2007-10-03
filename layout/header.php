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
 * @version   CVS: $Id: header.php,v 1.4 2007/10/03 19:37:51 jact Exp $
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

  if ((isset($isMd5) && $isMd5) || (isset($focusFormField) && !empty($focusFormField)))
  {
    HTML::start('script', array('src' => '../js/event.js', 'type' => 'text/javascript', 'defer' => true));
    HTML::end('script');
  }

  if (isset($isMd5) && $isMd5)
  {
    HTML::start('script', array('src' => '../js/md5.js', 'type' => 'text/javascript', 'defer' => true));
    HTML::end('script');

    HTML::start('script', array('src' => '../js/password.php', 'type' => 'text/javascript', 'defer' => true));
    HTML::end('script');
  }

  if (isset($focusFormField) && !empty($focusFormField))
  {
    HTML::start('script', array('src' => '../js/focus.php?field=' . $focusFormField, 'type' => 'text/javascript'));
    HTML::end('script');
  }

  HTML::start('script', array('src' => '../js/pop_window.js', 'type' => 'text/javascript', 'defer' => true));
  HTML::end('script');

  HTML::end('head');
  HTML::start('body');

  HTML::start('div', array('id' => 'header'));
  HTML::start('div', array('id' => 'subHeader'));

  if (defined("OPEN_CLINIC_USE_IMAGE") && OPEN_CLINIC_USE_IMAGE && is_file(OPEN_CLINIC_IMAGE_URL))
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

  require_once("../layout/component.php");
  $mainNav = array(
    "home" => array(_("Home"), "../home/index.php"),
    "medical" => array(_("Medical Records"), "../medical/index.php"),
    //"stats" => array("Statistics", "../stats/index.php"),
    "admin" => array(_("Admin"), "../admin/index.php")
  );
  echo menuBar($tab, $mainNav);

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
  require_once("../layout/" . $tab . ".php");
  HTML::rule();
  echo logoInfo();
  HTML::end('div'); // #sideBar

  HTML::rule();

  HTML::start('div', array('id' => 'mainZone'));

  if (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    HTML::message(_("This is a demo version"), OPEN_MSG_INFO);
  }
?>
