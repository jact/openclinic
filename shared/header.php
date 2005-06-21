<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: header.php,v 1.21 2005/06/21 18:29:46 jact Exp $
 */

/**
 * header.php
 *
 * Contains the common header of the web pages
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['PATH_TRANSLATED']))
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../lib/html_lib.php");

  ////////////////////////////////////////////////////////////////////
  // XHTML Start (XML prolog, DOCTYPE, title page and meta data)
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/xhtml_start.php");
?>

<link rel="home" title="<?php echo _("Clinic Home"); ?>" href="../home/index.php" />

<link rel="shortcut icon" type="image/png" href="../images/miniopc.png" />

<link rel="bookmark icon" type="image/png" href="../images/miniopc.png" />

<?php
  if ( !(isset($_GET['css']) && $_GET['css'] == "off") )
  {
    echo '<link rel="stylesheet" type="text/css" href="../css/' . OPEN_THEME_CSS_FILE . '" title="' . OPEN_THEME_NAME . '" />';
  } // end-if
?>

<script type="text/javascript" src="../scripts/pop_window.js" defer="defer"></script>
</head>
<body<?php
  if (isset($focusFormName) && isset($focusFormField) && ($focusFormName != "") && ($focusFormField != ""))
  {
    echo ' onload="self.focus(); document.' . $focusFormName . "." . $focusFormField . '.focus()"';
  }
?>>

<!-- Header -->
<div id="header">
  <div id="subHeader">
    <?php
      echo '<p id="logo">';
      if (defined("OPEN_CLINIC_URL") && OPEN_CLINIC_URL)
      {
        echo '<a href="' . OPEN_CLINIC_URL . '">';
      }
      if (defined("OPEN_CLINIC_USE_IMAGE") && OPEN_CLINIC_USE_IMAGE)
      {
        list($width, $height, $type, $attr) = @getimagesize(OPEN_CLINIC_IMAGE_URL);
        echo '<img src="' . OPEN_CLINIC_IMAGE_URL . '" alt="' . OPEN_CLINIC_NAME . '" title="' . OPEN_CLINIC_NAME . '" ' . $attr . ' />';
      }
      else
      {
        echo OPEN_CLINIC_NAME;
      }
      if (defined("OPEN_CLINIC_URL") && OPEN_CLINIC_URL)
      {
        echo '</a>';
      }
      echo "</p>\n";
    ?>

    <div id="headerInformation">
      <?php
        echo '<p>' . sprintf(_("Today's date: %s"), date(_("Y-m-d"))) . "</p>\n";

        if (defined("OPEN_CLINIC_HOURS") && OPEN_CLINIC_HOURS)
        {
          echo '<p>' . sprintf(_("Clinic hours: %s"), OPEN_CLINIC_HOURS) . "</p>\n";
        }

        if (defined("OPEN_CLINIC_ADDRESS") && OPEN_CLINIC_ADDRESS)
        {
          echo '<address>' . sprintf(_("Clinic address: %s"), OPEN_CLINIC_ADDRESS) . "</address>\n";
        }

        if (defined("OPEN_CLINIC_PHONE") && OPEN_CLINIC_PHONE)
        {
          echo '<address>' . sprintf(_("Clinic phone: %s"), OPEN_CLINIC_PHONE) . "</address>\n";
        }
      ?>
    </div><!-- End #headerInformation -->
  </div><!-- End #subHeader -->

  <hr class="noPrint" />

  <p class="noPrint"><a href="#mainZone" accesskey="2"><?php echo _("Skip over navigation"); ?></a></p>

  <!-- Tabs -->
  <div class="menuBar">
    <ul id="tabs">
    <?php
      $mainNav = array(
        "home" => array(_("Home"), "../home/index.php"),
        "medical" => array(_("Medical Records"), "../medical/index.php"),
        //"stats" => array("Statistics", "../stats/index.php"),
        "admin" => array(_("Admin"), "../admin/index.php")
      );

      $sentinel = true;
      foreach ($mainNav as $key => $value)
      {
        echo '<li';
        if ($sentinel)
        {
          $sentinel = false;
          echo ' id="first"';
        }
        echo '>';

        echo ($tab == $key)
          ? '<span>' . $value[0] . '</span>'
          : '<a href="' . $value[1] . '">' . $value[0] . "</a>";
        echo "</li>\n";
      }
      unset($mainNav);
    ?>
    </ul>
  </div><!-- End .menuBar -->
  <!-- End Tabs -->

  <ul id="sourceForgeLinks">
    <li><a href="http://sourceforge.net/projects/openclinic/"><?php echo _("Project Page"); ?></a></li>

    <?php //<li><a href="http://sourceforge.net/mail/?group_id=70742">?><?php //echo _("Mailing Lists"); ?><?php //</a></li> ?>

    <li><a href="http://sourceforge.net/project/showfiles.php?group_id=70742"><?php echo _("Downloads"); ?></a></li>

    <li><a href="http://sourceforge.net/tracker/?group_id=70742&amp;atid=528857"><?php echo _("Report Bugs"); ?></a></li>

    <?php //<li><a href="http://sourceforge.net/pm/?group_id=70742">?><?php //echo _("Tasks"); ?><?php //</a></li> ?>

    <li><a href="http://sourceforge.net/forum/?group_id=70742"><?php echo _("Forums"); ?></a></li>

    <?php //<li><a href="http://sourceforge.net/project/memberlist.php?group_id=70742">?><?php //echo _("Developers"); ?><?php //</a></li> ?>
  </ul><!-- End #sourceForgeLinks -->
</div><!-- End #header -->
<!-- End Header -->

<hr class="noPrint" />

<!-- Side Bar -->
<div id="sideBar">
  <?php require_once("../navbars/" . $tab . ".php"); ?>

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
        <img src="../images/sf-logo.png" width="130" height="37" alt="Project hosted in SourceForge.net" title="Project hosted in SourceForge.net" />
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
  </div><!-- End #sideBarLogo -->
</div><!-- End #sideBar -->
<!-- End Side Bar -->

<hr class="noPrint" />

<!-- Main Zone -->
<div id="mainZone">
<?php
  if (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    showMessage(_("This is a demo version"), OPEN_MSG_INFO);
  }
?>
