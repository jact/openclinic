<?php
/**
 * header.php
 *
 * Contains the common header of the web pages
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: header.php,v 1.28 2006/04/10 19:55:25 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../lib/HTML.php");

  /**
   * XHTML Start (XML prolog, DOCTYPE, title page and meta data)
   */
  require_once("../shared/xhtml_start.php");
?>

<link rel="home" title="<?php echo _("Clinic Home"); ?>" href="../home/index.php" />

<link rel="icon" type="image/png" href="../images/miniopc.png" />

<link rel="shortcut icon" type="image/png" href="../images/miniopc.png" />

<link rel="bookmark icon" type="image/png" href="../images/miniopc.png" />

<?php
  if ( !(isset($_GET['css']) && $_GET['css'] == "off") )
  {
    echo '<link rel="stylesheet" type="text/css" href="../css/' . OPEN_THEME_CSS_FILE . '" title="' . OPEN_THEME_NAME . '" />' . "\n";
  }

  if (isset($isMd5) && $isMd5)
  {
    echo '<script src="../scripts/md5.js" type="text/javascript" defer="defer"></script>' . "\n";
    echo '<script src="../scripts/password.php" type="text/javascript" defer="defer"></script>' . "\n";
  }
?>

<script type="text/javascript" src="../scripts/pop_window.js" defer="defer"></script>
</head>
<body<?php
  if (isset($focusFormField) && !empty($focusFormField))
  {
    echo ' onload="self.focus(); var field = document.getElementById(\'' . $focusFormField . '\'); if (field != null) field.focus();"';
  }
?>>

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

  <hr />

  <p id="skipLink"><?php HTML::link(_("Skip over navigation"), '#mainZone', null, array('accesskey' => 2)); ?></p>

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
          : HTML::strLink($value[0], $value[1]);
        echo "</li>\n";
      }
      unset($mainNav);
    ?>
    </ul>
  </div><!-- End .menuBar -->

  <ul id="sourceForgeLinks">
<?php
  $sfLinks = array(
    _("Project Page") => 'http://sourceforge.net/projects/openclinic/',
    //_("Mailing Lists") => 'http://sourceforge.net/mail/?group_id=70742',
    _("Downloads") => 'http://sourceforge.net/project/showfiles.php?group_id=70742',
    _("Report Bugs") => 'http://sourceforge.net/tracker/?group_id=70742&amp;atid=528857',
    //_("Tasks") => 'http://sourceforge.net/pm/?group_id=70742',
    _("Forums") => 'http://sourceforge.net/forum/?group_id=70742',
    //_("Developers"), 'http://sourceforge.net/project/memberlist.php?group_id=70742'
  );

  foreach ($sfLinks as $key => $value)
  {
    echo '<li>' . HTML::strLink($key, $value) . "</li>\n";
  }
  unset($sfLinks);
?>
  </ul><!-- End #sourceForgeLinks -->
</div><!-- End #header -->

<hr />

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

<hr />

<div id="mainZone">
<?php
  if (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    HTML::message(_("This is a demo version"), OPEN_MSG_INFO);
  }
?>
