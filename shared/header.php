<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: header.php,v 1.9 2004/07/07 17:52:59 jact Exp $
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

  ////////////////////////////////////////////////////////////////////
  // XHTML Start (XML prolog, DOCTYPE, title page and meta data)
  ////////////////////////////////////////////////////////////////////
  //$title = OPEN_CLINIC_NAME . ((isset($title) && $title != "") ? " : " . $title : "");
  require_once("../shared/xhtml_start.php");
?>

<link rel="home" title="<?php echo _("Clinic Home"); ?>" href="../home/index.php" />

<link rel="shortcut icon" type="image/png" href="../images/miniopc.png" />

<link rel="bookmark icon" type="image/png" href="../images/miniopc.png" />

<?php
  if ( !(isset($_GET['css']) && $_GET['css'] == "off") )
  {
    //echo <!--link rel="stylesheet" type="text/css" href="../css/style.php" /-->
?>
<style type="text/css" title="<?php echo STYLE_NAME; ?>">
<!--/*--><![CDATA[/*<!--*/
<?php require_once("../css/style.php"); ?>
/*]]>*/-->
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
      echo '<h1>';
      if (defined("OPEN_CLINIC_URL") && OPEN_CLINIC_URL)
      {
        echo '<a href="' . OPEN_CLINIC_URL . '">';
      }
      if (defined("OPEN_CLINIC_USE_IMAGE") && OPEN_CLINIC_USE_IMAGE)
      {
        echo '<img src="' . OPEN_CLINIC_IMAGE_URL . '" alt="' . OPEN_CLINIC_NAME . '" title="' . OPEN_CLINIC_NAME . '" />';
      }
      else
      {
        echo OPEN_CLINIC_NAME;
      }
      if (defined("OPEN_CLINIC_URL") && OPEN_CLINIC_URL)
      {
        echo "</a>\n";
      }
      echo "</h1>\n";
    ?>

    <div id="headerInformation">
      <?php
        echo sprintf(_("Today's date: %s"), date(_("Y-m-d")));
        echo "<br />\n";

        if (defined("OPEN_CLINIC_HOURS") && OPEN_CLINIC_HOURS)
        {
          echo sprintf(_("Clinic hours: %s"), OPEN_CLINIC_HOURS);
          echo "<br />\n";
        }

        if (defined("OPEN_CLINIC_ADDRESS") && OPEN_CLINIC_ADDRESS)
        {
          echo sprintf(_("Clinic address: %s"), OPEN_CLINIC_ADDRESS);
          echo "<br />\n";
        }

        if (defined("OPEN_CLINIC_PHONE") && OPEN_CLINIC_PHONE)
        {
          echo sprintf(_("Clinic phone: %s"), OPEN_CLINIC_PHONE);
        }
      ?>
    </div><!-- End #headerInformation -->
  </div><!-- End #subHeader -->

  <hr class="noPrint" />

  <h1 class="skipLink"><a href="#mainZone" accesskey="2"><?php echo _("Skip over navigation"); ?></a></h1>

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

<hr class="noPrint" />

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
        <img src="../images/php-logo.gif" width="90" height="33" alt="Powered by PHP" title="Powered by PHP" />
      </a>
    </p>

    <p>
      <a href="http://www.mysql.com">
        <img src="../images/mysql-logo.png" width="84" height="44" alt="Works with MySQL" title="Works with MySQL" />
      </a>
    </p>

    <p>
      <a href="http://validator.w3.org/check/referer">
        <img src="../images/valid-xhtml11.png" width="88" height="31" alt="Valid XHTML 1.1" title="Valid XHTML 1.1" />
      </a>
    </p>
  </div><!-- End #sideBarLogo -->
</div><!-- End #sideBar -->
<!-- End Side Bar -->

<hr class="noPrint" />

<!-- Main Zone -->
<div id="mainZone">
