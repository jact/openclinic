<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_preload_css.php,v 1.1 2004/08/03 11:20:45 jact Exp $
 */

/**
 * theme_preload_css.php
 ********************************************************************
 * Upload a css file to preload contents
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "themes";
  $restrictInDemo = true; // To prevent users' malice // ya veremos
  $returnLocation = (isset($_GET["key"]) && intval($_GET["key"]) > 0)
    ? '../admin/theme_edit_form.php?key=' . $_GET["key"]
    : '../admin/theme_new_form.php';

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");

  if (!empty($_FILES['css_filename']['name']) && $_FILES['css_filename']['size'] > 0)
  {
    $cssRules = fread(fopen($_FILES['css_filename']['tmp_name'], 'r'), $_FILES['css_filename']['size']);
    $cssRules = safeText($cssRules, false);

    //debug($cssRules);
    $_POST['css_file'] = $_FILES['css_filename']['name'];
    $_POST['css_rules'] = $cssRules;

    $_SESSION["postVars"] = $_POST;

    header("Location: " . $returnLocation);
    exit();
  }

  // after login_check inclusion to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "css_filename";

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Preload CSS file");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Themes") => "../admin/theme_list.php",
    (strstr($returnLocation, "edit") ? _("Edit Theme") : _("Add New Theme")) => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "themes.png");
  unset($links);
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'] . ((isset($_GET['key'])) ? "?key=" . $_GET['key'] : ""); ?>" enctype="multipart/form-data">
<?php
  $thead = array(
    _("Preload a CSS file") => array('colspan' => 2)
  );

  $tbody = array();

  $row = '* <label for="css_filename" class="requiredField">' . _("Path Filename") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;

  //$row .= htmlInputHidden("MAX_FILE_SIZE", "10000");
  $row .= htmlInputFile("css_filename", "", 50);

  /*if (isset($pageErrors["css_filename"]))
  {
    $row .= htmlMessage($pageErrors["css_filename"], OPEN_MSG_ERROR);
  }*/

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $tfoot = array(
    htmlInputButton("button1", _("Submit"))
    . htmlInputButton("button2", _("Reset"), "reset")
    . htmlInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array(
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  showTable($thead, $tbody, $tfoot, $options);
?>
</form>

<?php
  showMessage('* ' . _("Note: The fields with * are required."));

  require_once("../shared/footer.php");
?>
