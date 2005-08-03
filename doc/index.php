<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: index.php,v 1.15 2005/08/03 17:04:45 jact Exp $
 */

/**
 * index.php
 *
 * Home page of documentation project
 *
 * Author: jact <jachavar@gmail.com>
 */

  /*if (count($_GET) == 0 || !isset($_GET['tab']) || !isset($_GET['nav']))
  {
    header("Location: ../index.php");
    exit();
  }
  header("Location: book-manual_usuario.htm#" . $_GET['tab'] . "-" . $_GET['nav']);*/

  $tab = "doc";

  require_once("../shared/read_settings.php");

  ////////////////////////////////////////////////////////////////////
  // XHTML Start (XML prolog, DOCTYPE, title page and meta data)
  ////////////////////////////////////////////////////////////////////
  $title = _("OpenClinic Help");
  require_once("../shared/xhtml_start.php");

  echo '<link rel="stylesheet" type="text/css" href="../css/' . OPEN_THEME_CSS_FILE . '" title="' . OPEN_THEME_NAME . '" />';
?>

<script type="text/javascript" src="../scripts/pop_window.js" defer="defer"></script>
</head>
<body>

<!-- Header -->
<div id="header">
  <div id="subHeader">
    <h1><?php echo _("OpenClinic Help"); ?></h1>
  </div><!-- End #subHeader -->

  <!-- Tabs -->
  <div class="menuBar">
    <ul id="tabs">
      <li id="first"><span><?php echo _("Help"); ?></span></li>
    </ul>
  </div><!-- End .menuBar -->
  <!-- End Tabs -->

  <div id="sourceForgeLinks">
    <a href="#" onclick="window.close(); return false;"><?php echo _("Close Window"); ?></a>
  </div><!-- End #sourceForgeLinks -->
</div><!-- End #header -->
<!-- End Header -->

<!-- Side Bar -->
<div id="sideBar">
  <ul class="linkList">
    <li class="selected"><?php echo _("Help Topic"); ?></li>
    <li><a href="#"><?php echo _("Help Topic"); ?></a></li>
  </ul><!-- End .linkList -->

  <hr />

  <div id="sideBarLogo">
    <p>
      <a href="http://openclinic.sourceforge.net">
        <img src="../images/openclinic-2.png" width="130" height="29" alt="<?php echo _("Powered by OpenClinic"); ?>" title="<?php echo _("Powered by OpenClinic"); ?>" />
      </a>
    </p>

    <p>
      <a href="http://www.coresis.com">
        <img src="../images/thank.png" width="65" height="30" alt="OpenClinic Logo thanks to Coresis" title="OpenClinic Logo thanks to Coresis" /><img src="../images/coresis.png" width="65" height="30" alt="OpenClinic Logo thanks to Coresis" title="OpenClinic Logo thanks to Coresis" />
      </a>
    </p>

    <p>
      <a href="http://sourceforge.net">
        <img src="../images/sf-logo.png" width="130" height="37" alt="SourceForge.net Logo"  title="SourceForge.net Logo" />
      </a>
    </p>

    <p>
      <a href="http://www.php.net">
        <img src="../images/php-logo.gif" width="80" height="15" alt="Powered by PHP" title="Powered by PHP" />
      </a>
    </p>

    <p>
      <a href="http://www.mysql.com">
        <img src="../images/mysql-logo.png" width="80" height="15" alt="Works with MySQL" title="Works with MySQL" />
      </a>
    </p>

    <p>
      <a href="http://validator.w3.org/check/referer">
        <img src="../images/valid-xhtml11.png" width="80" height="15" alt="Valid XHTML 1.1" title="Valid XHTML 1.1" />
      </a>
    </p>

    <p>
      <a href="http://jigsaw.w3.org/css-validator?uri=<?php echo "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']; ?>">
        <img src="../images/valid-css.png" width="80" height="15" alt="Valid CSS" title="Valid CSS" />
      </a>
    </p>
  </div><!-- End #sidebarLogo -->
</div><!-- End #sideBar -->
<!-- End Side Bar -->

<!-- Main Zone -->
<div id="mainZone">

<h3><?php echo _("Sample Help Page:"); ?></h3>

<?php Error::trace($_GET); // debug ?>

<?php require_once("../shared/footer.php"); ?>
