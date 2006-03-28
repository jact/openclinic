<?php
/**
 * theme_new_form.php
 *
 * Addition screen of a theme
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_new_form.php,v 1.22 2006/03/28 19:15:32 jact Exp $
 * @author    jact <jachavar@gmail.com>
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
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError

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

    $themeQ->select($idTheme);

    $theme = $themeQ->fetch();
    if ($theme)
    {
      $formVar["css_file"] = $theme->getCSSFile();
      $filename = "../css/" . $theme->getCSSFile();
      $fp = fopen($filename, 'r');
      $formVar["css_rules"] = fread($fp, filesize($filename));
      fclose($fp);
    }
    else
    {
      Error::fetch($themeQ, false);
    }
    $themeQ->freeResult();
    $themeQ->close();
    unset($themeQ);
    unset($theme);
  }
  elseif ( !isset($formError) )
  {
    $filename = "../css/" . "scheme.css";
    $fp = fopen($filename, 'r');
    $formVar["css_rules"] = fread($fp, filesize($filename));
    fclose($fp);
  }

  /**
   * Show page
   */
  $title = _("Add New Theme");
  $focusFormField = "theme_name"; // to avoid JavaScript mistakes in demo version
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
  echo '<p>';
  HTML::link(_("Preview Theme"), '#', null, array('onclick' => 'previewTheme(); return false;'));
  echo ' | ';
  HTML::link(_("Preload CSS file"), '../admin/theme_preload_css.php', (isset($idTheme) ? array('key' => $idTheme, 'copy' => 'Y') : null));
  //echo ' | ';
  //HTML::link(_("Upload image"), '../admin/theme_upload_image.php'); // @todo
  echo "</p>\n";

  echo "<hr />\n";

  require_once("../shared/form_errors_msg.php");

  /**
   * New form
   */
  echo '<form method="post" action="../admin/theme_new.php">' . "\n";
  require_once("../admin/theme_fields.php");
  echo "</form>\n";

  HTML::message('* ' . _("Note: The fields with * are required."));

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  require_once("../shared/footer.php");
?>
