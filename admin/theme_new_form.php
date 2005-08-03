<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_new_form.php,v 1.16 2005/08/03 16:19:15 jact Exp $
 */

/**
 * theme_new_form.php
 *
 * Addition screen of a theme
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "themes";
  $returnLocation = "../admin/theme_list.php";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  /**
   * Checking for query string flag to read data from database.
   * This is only used when copying an existing theme.
   */
  if (isset($_GET["key"]))
  {
    $idTheme = intval($_GET["key"]);

    include_once("../classes/Theme_Query.php");

    $themeQ = new Theme_Query();
    $themeQ->connect();
    if ($themeQ->isError())
    {
      Error::query($themeQ);
    }

    $themeQ->select($idTheme);
    if ($themeQ->isError())
    {
      $themeQ->close();
      Error::query($themeQ);
    }

    $theme = $themeQ->fetch();
    if ($themeQ->isError())
    {
      Error::fetch($themeQ, false);
    }
    else
    {
      $postVars["css_file"] = $theme->getCSSFile();
      $filename = "../css/" . $theme->getCSSFile();
      $fp = fopen($filename, 'r');
      $postVars["css_rules"] = fread($fp, filesize($filename));
      fclose($fp);
    }
    $themeQ->freeResult();
    $themeQ->close();
    unset($themeQ);
    unset($theme);
  }
  elseif (isset($_GET["reset"]))
  {
    $filename = "../css/" . "scheme.css";
    $fp = fopen($filename, 'r');
    $postVars["css_rules"] = fread($fp, filesize($filename));
    fclose($fp);
  }

  /**
   * Show page
   */
  $title = _("Add New Theme");
  // to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "theme_name";
  require_once("../shared/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Themes") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon themeIcon");
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
  document.forms[0].action = "../admin/theme_new.php";
  document.forms[0].target = "";
  document.forms[0].submit();
}
/*]]>*///-->
</script>

<?php
  echo '<p><a href="#" onclick="previewTheme(); return false;">' . _("Preview Theme") . "</a>\n";
  echo ' | <a href="../admin/theme_preload_css.php' . (isset($idTheme) ? '?key=' . $idTheme . '&amp;copy=Y' : '') . '">' . _("Preload CSS file") . "</a></p>\n";
  //echo ' | <a href="../admin/theme_upload_image.php">' . _("Upload image") . "</a></p>\n"; // @todo

  echo "<hr />\n";

  require_once("../shared/form_errors_msg.php");
?>

<form method="post" action="../admin/theme_new.php">
  <div>
<?php require_once("../admin/theme_fields.php"); ?>
  </div>
</form>

<?php
  HTML::message('* ' . _("Note: The fields with * are required."));

  require_once("../shared/footer.php");
?>
