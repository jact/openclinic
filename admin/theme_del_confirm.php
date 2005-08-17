<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_del_confirm.php,v 1.12 2005/08/17 16:53:59 jact Exp $
 */

/**
 * theme_del_confirm.php
 *
 * Confirmation screen of a theme deletion process
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "themes";
  $returnLocation = "../admin/theme_list.php";

  /**
   * Checking for query string. Go back to $returnLocation if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || empty($_GET["name"]) || empty($_GET["file"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");

  /**
   * Retrieving get vars
   */
  $idTheme = intval($_GET["key"]);
  $name = Check::safeText($_GET["name"]);
  $file = Check::safeText($_GET["file"]);

  /**
   * Show page
   */
  $title = _("Delete Theme");
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

  /**
   * Form
   */
  echo '<form method="post" action="../admin/theme_del.php">' . "\n";

  $tbody = array();

  $tbody[] = HTML::strMessage(sprintf(_("Are you sure you want to delete theme, %s?"), $name), OPEN_MSG_WARNING, false);

  $row = Form::strHidden("id_theme", "id_theme", $idTheme);
  $row .= Form::strHidden("name", "name", $name);
  $row .= Form::strHidden("file", "file", $file);
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("delete", "delete", _("Delete"))
    . Form::strButton("return", "return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array('class' => 'center');

  Form::fieldset($title, $tbody, $tfoot, $options);

  echo "</form>\n";

  require_once("../shared/footer.php");
?>
