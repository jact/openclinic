<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: view_source.php,v 1.6 2005/07/21 16:57:13 jact Exp $
 */

/**
 * view_source.php
 *
 * View source code of a file screen
 *
 * Author: jact <jachavar@gmail.com>
 */

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/HTML.php");

  ////////////////////////////////////////////////////////////////////
  // XHTML Start (XML prolog, DOCTYPE, title page and meta data)
  ////////////////////////////////////////////////////////////////////
  $title = sprintf(_("Source file: %s"), $_GET["file"]);
  require_once("../shared/xhtml_start.php");
?>

<link rel="stylesheet" type="text/css" href="../css/style.css" />

<style type="text/css">
<!--/*--><![CDATA[/*<!--*/
body {
  background: #fff;
  color: inherit;
  border: 0;
  padding: 0;
}
code {
  white-space: pre;
}
/*]]>*/-->
</style>
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
      HTML::message(_("No file found."), OPEN_MSG_ERROR);

      echo '<p><a href="#" onclick="window.close(); return false;">' . _("Close Window") . "</a></p>\n";
    }
  }
  else
  {
    HTML::message(sprintf(_("You are not authorized to use %s tab."), _("Admin"))); // maybe change
  }
?>
</body>
</html>
