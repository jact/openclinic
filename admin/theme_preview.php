<?php
/**
 * theme_preview.php
 *
 * Preview page of an application theme
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_preview.php,v 1.39 2008/03/23 11:58:57 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  error_reporting(E_ALL & ~E_NOTICE); // normal mode

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "themes";

  /**
   * Checking for get and post vars. Go back to form if none found.
   */
  if (count($_POST) == 0 && count($_GET) == 0)
  {
    header("Location: ../admin/theme_edit_form.php");
    exit();
  }

  require_once("../model/Query/Setting.php");
  require_once("../lib/Form.php");

  /**
   * Reading general settings
   */
  $setQ = new Query_Setting();
  $setQ->select();

  $set = $setQ->fetch();
  if ( !$set )
  {
    $setQ->close();
    Error::fetch($setQ);
  }

  $setQ->freeResult();
  $setQ->close();
  unset($setQ);

  define("OPEN_LANGUAGE", $set->getLanguage());
  unset($set);

  /**
   * i18n l10n (after OPEN_LANGUAGE is defined)
   */
  require_once("../config/i18n.php");

  if (isset($_GET["id_theme"]) && intval($_GET["id_theme"]) > 0)
  {
    include_once("../model/Query/Theme.php");

    /**
     * Reading theme settings
     */
    $themeQ = new Query_Theme();
    $themeQ->select(intval($_GET["id_theme"]));

    $theme = $themeQ->fetch();
    if ( !$theme )
    {
      $themeQ->close();
      Error::fetch($themeQ);
    }

    $themeQ->freeResult();
    $themeQ->close();
    unset($themeQ);

    $_POST["theme_name"] = $theme->getName();
    $filename = '../css/' . $theme->getCssFile();
    $size = filesize($filename);
    $fp = fopen($filename, 'r');
    $_POST["css_rules"] = fread($fp, $size);
    fclose($fp);

    unset($theme);
  }

  if (isset($_POST["theme_name"]) && isset($_POST["css_rules"]))
  {
    /**
     * Theme related constants
     */
    define("OPEN_THEME_NAME",      $_POST["theme_name"]);
    define("OPEN_THEME_CSS_RULES", $_POST["css_rules"]);
  }
  else
  {
    header("Location: ../admin/theme_edit_form.php");
    exit();
  }

  /**
   * XHTML Start (XML prolog, DOCTYPE, title page and meta data)
   */
  $title = sprintf(_("%s Theme Preview"), OPEN_THEME_NAME);
  require_once("../layout/xhtml_start.php");
  require_once("../lib/Msg.php");
  require_once("../layout/component.php");

  echo HTML::start('link', array('rel' => 'shortcut icon', 'type' => 'image/png', 'href' => '../img/miniopc.png'), true);

  echo HTML::start('style', array('type' => 'text/css', 'title' => OPEN_THEME_NAME));
  echo "<!--/*--><![CDATA[/*<!--*/\n";
  echo OPEN_THEME_CSS_RULES;
  echo "/*]]>*/-->\n";
  echo HTML::end('style');

  echo HTML::insertScript('pop_window.js');

  echo HTML::end('head');
  echo HTML::start('body', array('id' => 'top'));
  echo HTML::start('div', array('id' => 'wrap'));

  echo HTML::start('div', array('id' => 'header'));

  echo appLogo();

  $array = array(
    HTML::link(_("Close Window"), '#', null, array('onclick' => 'window.close(); return false;'))
  );
  echo HTML::itemList($array, array('id' => 'shortcuts'));

  echo menuBar($tab);

  echo HTML::end('div'); // #header

  echo HTML::start('div', array('id' => 'main'));
  echo HTML::start('div', array('id' => 'content'));

  echo HTML::section(1, sprintf(_("This is a preview of the %s theme."), $_POST["theme_name"]));

  echo HTML::para(HTML::link(_("Sample Link"), '#top'));

  echo HTML::rule();

  echo HTML::section(2, _("Subtitle Sample:"));

  $thead = array(
    _("Table Heading") => array('colspan' => 2)
  );

  $tbody = array();

  $tbody[] = array(sprintf(_("Sample data row %d"), 1));

  $tbody[] = array(sprintf(_("Sample data row %d"), 2));

  $row = Form::label("sample_text", _("Required Field") . ":", array('class' => 'required'));
  $row .= OPEN_SEPARATOR;
  $row .= Form::text("sample_text", _("Sample Input Text"), array('size' => 50, 'readonly' => true));

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $options = array(
    'tfoot' => array('align' => 'center'),
    'r0' => array('colspan' => 2),
    'r1' => array('colspan' => 2)
  );

  $tfoot = array(
    Form::button("sample_button", _("Sample Button"), array('type' => 'button'))
  );

  echo HTML::table($thead, $tbody, $tfoot, $options);

  echo Msg::error(_("Sample Error"));
  echo Msg::warning(_("Sample Warning"));
  echo Msg::info(_("Sample Info"));
  echo Msg::hint(_("Sample Hint"));

  require_once("../layout/footer.php");
?>
