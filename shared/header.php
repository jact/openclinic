<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: header.php,v 1.5 2004/05/20 19:18:59 jact Exp $
 */

/**
 * header.php
 ********************************************************************
 * Contains the common header of the web pages
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

  // To prevent 'short_open_tag = On' mistake
  echo '<?xml version="1.0" encoding="' . OPEN_ENCODING . '" standalone="no" ?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo str_replace("_", "-", OPEN_LANGUAGE); ?>" dir="<?php echo OPEN_DIRECTION; ?>">
<head>
<title>
<?php
  echo OPEN_CLINIC_NAME;
  if (isset($title) && $title != "")
  {
    echo " : " . $title;
  }
?>
</title>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo OPEN_CHARSET; ?>" />

<meta http-equiv="Content-Style-Type" content="text/css2" />

<meta http-equiv="Cache-Control" content="no-cache" />

<meta http-equiv="Pragma" content="no-cache" />

<meta http-equiv="expires" content="-1" />

<meta http-equiv="imagetoolbar" content="no" />

<meta name="robots" content="noindex,nofollow" />

<meta name="MSSmartTagsPreventParsing" content="TRUE" />

<meta name="author" content="Jose Antonio Chavarría" />

<meta name="copyright" content="2002-2004 Jose Antonio Chavarría" />

<meta name="keywords" content="OpenClinic, open source, gpl, healthcare, php, mysql, coresis" />

<meta name="description" content="OpenClinic is an easy to use, open source, medical records system written in PHP" />

<link rel="home" title="<?php echo _("Clinic Home"); ?>" href="../home/index.php" />

<link rel="shortcut icon" type="image/png" href="../images/miniopc.png" />

<link rel="bookmark icon" type="image/png" href="../images/miniopc.png" />

<?php
  if ( !(isset($_GET['css']) && $_GET['css'] == "off") )
  {
    //echo <!--link rel="stylesheet" type="text/css" href="../css/style.php" /-->
?>
<style type="text/css" title="<?php echo STYLE_NAME; ?>">
<!--
<?php require_once("../css/style.php"); ?>
-->
</style>
<?php
  } // end-if
?>

<script type="text/javascript" src="../scripts/pop_window.js" defer="defer"></script>
</head>
<body
<?php
  if (isset($focusFormName) && isset($focusFormField) && ($focusFormName != "") && ($focusFormField != ""))
  {
    echo ' onload="self.focus(); document.' . $focusFormName . "." . $focusFormField . '.focus()"';
  }
?>>

<!-- Header -->
<div id="header">
  <div id="subHeader">
    <?php
      if (defined("OPEN_CLINIC_URL"))
      {
        echo '<a href="' . OPEN_CLINIC_URL . '">';
      }
      if (OPEN_CLINIC_USE_IMAGE)
      {
        echo '<img src="' . OPEN_CLINIC_IMAGE_URL . '" alt="' . OPEN_CLINIC_NAME . '" title="' . OPEN_CLINIC_NAME . '" />';
      }
      else
      {
        echo '<span class="headerTitle">';
        echo OPEN_CLINIC_NAME;
        echo '</span>';
      }
      if (defined("OPEN_CLINIC_URL"))
      {
        echo "</a>\n";
      }
    ?>

    <div id="headerInformation">
      <?php
        echo sprintf(_("Today's date: %s"), date(_("Y-m-d")));
        echo "<br />\n";

        if (defined("OPEN_CLINIC_HOURS"))
        {
          echo sprintf(_("Clinic hours: %s"), OPEN_CLINIC_HOURS);
        }
        echo "<br />\n";

        if (defined("OPEN_CLINIC_ADDRESS"))
        {
          echo sprintf(_("Clinic address: %s"), OPEN_CLINIC_ADDRESS);
        }
        echo "<br />\n";

        if (defined("OPEN_CLINIC_PHONE"))
        {
          echo sprintf(_("Clinic phone: %s"), OPEN_CLINIC_PHONE);
        }
      ?>
    </div><!-- End #headerInformation -->
  </div><!-- End #subHeader -->

  <a class="skipLink" href="#mainZone" accesskey="2"><?php echo _("Skip over navigation"); ?></a>

  <!-- Tabs -->
  <div class="menuBar">
    <ul id="tabs">
    <?php
      echo '<li id="first">';
      echo ($tab == "home")
        ? '<span>' . _("Home") . "</span>"
        : '<a href="../home/index.php">' . _("Home") . "</a>";
      echo "</li>\n";

      echo '<li>';
      echo ($tab == "medical")
        ? '<span>' . _("Medical Records") . "</span>"
        : '<a href="../medical/index.php">' . _("Medical Records") . "</a>";
      echo "</li>\n";

      /*echo '<li>';
      echo ($tab == "stats")
        ? '<span>' . "Statistics" . "</span>"
        : '<a href="../stats/index.php">' . "Statistics" . "</a>";
      echo "</li>\n";*/

      echo '<li>';
      echo ($tab == "admin")
        ? '<span>' . _("Admin") . "</span>"
        : '<a href="../admin/index.php">' . _("Admin") . "</a>";
      echo "</li>\n";
    ?>
    </ul>
  </div><!-- End .menuBar -->
  <!-- End Tabs -->

  <div id="sourceForgeLinks">
    <?php
      if (defined("OPEN_DEMO") && OPEN_DEMO)
      {
        echo '<div class="error">' . _("This is a demo version") . "</div>\n";
      }
    ?>
    <a href="http://sourceforge.net/projects/openclinic/"><?php echo _("Project Page"); ?></a> |

    <!--a href="http://sourceforge.net/mail/?group_id=70742"><?php //echo _("Mailing Lists"); ?></a> | -->

    <a href="http://sourceforge.net/project/showfiles.php?group_id=70742"><?php echo _("Downloads"); ?></a> |

    <a href="http://sourceforge.net/tracker/?group_id=70742&amp;atid=528857"><?php echo _("Report Bugs"); ?></a> |

    <!--a href="http://sourceforge.net/pm/?group_id=70742"><?php //echo _("Tasks"); ?></a> | -->

    <a href="http://sourceforge.net/forum/?group_id=70742"><?php echo _("Forums"); ?></a>

    <!--a href="http://sourceforge.net/project/memberlist.php?group_id=70742"><?php echo _("Developers"); ?></a-->
  </div><!-- End #sourceForgeLinks -->
</div><!-- End #header -->
<!-- End Header -->

<!-- Side Bar -->
<div id="sideBar">
  <?php require_once("../navbars/" . $tab . ".php"); ?>

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
        <img src="../images/sf-logo.png" width="130" height="37" alt="SourceForge.net Logo" title="SourceForge.net Logo" />
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
  </div><!-- End #sideBarLogo -->
</div><!-- End #sideBar -->
<!-- End Side Bar -->

<!-- Main Zone -->
<div id="mainZone">
