<?php
/**
 * theme_edit_form.php
 *
 * Edition screen of a theme
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_edit_form.php,v 1.23 2006/03/28 19:15:32 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "themes";
  $returnLocation = "../admin/theme_list.php";

  /**
   * Checking for get vars. Go back to $returnLocation if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError

  /**
   * Retrieving get vars
   */
  $idTheme = intval($_GET["key"]);

  /**
   * Checking for $formError to read data from database
   */
  if ( !isset($formError) )
  {
    include_once("../classes/Theme_Query.php");

    /**
     * Search database
     */
    $themeQ = new Theme_Query();
    $themeQ->connect();

    if ( !$themeQ->select($idTheme) )
    {
      $themeQ->close();
      include_once("../shared/header.php");

      HTML::message(_("That theme does not exist."), OPEN_MSG_ERROR);

      include_once("../shared/footer.php");
      exit();
    }

    $theme = $themeQ->fetch();
    if ($theme)
    {
      $formVar["id_theme"] = $idTheme;
      $formVar["theme_name"] = $theme->getName();
      $formVar["css_file"] = $theme->getCSSFile();
      $filename = "../css/" . $theme->getCSSFile();
      $fp = fopen($filename, 'r');
      if ($fp)
      {
        $formVar["css_rules"] = fread($fp, filesize($filename));
        fclose($fp);
      }
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

  /**
   * Show page
   */
  $title = _("Edit Theme");
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
  document.forms[0].action = "../admin/theme_edit.php";
  document.forms[0].target = "";
  document.forms[0].submit();
}
/*]]>*///-->
</script>

<?php
  echo '<p>';
  HTML::link(_("Preview Theme"), '#', null, array('onclick' => 'previewTheme(); return false;'));
  echo ' | ';
  HTML::link(_("Preload CSS file"), '../admin/theme_preload_css.php', array('key' => $idTheme));
  //echo ' | ';
  //HTML::link(_("Upload image"), '../admin/theme_upload_image.php'); // @todo
  echo "</p>\n";

  echo "<hr />\n";

  require_once("../shared/form_errors_msg.php");

  /**
   * Edit form
   */
  echo '<form method="post" action="../admin/theme_edit.php">' . "\n";

  Form::hidden("id_theme", $formVar["id_theme"]);

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
