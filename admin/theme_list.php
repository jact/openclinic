<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_list.php,v 1.16 2005/07/30 18:58:25 jact Exp $
 */

/**
 * theme_list.php
 *
 * List of defined themes screen
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "themes";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Theme_Query.php");
  require_once("../lib/Form.php");

  /**
   * Retrieving get vars
   */
  $info = (isset($_GET["info"]) ? urldecode(Check::safeText($_GET["info"])) : "");

  /**
   * Show page
   */
  $title = _("Themes");
  require_once("../shared/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon themeIcon");
  unset($links);

  /**
   * Display insertion message if coming from new with a successful insert.
   */
  if (isset($_GET["added"]) && !empty($info))
  {
    HTML::message(sprintf(_("Theme, %s, has been added."), $info), OPEN_MSG_INFO);
  }

  /**
   * Display update message if coming from edit with a successful update.
   */
  if (isset($_GET["updated"]) && !empty($info))
  {
    HTML::message(sprintf(_("Theme, %s, has been updated."), $info), OPEN_MSG_INFO);
  }

  /**
   * Display deletion message if coming from del with a successful delete.
   */
  if (isset($_GET["deleted"]) && !empty($info))
  {
    HTML::message(sprintf(_("Theme, %s, has been deleted."), $info), OPEN_MSG_INFO);
  }

  /**
   * Display file used message.
   */
  if (isset($_GET["file"]) && !empty($info))
  {
    HTML::message(sprintf(_("Filename of theme, %s, already exists. The changes have no effect."), $info), OPEN_MSG_INFO);
  }

  $thead = array(
    _("Change Theme by default in application")
  );

  $content = Form::strLabel("id_theme", _("Choose a New Theme:"));

  $content .= Form::strSelectTable("theme_tbl", "id_theme", OPEN_THEME_ID, "theme_name");
  $content .= Form::strButton("button1", "button1", _("Update"));

  $tbody = array(
    0 => array($content)
  );

  $options = array(
    'shaded' => false
  );
?>

<form method="post" action="../admin/theme_use.php">
  <div>
<?php HTML::table($thead, $tbody, null, $options); ?>
  </div>
</form>

<hr />

<p>
  <a href="../admin/theme_new_form.php?reset=Y"><?php echo _("Add New Theme"); ?></a>
</p>

<hr />

<h3><?php echo _("Themes List:"); ?></h3>

<script type="text/javascript" defer="defer">
<!--/*--><![CDATA[/*<!--*/
function previewTheme(key)
{
  return popSecondary("../admin/theme_preview.php?key=" + key);
}
/*]]>*///-->
</script>

<?php
  $themeQ = new Theme_Query();
  $themeQ->connect();
  if ($themeQ->isError())
  {
    Error::query($themeQ);
  }

  $numRows = $themeQ->selectWithStats();
  if ($themeQ->isError())
  {
    $themeQ->close();
    Error::query($themeQ);
  }

  if ($numRows == 0)
  {
    $themeQ->close();
    HTML::message(_("No results found."), OPEN_MSG_INFO);
    include_once("../shared/footer.php");
    exit();
  }

  $thead = array(
    _("Function") => array('colspan' => 5),
    _("Theme Name"),
    _("Usage")
  );

  $validateLink = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  $validateLink = str_replace("/admin/", "/css/", $validateLink);
  $validateLink = substr($validateLink, 0, strrpos($validateLink, "/") + 1);
  $validateLink = "http://jigsaw.w3.org/css-validator/validator?uri=" . $validateLink;

  $tbody = array();
  while ($theme = $themeQ->fetch())
  {
    ////////////////////////////////////////////////////////////////////
    // Row construction
    ////////////////////////////////////////////////////////////////////
    if (in_array($theme->getCSSFile(), $reservedCSSFiles))
    {
      $row = "** " . _("edit");
    }
    else
    {
      $row = '<a href="../admin/theme_edit_form.php?key=' . $theme->getIdTheme() . '&amp;reset=Y">' . _("edit") . '</a>';
    }
    $row .= OPEN_SEPARATOR;

    $row .= '<a href="../admin/theme_new_form.php?key=' . $theme->getIdTheme() . '&amp;reset=Y">' . _("copy") . '</a>';
    $row .= OPEN_SEPARATOR;

    $row .= '<a href="../admin/theme_preview.php?key=' . $theme->getIdTheme() . '" onclick="return previewTheme(' . $theme->getIdTheme() . ')">' . _("preview") . '</a>';
    $row .= OPEN_SEPARATOR;

    $row .= '<a href="' . $validateLink . $theme->getCSSFile() . '">' . _("validate") . '</a>';
    $row .= OPEN_SEPARATOR;

    if (in_array($theme->getCSSFile(), $reservedCSSFiles))
    {
      $row .= "** " . _("del");
    }
    elseif ($theme->getIdTheme() == OPEN_THEME_ID || $theme->getCount() > 0)
    {
      $row .= "* " . _("del");
    }
    else
    {
      $row .= '<a href="../admin/theme_del_confirm.php?key=' . $theme->getIdTheme() . '&amp;name=' . urlencode($theme->getThemeName()) . '&amp;file=' . urlencode($theme->getCSSFile()) . '">' . _("del") . '</a>';
    } // end if
    $row .= OPEN_SEPARATOR;

    $row .= $theme->getThemeName();
    $row .= OPEN_SEPARATOR;

    if ($theme->getIdTheme() == OPEN_THEME_ID)
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

  HTML::table($thead, $tbody, null);

  unset($themeQ);
  unset($theme);

  HTML::message('* ' . _("Note: The delete function is not available on the themes that are currently in use by some user or by the application."));
  HTML::message('** ' . _("Note: The functions edit and delete are not available on the application themes."));

  require_once("../shared/footer.php");
?>
