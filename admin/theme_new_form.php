<?php
/**
 * theme_new_form.php
 *
 * Addition screen of a theme
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_new_form.php,v 1.34 2007/12/07 16:50:50 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "themes";
  $returnLocation = "../admin/theme_list.php";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR);

  /**
   * Checking for query string flag to read data from database.
   * This is only used when copying an existing theme.
   */
  if (isset($_GET["id_theme"]))
  {
    $idTheme = intval($_GET["id_theme"]);

    include_once("../model/Query/Theme.php");

    $themeQ = new Query_Theme();
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
  require_once("../layout/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Themes") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon icon_theme");
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
  HTML::para(
    HTML::strLink(_("Preview Theme"), '#', null, array('onclick' => 'previewTheme(); return false;'))
    . ' | '
    . HTML::strLink(_("Preload CSS file"), '../admin/theme_preload_css.php',
      (isset($idTheme) ? array('id_theme' => $idTheme, 'copy' => 'Y') : null)
    )
    //. ' | '
    //. HTML::strLink(_("Upload image"), '../admin/theme_upload_image.php') // @todo
  );

  HTML::rule();

  Form::errorMsg();

  /**
   * New form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../admin/theme_new.php'));
  require_once("../admin/theme_fields.php");
  HTML::end('form');

  Msg::hint('* ' . _("Note: The fields with * are required."));

  HTML::para(HTML::strLink(_("Return"), $returnLocation));

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  require_once("../layout/footer.php");
?>
