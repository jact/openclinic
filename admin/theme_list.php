<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_list.php,v 1.4 2004/06/16 19:10:30 jact Exp $
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
?>

<form method="post" action="../admin/theme_use.php">
  <div>
    <table>
      <thead>
        <tr>
          <th>
            <?php echo _("Change Theme by default"); ?>
          </th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>
            <?php
              echo '<label for="id_theme">';
              echo _("Choose a New Theme:");
              echo "</label>\n";

              showSelect("theme_tbl", "id_theme", OPEN_THEMEID, "theme_name");
              showInputButton("button1", _("Update"));
            ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</form>

<p>
  <a href="../admin/theme_new_form.php?reset=Y"><?php echo _("Add New Theme"); ?></a>
</p>

<h3><?php echo _("Themes List:"); ?></h3>

<?php
  $themeQ = new Theme_Query();
  $themeQ->connect();
  if ($themeQ->errorOccurred())
  {
    showQueryError($themeQ);
  }

  $numRows = $themeQ->selectWithStats();
  if ($themeQ->errorOccurred())
  {
    $themeQ->close();
    showQueryError($themeQ);
  }

  if ($numRows == 0)
  {
    $themeQ->close();
    echo '<p>' . _("No results found.") . "</p>\n";
    include_once("../shared/footer.php");
    exit();
  }
?>

<table>
  <thead>
    <tr>
      <th colspan="3">
        <?php echo _("Function"); ?>
      </th>

      <th>
        <?php echo _("Theme Name"); ?>
      </th>

      <th>
        <?php echo _("Usage"); ?>
      </th>
    </tr>
  </thead>

  <tbody>
<?php
  $rowClass = "odd";
  while ($theme = $themeQ->fetch())
  {
?>
    <tr class="<?php echo $rowClass; ?>">
      <td>
        <a href="../admin/theme_edit_form.php?key=<?php echo $theme->getIdTheme(); ?>&amp;reset=Y"><?php echo _("edit"); ?></a>
      </td>

      <td>
        <a href="../admin/theme_new_form.php?key=<?php echo $theme->getIdTheme(); ?>&amp;reset=Y"><?php echo _("copy"); ?></a>
      </td>

      <td>
        <?php
          if ($theme->getIdTheme() == OPEN_THEMEID || $theme->getCount() > 0)
          {
            echo "* " . _("del");
          }
          else
          {
        ?>
            <a href="../admin/theme_del_confirm.php?key=<?php echo $theme->getIdTheme(); ?>&amp;name=<?php echo urlencode($theme->getThemeName()); ?>"><?php echo _("del"); ?></a>
        <?php
          } // end if
        ?>
      </td>

      <td>
        <?php echo $theme->getThemeName();?>
      </td>

      <td>
        <?php
          if ($theme->getIdTheme() == OPEN_THEMEID)
          {
            echo _("in use") . " (" . _("by application") . ")";
          }
          if ($theme->getCount() > 0)
          {
            echo _("in use") . " (" . sprintf(_("%d user(s)"), $theme->getCount()) . ")";
          }
        ?>
      </td>
    </tr>
<?php
    // swap row color
    ($rowClass == "odd") ? $rowClass = "even" : $rowClass = "odd";
  } // end while
  $themeQ->freeResult();
  $themeQ->close();
  unset($themeQ);
  unset($theme);
?>
  </tbody>
</table>

<?php
  echo '<p class="advice">* ' . _("Note: The delete function is not available on the themes that are currently in use by some user or by the application.") . "</p>\n";

  require_once("../shared/footer.php");
?>
