<?php
/**
 * theme_list.php
 *
 * List of defined themes screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_list.php,v 1.35 2008/03/23 11:58:57 jact Exp $
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
   * Breadcrumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_theme");
  unset($links);

  echo HTML::para(HTML::link(_("Add New Theme"), '../admin/theme_new_form.php'));

  /**
   * Search in database
   */
  $themeQ = new Query_Theme();
  if ( !$themeQ->selectWithStats() )
  {
    $themeQ->close();

    echo Msg::info(_("No results found."));
    include_once("../layout/footer.php");
    exit();
  }

  if ($themeQ->numRows() > 1)
  {
    $legend = _("Change Theme by default in application");

    $content = Form::label("id_theme", _("Choose a New Theme:"));
    $content .= Form::selectTable("theme_tbl", "id_theme", OPEN_THEME_ID, "theme_name");

    $body = array($content);

    $foot = array(Form::button("button1", _("Update")));

    /**
     * Theme use form
     */
    echo HTML::start('form', array('method' => 'post', 'action' => '../admin/theme_use.php'));
    echo Form::fieldset($legend, $body, $foot);
    echo HTML::end('form');
  }

  echo HTML::section(2, _("Themes List:"));

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

    if ($theme->isCssReserved($theme->getCssFile()))
    {
      $row .= '**'; //"** " . _("edit");
    }
    else
    {
      $row .= HTML::link(
        HTML::image('../img/action_edit.png', _("edit")),
        '../admin/theme_edit_form.php',
        array('id_theme' => $theme->getId())
      );
    }
    $row .= OPEN_SEPARATOR;

    $row .= HTML::link(
      HTML::image('../img/action_copy.png', _("copy")),
      '../admin/theme_new_form.php',
      array('id_theme' => $theme->getId())
    );
    $row .= OPEN_SEPARATOR;

    $row .= HTML::link(
      HTML::image('../img/action_view.png', _("preview")),
      '../admin/theme_preview.php',
      array('id_theme' => $theme->getId()),
      array('class' => 'popup')
    );
    $row .= OPEN_SEPARATOR;

    $row .= HTML::link(
      HTML::image('../img/action_valid.png', _("validate")),
      $validateLink . $theme->getCssFile()
    );
    $row .= OPEN_SEPARATOR;

    if ($theme->isCssReserved($theme->getCssFile()))
    {
      $row .= '**'; //"** " . _("del");
    }
    elseif ($theme->getId() == OPEN_THEME_ID || $theme->getCount() > 0)
    {
      $row .= '*'; //"* " . _("del");
    }
    else
    {
      $row .= HTML::link(
        HTML::image('../img/action_delete.png', _("delete")),
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

  echo HTML::table($thead, $tbody, null, $options);

  unset($themeQ);
  unset($theme);

  echo Msg::hint('* ' . _("Note: The delete function is not available on the themes that are currently in use by some user or by the application."));
  echo Msg::hint('** ' . _("Note: The functions edit and delete are not available on the application themes."));

  require_once("../layout/footer.php");
?>
