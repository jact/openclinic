<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: view_source.php,v 1.1 2004/03/22 19:45:26 jact Exp $
 */

/**
 * view_source.php
 ********************************************************************
 * View source code of a file screen
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 22/03/04 20:45
 */

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/style.css" />

<style type="text/css">
<!--
body {
  background: #fff;
  border: 0;
  padding: 0;
}
-->
</style>

<title><?php echo _("Source file:") . ' ' . $_GET["file"]; ?></title>
</head>
<body
<?php
  if (count($_GET) == 0 || empty($_GET["file"]) || empty($_GET["tab"]))
  {
    echo 'onload="window.close()"';
  }
?>
>
<?php
  if (isset($_SESSION["hasAdminAuth"]))
  {
    $file = basename($_GET["file"]);

    if (is_file('../' . $_GET["tab"] . '/' . $file))
    {
      highlight_file('../' . $_GET["tab"] . '/' . $file);
    }
    elseif (is_file('../shared/' . $file))
    {
      highlight_file('../shared/' . $file);
    }
    else
    {
      echo '<p>' . _("No file found.") . "</p>\n";
      echo '<p><a href="#" onclick="window.close(); return false;">' . _("Close Window") . "</a></p>\n";
    }
  }
  else
  {
    echo sprintf(_("You are not authorized to use %s tab."), _("Admin")); // maybe change
  }
?>
</body>
</html>
