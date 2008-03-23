<?php
/**
 * theme_preload_css.php
 *
 * Upload a css file to preload contents
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_preload_css.php,v 1.27 2008/03/23 11:58:57 jact Exp $
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
   * Breadcrumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Themes") => "../admin/theme_list.php",
    (strstr($returnLocation, "edit") ? _("Edit Theme") : _("Add New Theme")) => $returnLocation,
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_theme");
  unset($links);

  /**
   * Form
   */
  echo HTML::start('form',
    array(
      'method' => 'post',
      'action' => $_SERVER['PHP_SELF'] . ($idTheme ? '?id_theme=' . $idTheme : ''),
      'enctype' => 'multipart/form-data'
    )
  );

  $tbody = array();

  $row = Form::label("css_filename", _("Path Filename") . ":", array('class' => 'required'));
  //$row .= Form::hidden("MAX_FILE_SIZE", "10000");
  $row .= Form::file("css_filename", null,
    array(
      'size' => 50,
      //'error' => $formError["css_filename"])
    )
  );

  $tbody[] = $row;

  $tfoot = array(
    Form::button("preload", _("Submit"))
    . Form::generateToken()
  );

  echo Form::fieldset($title, $tbody, $tfoot);
  echo HTML::end('form');

  echo Msg::hint('* ' . _("Note: The fields with * are required."));

  echo HTML::para(HTML::link(_("Return"), $returnLocation));

  require_once("../layout/footer.php");
?>
