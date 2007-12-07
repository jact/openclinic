<?php
/**
 * theme_preload_css.php
 *
 * Upload a css file to preload contents
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_preload_css.php,v 1.26 2007/12/07 16:50:50 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.7
 */

  /**
   * Retrieving get vars
   */
  $idTheme = ((isset($_GET["id_theme"]) && intval($_GET["id_theme"]) > 0) ? intval($_GET["id_theme"]) : 0);
  $fromCopy = (isset($_GET["copy"]) ? true : false);

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "themes";
  $returnLocation = ($idTheme > 0)
    ? (($fromCopy)
      ? '../admin/theme_new_form.php?id_theme=' . $idTheme
      : '../admin/theme_edit_form.php?id_theme=' . $idTheme)
    : '../admin/theme_new_form.php';

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR, false); // Not in DEMO to prevent users' malice // We'll see

  require_once("../lib/Form.php");

  if (count($_POST) > 0)
  {
    Form::compareToken($returnLocation);
  }

  require_once("../lib/Check.php");

  if (!empty($_FILES['css_filename']['name']) && $_FILES['css_filename']['size'] > 0)
  {
    $cssRules = fread(fopen($_FILES['css_filename']['tmp_name'], 'r'), $_FILES['css_filename']['size']);
    $cssRules = Check::safeText($cssRules, false);

    //Error::debug($cssRules);
    $_POST['css_file'] = $_FILES['css_filename']['name'];
    $_POST['css_rules'] = $cssRules;

    Form::setSession($_POST);

    header("Location: " . $returnLocation);
    exit();
  }

  /**
   * Show page
   */
  $title = _("Preload CSS file");
  $focusFormField = "css_filename"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Themes") => "../admin/theme_list.php",
    (strstr($returnLocation, "edit") ? _("Edit Theme") : _("Add New Theme")) => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon icon_theme");
  unset($links);

  /**
   * Form
   */
  HTML::start('form',
    array(
      'method' => 'post',
      'action' => $_SERVER['PHP_SELF'] . ($idTheme ? '?id_theme=' . $idTheme : ''),
      'enctype' => 'multipart/form-data'
    )
  );

  $tbody = array();

  $row = Form::strLabel("css_filename", _("Path Filename") . ":", true);
  //$row .= Form::strHidden("MAX_FILE_SIZE", "10000");
  $row .= Form::strFile("css_filename", "", 50/*, array('error' => $formError["css_filename"])*/);

  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("preload", _("Submit"))
    . Form::generateToken()
  );

  Form::fieldset($title, $tbody, $tfoot);
  HTML::end('form');

  Msg::hint('* ' . _("Note: The fields with * are required."));

  HTML::para(HTML::strLink(_("Return"), $returnLocation));

  require_once("../layout/footer.php");
?>
