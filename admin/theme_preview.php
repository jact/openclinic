<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_preview.php,v 1.20 2005/07/21 16:55:57 jact Exp $
 */

/**
 * theme_preview.php
 *
 * Preview page of an application theme
 *
 * Author: jact <jachavar@gmail.com>
 */

  error_reporting(E_ALL & ~E_NOTICE); // normal mode

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";

  ////////////////////////////////////////////////////////////////////
  // Checking for get and post vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0 && count($_GET) == 0)
  {
    header("Location: ../admin/theme_edit_form.php");
    exit();
  }

  require_once("../classes/Setting_Query.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/Error.php");

  ////////////////////////////////////////////////////////////////////
  // Reading general settings
  ////////////////////////////////////////////////////////////////////
  $setQ = new Setting_Query();
  $setQ->connect();
  if ($setQ->isError())
  {
    Error::query($setQ);
  }

  $setQ->select();
  if ($setQ->isError())
  {
    $setQ->close();
    Error::query($setQ);
  }

  $set = $setQ->fetch();
  if ($setQ->isError())
  {
    $setQ->close();
    Error::fetch($setQ);
  }

  $setQ->freeResult();
  $setQ->close();
  unset($setQ);

  define("OPEN_LANGUAGE", $set->getLanguage());
  unset($set);

  ////////////////////////////////////////////////////////////////////
  // i18n l10n (after OPEN_LANGUAGE is defined)
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/i18n.php");

  if (isset($_GET["key"]) && intval($_GET["key"]) > 0)
  {
    include_once("../classes/Theme_Query.php");

    ////////////////////////////////////////////////////////////////////
    // Reading theme settings
    ////////////////////////////////////////////////////////////////////
    $themeQ = new Theme_Query();
    $themeQ->connect();
    if ($themeQ->isError())
    {
      Error::query($themeQ);
    }

    $themeQ->select(intval($_GET["key"]));
    if ($themeQ->isError())
    {
      $themeQ->close();
      Error::query($themeQ);
    }

    $theme = $themeQ->fetch();
    if ($themeQ->isError())
    {
      $themeQ->close();
      Error::fetch($themeQ);
    }

    $themeQ->freeResult();
    $themeQ->close();
    unset($themeQ);

    $_POST["theme_name"] = $theme->getThemeName();
    $filename = '../css/' . $theme->getCSSFile();
    $size = filesize($filename);
    $fp = fopen($filename, 'r');
    $_POST["css_rules"] = fread($fp, $size);
    fclose($fp);

    unset($theme);
  }

  if (isset($_POST["theme_name"]) && isset($_POST["css_rules"]))
  {
    ////////////////////////////////////////////////////////////////////
    // Theme related constants
    ////////////////////////////////////////////////////////////////////
    define("OPEN_THEME_NAME",      $_POST["theme_name"]);
    define("OPEN_THEME_CSS_RULES", $_POST["css_rules"]);
  }
  else
  {
    header("Location: ../admin/theme_edit_form.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // XHTML Start (XML prolog, DOCTYPE, title page and meta data)
  ////////////////////////////////////////////////////////////////////
  $title = sprintf(_("%s Theme Preview"), OPEN_THEME_NAME);
  require_once("../shared/xhtml_start.php");
?>

<link rel="shortcut icon" href="../images/miniopc.png" type="image/png" />

<style type="text/css" title="<?php echo OPEN_THEME_NAME; ?>">
<!--/*--><![CDATA[/*<!--*/
<?php echo OPEN_THEME_CSS_RULES; ?>
/*]]>*/-->
</style>

<script type="text/javascript" src="../scripts/pop_window.js" defer="defer"></script>
</head>
<body id="top">

<!-- Header -->
<div id="header">
  <div id="subHeader">
    <h1><?php echo _("Clinic Name"); ?></h1>

    <div id="headerInformation">
      <p><?php echo _("Information"); ?></p>
    </div>
  </div><!-- End #subHeader -->

  <!-- Tabs -->
  <div class="menuBar">
    <ul id="tabs">
    <?php
      echo '<li id="first"><a href="#top">' . _("Home") . "</a></li>\n";
      echo '<li><a href="#top">' . _("Medical Records") . "</a></li>\n";
      //echo '<li><a href="#top">' . "Statistics" . "</a></li>\n";
      echo '<li><span>' . _("Admin") . "</span></li>\n";
    ?>
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
    <li class="selected"><?php echo _("Theme Preview"); ?></li>
    <li><a href="#top"><?php echo _("Sample Link"); ?></a></li>
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
<h2><?php echo sprintf(_("This is a preview of the %s theme."), $_POST["theme_name"]); ?></h2>

<p><a href="#top"><?php echo _("Sample Link"); ?></a></p>

<hr />

<h3><?php echo _("Subtitle Sample:"); ?></h3>

<?php
  $thead = array(
    _("Table Heading") => array('colspan' => 2)
  );

  $tbody = array();

  $tbody[] = array(sprintf(_("Sample data row %d"), 1));

  $tbody[] = array(sprintf(_("Sample data row %d"), 2));

  $row = '* <label for="sample_text" class="requiredField">' . _("Required Field") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("sample_text", 50, 50, _("Sample Input Text"), "", "text", true);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $options = array(
    'tfoot' => array('align' => 'center'),
    'r0' => array('colspan' => 2),
    'r1' => array('colspan' => 2)
  );

  $tfoot = array(
    htmlInputButton("sample_button", _("Sample Button"), "button")
  );

  HTML::table($thead, $tbody, $tfoot, $options);

  HTML::message(_("Sample Error"), OPEN_MSG_ERROR);

  HTML::message(_("Sample Info"), OPEN_MSG_INFO);

  HTML::message(_("Sample Warning"));

  require_once("../shared/footer.php");
?>
