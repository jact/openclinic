<?php
/**
 * theme_new_form.php
 *
 * Addition screen of a theme
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_new_form.php,v 1.36 2008/03/23 11:58:57 jact Exp $
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
      $formVar["css_file"] = $theme->getCssFile();
      $filename = "../css/" . $theme->getCssFile();
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
   * Breadcrumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Themes") => $returnLocation,
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_theme");
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
  echo HTML::para(
    HTML::link(_("Preview Theme"), '#', null, array('onclick' => 'previewTheme(); return false;'))
    . ' | '
    . HTML::link(_("Preload CSS file"), '../admin/theme_preload_css.php',
      (isset($idTheme) ? array('id_theme' => $idTheme, 'copy' => 'Y') : null)
    )
    //. ' | '
    //. HTML::link(_("Upload image"), '../admin/theme_upload_image.php') // @todo
  );

  echo HTML::rule();

  echo Form::errorMsg();

  /**
   * New form
   */
  echo HTML::start('form', array('method' => 'post', 'action' => '../admin/theme_new.php'));
  require_once("../admin/theme_fields.php");
  echo HTML::end('form');

  echo Msg::hint('* ' . _("Note: The fields with * are required."));

  echo HTML::para(HTML::link(_("Return"), $returnLocation));

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  require_once("../layout/footer.php");
?>
