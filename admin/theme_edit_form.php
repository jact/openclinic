<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_edit_form.php,v 1.13 2005/07/19 19:50:04 jact Exp $
 */

/**
 * theme_edit_form.php
 *
 * Edition screen of a theme
 *
 * Author: jact <jachavar@gmail.com>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "themes";
  $returnLocation = "../admin/theme_list.php";

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to theme list if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || empty($_GET["key"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  // after login_check inclusion to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "theme_name";

  ////////////////////////////////////////////////////////////////////
  // Checking for query string flag to read data from database.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["key"]))
  {
    $idTheme = intval($_GET["key"]);
    $postVars["id_theme"] = $idTheme;

    include_once("../classes/Theme_Query.php");

    $themeQ = new Theme_Query();
    $themeQ->connect();
    if ($themeQ->isError())
    {
      Error::query($themeQ);
    }

    $numRows = $themeQ->select($idTheme);
    if ($themeQ->isError())
    {
      $themeQ->close();
      Error::query($themeQ);
    }

    if ( !$numRows )
    {
      $themeQ->close();
      include_once("../shared/header.php");

      showMessage(_("That theme does not exist."), OPEN_MSG_ERROR);

      include_once("../shared/footer.php");
      exit();
    }

    $theme = $themeQ->fetch();
    if ($themeQ->isError())
    {
      Error::fetch($themeQ, false);
    }
    else
    {
      $postVars["theme_name"] = $theme->getThemeName();
      $postVars["css_file"] = $theme->getCSSFile();
      $filename = "../css/" . $theme->getCSSFile();
      $fp = fopen($filename, 'r');
      if ($fp)
      {
        $postVars["css_rules"] = fread($fp, filesize($filename));
        fclose($fp);
      }
    }
    $themeQ->freeResult();
    $themeQ->close();
    unset($themeQ);
    unset($theme);
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Edit Theme");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Themes") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "themes.png");
  unset($links);
?>

<script type="text/javascript" defer="defer">
<!--/*--><![CDATA[/*<!--*/
function previewTheme()
{
  var secondaryWin = window.open("", "secondary", "resizable=yes,scrollbars=yes,width=680,height=450");

  document.forms[0].action = "../admin/theme_preview.php";
  document.forms[0].target = "secondary";
  document.forms[0].submit();
}

function editTheme()
{
  document.forms[0].action = "../admin/theme_edit.php";
  document.forms[0].target = "";
  document.forms[0].submit();
}
/*]]>*///-->
</script>

<?php
  echo '<p><a href="#" onclick="previewTheme(); return false;">' . _("Preview Theme") . "</a>\n";
  echo ' | <a href="../admin/theme_preload_css.php?key=' . $_GET["key"] . '">' . _("Preload CSS file") . "</a></p>\n";
  //echo ' | <a href="../admin/theme_upload_image.php">' . _("Upload image") . "</a></p>\n"; // @todo

  echo "<hr />\n";

  require_once("../shared/form_errors_msg.php");
?>

<form method="post" action="../admin/theme_edit.php">
  <div>
<?php
  showInputHidden("id_theme", $postVars["id_theme"]);

  require_once("../admin/theme_fields.php");
?>
  </div>
</form>

<?php
  showMessage('* ' . _("Note: The fields with * are required."));

  require_once("../shared/footer.php");
?>
