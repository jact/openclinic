<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_del_confirm.php,v 1.6 2004/10/03 10:27:03 jact Exp $
 */

/**
 * theme_del_confirm.php
 ********************************************************************
 * Confirmation screen of a theme deletion process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "themes";
  $returnLocation = "../admin/theme_list.php";

  ////////////////////////////////////////////////////////////////////
  // Checking for query string. Go back to theme list if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || empty($_GET["name"]) || empty($_GET["file"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/validator_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idTheme = intval($_GET["key"]);
  $name = safeText($_GET["name"]);
  $file = safeText($_GET["file"]);

  ////////////////////////////////////////////////////////////////////
  // Show confirm page
  ////////////////////////////////////////////////////////////////////
  $title = _("Delete Theme");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Themes") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "themes.png");
  unset($links);
?>

<form method="post" action="../admin/theme_del.php">
  <h3><?php echo _("Delete Theme"); ?></h3>

  <?php showMessage(sprintf(_("Are you sure you want to delete theme, %s?"), $name)); ?>

  <p>
    <?php
      showInputHidden("id_theme", $idTheme);
      showInputHidden("name", $name);
      showInputHidden("file", $file);
      showInputButton("delete", _("Delete"));
      //showInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"');
    ?>
  </p>
</form>

<?php require_once("../shared/footer.php"); ?>
