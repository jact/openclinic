<?php
/**
 * theme_preview.php
 *
 * Preview page of an application theme
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_preview.php,v 1.38 2007/12/15 13:04:12 jact Exp $
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

  HTML::start('link', array('rel' => 'shortcut icon', 'type' => 'image/png', 'href' => '../img/miniopc.png'), true);

  HTML::start('style', array('type' => 'text/css', 'title' => OPEN_THEME_NAME));
  echo "<!--/*--><![CDATA[/*<!--*/\n";
  echo OPEN_THEME_CSS_RULES;
  echo "/*]]>*/-->\n";
  HTML::end('style');

  echo HTML::insertScript('pop_window.js');

  HTML::end('head');
  HTML::start('body', array('id' => 'top'));
  HTML::start('div', array('id' => 'wrap'));

  HTML::start('div', array('id' => 'header'));

  echo appLogo();

  $array = array(
    HTML::strLink(_("Close Window"), '#', null, array('onclick' => 'window.close(); return false;'))
  );
  HTML::itemList($array, array('id' => 'shortcuts'));

  echo menuBar($tab);

  HTML::end('div'); // #header

  HTML::start('div', array('id' => 'main'));
  HTML::start('div', array('id' => 'content'));

  HTML::section(1, sprintf(_("This is a preview of the %s theme."), $_POST["theme_name"]));

  HTML::para(HTML::strLink(_("Sample Link"), '#top'));

  HTML::rule();

  HTML::section(2, _("Subtitle Sample:"));

  $thead = array(
    _("Table Heading") => array('colspan' => 2)
  );

  $tbody = array();

  $tbody[] = array(sprintf(_("Sample data row %d"), 1));

  $tbody[] = array(sprintf(_("Sample data row %d"), 2));

  $row = Form::strLabel("sample_text", _("Required Field") . ":", true);
  $row .= OPEN_SEPARATOR;
  $row .= Form::strText("sample_text", 50, _("Sample Input Text"), array('readonly' => true));

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $options = array(
    'tfoot' => array('align' => 'center'),
    'r0' => array('colspan' => 2),
    'r1' => array('colspan' => 2)
  );

  $tfoot = array(
    Form::strButton("sample_button", _("Sample Button"), "button")
  );

  HTML::table($thead, $tbody, $tfoot, $options);

  Msg::error(_("Sample Error"));
  Msg::warning(_("Sample Warning"));
  Msg::info(_("Sample Info"));
  Msg::hint(_("Sample Hint"));

  require_once("../layout/footer.php");
?>
