<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_preview.php,v 1.4 2004/06/01 18:06:09 jact Exp $
 */

/**
 * theme_preview.php
 ********************************************************************
 * Preview page of an application theme
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  error_reporting(55); // E_ALL & ~E_NOTICE - normal

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: ../admin/theme_edit_form.php");
    exit();
  }

  require_once("../classes/Setting_Query.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/error_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Reading general settings
  ////////////////////////////////////////////////////////////////////
  $setQ = new Setting_Query();
  $setQ->connect();
  if ($setQ->errorOccurred())
  {
    showQueryError($setQ);
  }

  $setQ->select();
  if ($setQ->errorOccurred())
  {
    $setQ->close();
    showQueryError($setQ);
  }

  $set = $setQ->fetchSettings();
  if ( !$set )
  {
    $setQ->close();
    showQueryError($setQ);
  }

  $setQ->freeResult();
  $setQ->close();
  unset($setQ);

  define("OPEN_LANGUAGE", $set->getLanguage());
  unset($set);

  ////////////////////////////////////////////////////////////////////
  // i18n l10n
  ////////////////////////////////////////////////////////////////////
  require_once("../lib/lang_lib.php");
  require_once("../lib/nls.php");

  setLanguage(OPEN_LANGUAGE);
  initLanguage(OPEN_LANGUAGE);

  $nls = getNLS();
  define("OPEN_CHARSET", (isset($nls['charset'][OPEN_LANGUAGE]) ? $nls['charset'][OPEN_LANGUAGE] : $nls['default']['charset']));
  define("OPEN_DIRECTION", (isset($nls['direction'][OPEN_LANGUAGE]) ? $nls['charset'][OPEN_LANGUAGE] : $nls['default']['direction']));
  define("OPEN_ENCODING", "ISO-8859-1"); // getNLS()?

  ////////////////////////////////////////////////////////////////////
  // Theme related constants.
  ////////////////////////////////////////////////////////////////////
  define("STYLE_TITLE_BG_COLOR", $_POST["title_bg_color"]);
  define("STYLE_TITLE_FONT_FAMILY", $_POST["title_font_family"]);
  define("STYLE_TITLE_FONT_SIZE", $_POST["title_font_size"]);
  define("STYLE_TITLE_FONT_BOLD", isset($_POST["title_font_bold"]));
  define("STYLE_TITLE_TEXT_ALIGN", $_POST["title_align"]);
  define("STYLE_TITLE_FONT_COLOR", $_POST["title_font_color"]);

  define("STYLE_BODY_BG_COLOR", $_POST["body_bg_color"]);
  define("STYLE_BODY_FONT_FAMILY", $_POST["body_font_family"]);
  define("STYLE_BODY_FONT_SIZE", $_POST["body_font_size"]);
  define("STYLE_BODY_FONT_COLOR", $_POST["body_font_color"]);
  define("STYLE_BODY_LINK_COLOR", $_POST["body_link_color"]);

  define("STYLE_ERROR_COLOR", $_POST["error_color"]);

  define("STYLE_NAVBAR_BG_COLOR", $_POST["navbar_bg_color"]);
  define("STYLE_NAVBAR_FONT_FAMILY", $_POST["navbar_font_family"]);
  define("STYLE_NAVBAR_FONT_SIZE", $_POST["navbar_font_size"]);
  define("STYLE_NAVBAR_FONT_COLOR", $_POST["navbar_font_color"]);
  define("STYLE_NAVBAR_LINK_COLOR", $_POST["navbar_link_color"]);

  define("STYLE_TAB_BG_COLOR", $_POST["tab_bg_color"]);
  define("STYLE_TAB_FONT_FAMILY", $_POST["tab_font_family"]);
  define("STYLE_TAB_FONT_SIZE", $_POST["tab_font_size"]);
  define("STYLE_TAB_FONT_COLOR", $_POST["tab_font_color"]);
  define("STYLE_TAB_LINK_COLOR", $_POST["tab_link_color"]);
  define("STYLE_TAB_FONT_BOLD", isset($_POST["tab_font_bold"]));

  define("STYLE_TABLE_BORDER_COLOR", $_POST["table_border_color"]);
  define("STYLE_TABLE_BORDER_WIDTH", $_POST["table_border_width"]);
  define("STYLE_TABLE_CELL_PADDING", $_POST["table_cell_padding"]);

  // To prevent 'short_open_tag = On' mistake
  echo '<?xml version="1.0" encoding="' . OPEN_ENCODING . '" standalone="no" ?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo OPEN_LANGUAGE; ?>" dir="<?php echo OPEN_DIRECTION; ?>">
<head>
<title><?php echo sprintf(_("%s Theme Preview"), $_POST["theme_name"]); ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo OPEN_CHARSET; ?>" />

<meta name="MSSmartTagsPreventParsing" content="TRUE" />

<link rel="shortcut icon" href="../images/miniopc.png" type="image/png" />

<style type="text/css" title="<?php echo $_POST['theme_name']; ?>">
<!--
<?php require_once("../css/style.php"); ?>
-->
</style>

<script type="text/javascript" src="../scripts/pop_window.js" defer="defer"></script>
</head>
<body id="top">

<!-- Header -->
<div id="header">
  <div id="subHeader">
    <span class="headerTitle">
      <?php echo _("Clinic Name"); ?>
    </span>

    <div id="headerInformation">
      <a href="#" onclick="window.close(); return false;"><?php echo _("Close Window"); ?></a>
    </div><!-- End #headerInformation -->
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
    &nbsp;
  </div><!-- End #sourceForgeLinks -->
</div><!-- End #header -->
<!-- End Header -->

<!-- Side Bar -->
<div id="sideBar">
  <div class="linkList">
    <span class="selected"><?php echo _("Theme Preview"); ?></span>
    <span class="noPrint"> | </span>
    <a href="#top"><?php echo _("Sample Link"); ?></a>
  </div><!-- End .linkList -->

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
        <img src="../images/php-logo.gif" alt="Powered by PHP" title="Powered by PHP" width="90" height="33" />
      </a>
    </p>

    <p>
      <a href="http://www.mysql.com">
        <img src="../images/mysql-logo.png" alt="Works with MySQL" title="Works with MySQL" width="84" height="44" />
      </a>
    </p>

    <p>
      <a href="http://validator.w3.org/check/referer">
        <img src="../images/valid-xhtml11.png" alt="Valid XHTML 1.1" title="Valid XHTML 1.1" width="88" height="31" />
      </a>
    </p>
  </div><!-- End #sidebarLogo -->
</div><!-- End #sideBar -->
<!-- End Side Bar -->

<!-- Main Zone -->
<div id="mainZone">
<p>
  <?php echo sprintf(_("This is a preview of the %s theme."), $_POST["theme_name"]); ?>
</p>

<h3><?php echo _("Sample List:"); ?></h3>

<table>
  <thead>
    <tr>
      <th>
        <?php echo _("Table Heading"); ?>
      </th>
    </tr>
  </thead>

  <tbody>
    <tr class="odd">
      <td>
        <?php echo sprintf(_("Sample data row %d"), 1); ?>
      </td>
    </tr>

    <tr class="even">
      <td>
        <?php echo sprintf(_("Sample data row %d"), 2); ?>
      </td>
    </tr>

    <tr class="odd">
      <td>
        <?php echo sprintf(_("Sample data row %d"), 3); ?>
      </td>
    </tr>

    <tr class="even">
      <td>
        <?php showInputText("sample_text", 50, 50, _("Sample Input Text"), "", "text", true); ?>
      </td>
    </tr>

    <tr class="center">
      <td>
        <?php showInputButton("sample_button", _("Sample Button"), "button"); ?>
      </td>
    </tr>
  </tbody>
</table>

<p>
  <a href="#top"><?php echo _("Sample Link"); ?></a>
</p>

<p class="error"><?php echo _("Sample Error"); ?></p>

<?php require_once("../shared/footer.php"); ?>
