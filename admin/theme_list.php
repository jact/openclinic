<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_list.php,v 1.7 2004/07/29 19:24:49 jact Exp $
 */

/**
 * theme_list.php
 ********************************************************************
 * List of defined themes screen
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "themes";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Theme_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../lib/input_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Themes");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  showNavLinks($links, "themes.png");
  unset($links);

  ////////////////////////////////////////////////////////////////////
  // Display insertion message if coming from new with a successful insert.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["added"]) && isset($_GET["info"]))
  {
    showMessage(sprintf(_("Theme, %s, has been added."), urldecode($_GET["info"])), OPEN_MSG_INFO);
  }

  ////////////////////////////////////////////////////////////////////
  // Display update message if coming from edit with a successful update.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["updated"]) && isset($_GET["info"]))
  {
    showMessage(sprintf(_("Theme, %s, has been updated."), urldecode($_GET["info"])), OPEN_MSG_INFO);
  }

  ////////////////////////////////////////////////////////////////////
  // Display deletion message if coming from del with a successful delete.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["deleted"]) && isset($_GET["info"]))
  {
    showMessage(sprintf(_("Theme, %s, has been deleted."), urldecode($_GET["info"])), OPEN_MSG_INFO);
  }

  $thead = array(
    _("Change Theme by default")
  );

  $content = '<label for="id_theme">';
  $content .= _("Choose a New Theme:");
  $content .= "</label>\n";

  $content .= htmlSelect("theme_tbl", "id_theme", OPEN_THEMEID, "theme_name");
  $content .= htmlInputButton("button1", _("Update"));

  $tbody = array(
    0 => array($content)
  );

  $options = array(
    'shaded' => false
  );
?>

<form method="post" action="../admin/theme_use.php">
  <div>
<?php showTable($thead, $tbody, null, $options); ?>
  </div>
</form>

<hr />

<p>
  <a href="../admin/theme_new_form.php?reset=Y"><?php echo _("Add New Theme"); ?></a>
</p>

<hr />

<h3><?php echo _("Themes List:"); ?></h3>

<?php
  $themeQ = new Theme_Query();
  $themeQ->connect();
  if ($themeQ->isError())
  {
    showQueryError($themeQ);
  }

  $numRows = $themeQ->selectWithStats();
  if ($themeQ->isError())
  {
    $themeQ->close();
    showQueryError($themeQ);
  }

  if ($numRows == 0)
  {
    $themeQ->close();
    showMessage(_("No results found."), OPEN_MSG_INFO);
    include_once("../shared/footer.php");
    exit();
  }

  $thead = array(
    _("Function") => array('colspan' => 3),
    _("Theme Name"),
    _("Usage")
  );

  $tbody = array();
  while ($theme = $themeQ->fetch())
  {
    ////////////////////////////////////////////////////////////////////
    // Row construction
    ////////////////////////////////////////////////////////////////////
    $row = '<a href="../admin/theme_edit_form.php?key=' . $theme->getIdTheme() . '&amp;reset=Y">' . _("edit") . '</a>';
    $row .= OPEN_SEPARATOR;
    $row .= '<a href="../admin/theme_new_form.php?key=' . $theme->getIdTheme() . '&amp;reset=Y">' . _("copy") . '</a>';
    $row .= OPEN_SEPARATOR;
    if ($theme->getIdTheme() == OPEN_THEMEID || $theme->getCount() > 0)
    {
      $row .= "* " . _("del");
    }
    else
    {
      $row .= '<a href="../admin/theme_del_confirm.php?key=' . $theme->getIdTheme() . '&amp;name=' . urlencode($theme->getThemeName()) . '">' . _("del") . '</a>';
    } // end if
    $row .= OPEN_SEPARATOR;
    $row .= $theme->getThemeName();
    $row .= OPEN_SEPARATOR;
    if ($theme->getIdTheme() == OPEN_THEMEID)
    {
      $row .= _("in use") . " (" . _("by application") . ")";
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

  showTable($thead, $tbody, null);

  unset($themeQ);
  unset($theme);

  showMessage('* ' . _("Note: The delete function is not available on the themes that are currently in use by some user or by the application."));

  require_once("../shared/footer.php");
?>
