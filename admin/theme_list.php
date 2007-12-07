<?php
/**
 * theme_list.php
 *
 * List of defined themes screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_list.php,v 1.33 2007/12/07 16:50:50 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "themes";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR);

  require_once("../model/Query/Theme.php");
  require_once("../lib/Form.php");

  /**
   * Show page
   */
  $title = _("Themes");
  require_once("../layout/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon icon_theme");
  unset($links);

  $legend = _("Change Theme by default in application");

  $content = Form::strLabel("id_theme", _("Choose a New Theme:"));

  $content .= Form::strSelectTable("theme_tbl", "id_theme", OPEN_THEME_ID, "theme_name");

  $tbody = array($content);

  $tfoot = array(Form::strButton("button1", _("Update")));

  /**
   * Theme use form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../admin/theme_use.php'));
  Form::fieldset($legend, $tbody, $tfoot);
  HTML::end('form');

  HTML::para(HTML::strLink(_("Add New Theme"), '../admin/theme_new_form.php'));

  HTML::section(2, _("Themes List:"));

  /**
   * Search in database
   */
  $themeQ = new Query_Theme();
  if ( !$themeQ->selectWithStats() )
  {
    $themeQ->close();

    Msg::info(_("No results found."));
    include_once("../layout/footer.php");
    exit();
  }

  $thead = array(
    _("#"),
    _("Function") => array('colspan' => 5),
    _("Theme Name"),
    _("Usage")
  );

  $validateLink = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  $validateLink = str_replace("/admin/", "/css/", $validateLink);
  $validateLink = substr($validateLink, 0, strrpos($validateLink, "/") + 1);
  $validateLink = "http://jigsaw.w3.org/css-validator/validator?uri=" . $validateLink;

  $tbody = array();
  $i = 0;
  while ($theme = $themeQ->fetch())
  {
    /**
     * Row construction
     */
    $row = ++$i . '.';
    $row .= OPEN_SEPARATOR;

    if (in_array($theme->getCSSFile(), $reservedCSSFiles))
    {
      $row .= '**'; //"** " . _("edit");
    }
    else
    {
      $row .= HTML::strLink(
        HTML::strImage('../img/action_edit.png', _("edit")),
        '../admin/theme_edit_form.php',
        array('id_theme' => $theme->getId())
      );
    }
    $row .= OPEN_SEPARATOR;

    $row .= HTML::strLink(
      HTML::strImage('../img/action_copy.png', _("copy")),
      '../admin/theme_new_form.php',
      array('id_theme' => $theme->getId())
    );
    $row .= OPEN_SEPARATOR;

    $row .= HTML::strLink(
      HTML::strImage('../img/action_view.png', _("preview")),
      '../admin/theme_preview.php',
      array('id_theme' => $theme->getId()),
      array('class' => 'popup')
    );
    $row .= OPEN_SEPARATOR;

    $row .= HTML::strLink(
      HTML::strImage('../img/action_valid.png', _("validate")),
      $validateLink . $theme->getCSSFile()
    );
    $row .= OPEN_SEPARATOR;

    if (in_array($theme->getCSSFile(), $reservedCSSFiles))
    {
      $row .= '**'; //"** " . _("del");
    }
    elseif ($theme->getId() == OPEN_THEME_ID || $theme->getCount() > 0)
    {
      $row .= '*'; //"* " . _("del");
    }
    else
    {
      $row .= HTML::strLink(
        HTML::strImage('../img/action_delete.png', _("delete")),
        '../admin/theme_del_confirm.php',
        array(
          'id_theme' => $theme->getId(),
          'name' => $theme->getName()
        )
      );
    } // end if
    $row .= OPEN_SEPARATOR;

    $row .= $theme->getName();
    $row .= OPEN_SEPARATOR;

    if ($theme->getId() == OPEN_THEME_ID)
    {
      $row .= _("in use") . " (" . _("by application") . ") ";
    }
    if ($theme->getCount() > 0)
    {
      $row .= _("in use") . " (" . sprintf(_("%d user(s)"), $theme->getCount()) . ")";
    }
    else
    {
      $row .= "";
    }

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end while
  $themeQ->freeResult();
  $themeQ->close();

  $options = array(
    0 => array('align' => 'right'),
    1 => array('align' => 'center'),
    2 => array('align' => 'center'),
    3 => array('align' => 'center'),
    4 => array('align' => 'center'),
    5 => array('align' => 'center')
  );

  HTML::table($thead, $tbody, null, $options);

  unset($themeQ);
  unset($theme);

  Msg::hint('* ' . _("Note: The delete function is not available on the themes that are currently in use by some user or by the application."));
  Msg::hint('** ' . _("Note: The functions edit and delete are not available on the application themes."));

  require_once("../layout/footer.php");
?>
