<?php
/**
 * theme_preload_css.php
 *
 * Upload a css file to preload contents
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_preload_css.php,v 1.17 2006/03/28 19:15:32 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.7
 */

  /**
   * Retrieving get vars
   */
  $idTheme = ((isset($_GET["key"]) && intval($_GET["key"]) > 0) ? intval($_GET["key"]) : 0);
  $fromCopy = (isset($_GET["copy"]) ? true : false);

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "themes";
  $restrictInDemo = true; // To prevent users' malice // We'll see
  $returnLocation = ($idTheme > 0)
    ? (($fromCopy)
      ? '../admin/theme_new_form.php?key=' . $idTheme
      : '../admin/theme_edit_form.php?key=' . $idTheme)
    : '../admin/theme_new_form.php';

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");

  if (!empty($_FILES['css_filename']['name']) && $_FILES['css_filename']['size'] > 0)
  {
    $cssRules = fread(fopen($_FILES['css_filename']['tmp_name'], 'r'), $_FILES['css_filename']['size']);
    $cssRules = Check::safeText($cssRules, false);

    //Error::debug($cssRules);
    $_POST['css_file'] = $_FILES['css_filename']['name'];
    $_POST['css_rules'] = $cssRules;

    $_SESSION["formVar"] = $_POST;

    header("Location: " . $returnLocation);
    exit();
  }

  /**
   * Show page
   */
  $title = _("Preload CSS file");
  $focusFormField = "css_filename"; // to avoid JavaScript mistakes in demo version
  require_once("../shared/header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Themes") => "../admin/theme_list.php",
    (strstr($returnLocation, "edit") ? _("Edit Theme") : _("Add New Theme")) => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon themeIcon");
  unset($links);

  /**
   * Form
   */
  echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . (($idTheme) ? "?key=" . $idTheme : "") . '" enctype="multipart/form-data">' . "\n";

  $tbody = array();

  $row = Form::strLabel("css_filename", _("Path Filename") . ":", true);
  //$row .= Form::strHidden("MAX_FILE_SIZE", "10000");
  $row .= Form::strFile("css_filename", "", 50/*, array('error' => $formError["css_filename"])*/);

  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("button1", _("Submit"))
    . Form::strButton("return", _("Return"), "button", array('onclick' => "parent.location='" . $returnLocation . "'"))
  );

  Form::fieldset($title, $tbody, $tfoot);
  echo "</form>\n";

  HTML::message('* ' . _("Note: The fields with * are required."));

  require_once("../shared/footer.php");
?>
