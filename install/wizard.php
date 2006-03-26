<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: wizard.php,v 1.22 2006/03/26 15:02:49 jact Exp $
 */

/**
 * wizard.php
 *
 * OpenClinic Install Wizard
 *
 * @author jact <jachavar@gmail.com>
 * @since 0.5
 */

/**
 * Functions:
 *  void _showButton(string $name, string $value, string $type = "next")
 *  string _warnIfExtNotLoaded(string $extensionName, bool $echoWhenOk = false)
 *  bool _validateSettings(void)
 */

  error_reporting(E_ALL & ~E_NOTICE); // normal mode
  //error_reporting(E_ALL); // debug mode

  /**
   * Step 8: If we have concluded...
   */
  if ($_POST['buttonPressed'] == "next7")
  {
    header("Location: ../index.php");
    exit();
  } // end step 8

  require_once("../lib/Form.php");
  require_once("../lib/Error.php");
  require_once("../lib/Check.php");

  $themes = array(
    1 => "SerialZ",
    2 => "SuperfluousBanter",
    3 => "Sinorca",
    4 => "Gazetteer Alternate"
  );

  /**
   * Step 0: Variables initialization if first visit
   */
  if ( !$_POST['alreadyVisited'] )
  {
    //init variables
    $_POST['dbHost'] = "localhost";
    $_POST['dbUser'] = "root";
    $_POST['dbPasswd'] = "";
    $_POST['dbName'] = "openclinic";

    $_POST['clinicLanguage'] = "en"; // English by default
    $_POST['clinicName'] = "My Clinic";
    $_POST['clinicHours'] = "";
    $_POST['clinicAddress'] = "Sesame Street";
    $_POST['clinicPhone'] = "";
    $_POST['timeout'] = 20;
    $_POST['itemsPage'] = 10;
    $_POST['clinicTheme'] = 1; // SerialZ theme by default

    $_POST['firstName'] = "John";
    $_POST['surname1'] = "Doe";
    $_POST['surname2'] = "Smith";
    $_POST['adminAddress'] = "";
    $_POST['adminPhone'] = "";
    $_POST['passwd'] = "";
    $_POST['email'] = "";
    $_POST['adminTheme'] = 3; // Sinorca by default
  } // end step 0

  /**
   * i18n l10n
   */
  require_once("../shared/i18n.php");

  $locale = I18n::languageList();
  // end i18n l10n

  /**
   * XHTML Start (XML prolog, DOCTYPE, title page and meta data)
   */
  $title = _("OpenClinic Install Wizard");
  require_once("../shared/xhtml_start.php");
?>

<link rel="stylesheet" href="../css/wizard.css" type="text/css" />

<script type="text/javascript" src="../scripts/wizard.js" defer="defer"></script>
</head>
<body>
<!-- Header -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validateInstall();">
<?php
  echo "<div>\n";
  //Error::debug($_POST);
  Check::safeArray($_POST);

  Form::hidden("alreadyVisited", 1, array('id' => 'h_alreadyVisited'));
  Form::hidden("buttonPressed", null, array('id' => 'h_buttonPressed'));

  Form::hidden("dbHost", ereg_replace(" ", "", $_POST['dbHost']), array('id' => 'h_dbHost'));
  Form::hidden("dbUser", ereg_replace(" ", "", $_POST['dbUser']), array('id' => 'h_dbUser'));
  Form::hidden("dbPasswd", $_POST['dbPasswd'], array('id' => 'h_dbPasswd'));
  Form::hidden("dbName", ereg_replace(" ", "", $_POST['dbName']), array('id' => 'h_dbName'));

  Form::hidden("clinicLanguage", $_POST['clinicLanguage'], array('id' => 'h_clinicLanguage'));
  Form::hidden("clinicName", $_POST['clinicName'], array('id' => 'h_clinicName'));
  Form::hidden("clinicHours", $_POST['clinicHours'], array('id' => 'h_clinicHours'));
  Form::hidden("clinicAddress", $_POST['clinicAddress'], array('id' => 'h_clinicAddress'));
  Form::hidden("clinicPhone", $_POST['clinicPhone'], array('id' => 'h_clinicPhone'));
  Form::hidden("timeout", intval($_POST['timeout']), array('id' => 'h_timeout'));
  Form::hidden("itemsPage", intval($_POST['itemsPage']), array('id' => 'h_itemsPage'));
  Form::hidden("clinicTheme", $_POST['clinicTheme'], array('id' => 'h_clinicTheme'));

  Form::hidden("firstName", $_POST['firstName'], array('id' => 'h_firstName'));
  Form::hidden("surname1", $_POST['surname1'], array('id' => 'h_surname1'));
  Form::hidden("surname2", $_POST['surname2'], array('id' => 'h_surname2'));
  Form::hidden("adminAddress", $_POST['adminAddress'], array('id' => 'h_adminAddress'));
  Form::hidden("adminPhone", $_POST['adminPhone'], array('id' => 'h_adminPhone'));
  Form::hidden("passwd", $_POST['passwd'], array('id' => 'h_passwd'));
  Form::hidden("email", ereg_replace(" ", "", $_POST['email']), array('id' => 'h_email'));
  Form::hidden("adminTheme", $_POST['adminTheme'], array('id' => 'h_adminTheme'));
  echo "</div>\n";
?>
<div id="window">
  <h1><?php echo _("OpenClinic Install Wizard"); ?></h1>
<!-- End Header -->
<?php
  /**
   * Step 2: License
   */
  if ($_POST['buttonPressed'] == "next1" || $_POST['buttonPressed'] == "back2")
  {
    $focusFormField = "license";
    //Error::debug(OPEN_LANGUAGE);
?>
  <h2><?php echo sprintf(_("Step %d of %d: "), 2, 7) . _("License"); ?></h2>

  <p>
    <?php echo _("OpenClinic is free software, distributed under GNU General Public License (GPL). Please read the license and, if you agree, click on 'I accept' button."); ?>
  </p>

  <p class="center">
    <textarea rows="15" cols="75" name="license"><?php include("../LICENSE"); ?></textarea>
  </p>

  <div id="buttons">
    <?php
      _showButton("back1", _("Back"), "back");

      _showButton("next2", _("I accept"));
    ?>
  </div>

  <p id="status">
    <?php echo _("Read the license before continue"); ?>
  </p>
<?php
  } // end step license

  /**
   * Step 3: MySQL database settings
   */
  elseif ($_POST['buttonPressed'] == "next2" || $_POST['buttonPressed'] == "back3")
  {
    $focusFormField = "dbHost[1]";
?>
  <h2><?php echo sprintf(_("Step %d of %d: "), 3, 7) . _("MySQL Database Settings"); ?></h2>

  <p>
    <?php echo sprintf(_("Install script create OpenClinic database. These following values will be written in %s file. All fields are required."), "<tt>'database_constants.php'</tt>"); ?>
  </p>

  <div class="center">
    <table>
      <tr>
        <td><?php Form::label("dbHost", _("Database Host:")); ?></td>
        <td><?php Form::text("dbHost", 25, $_POST['dbHost'], array('maxlength' => 100)); ?></td>
        <td><?php echo _("e. g.") . " localhost"; ?></td>
      </tr>

      <tr>
        <td><?php Form::label("dbUser", _("Database User:")); ?></td>
        <td><?php Form::text("dbUser", 25, $_POST['dbUser'], array('maxlength' => 100)); ?></td>
        <td><?php echo _("e. g.") . " root"; ?></td>
      </tr>

      <tr>
        <td><?php Form::label("dbPasswd", _("Database Password:")); ?></td>
        <td><?php Form::text("dbPasswd", 25, $_POST['dbPasswd'], array('maxlength' => 100)); ?></td>
        <td><?php echo _("e. g.") . " s45gh72cv"; ?></td>
      </tr>

      <tr>
        <td><?php Form::label("dbName", _("Database Name:")); ?></td>
        <td><?php Form::text("dbName", 25, $_POST['dbName'], array('maxlength' => 100)); ?></td>
        <td><?php echo _("e. g.") . " openclinic"; ?></td>
      </tr>
    </table>
  </div>

  <div id="buttons">
    <?php
      _showButton("back2", _("Back"), "back");

      _showButton("next3", _("Next"));
    ?>
  </div>

  <p id="status">
    <?php echo _("Check data before continue"); ?>
  </p>
<?php
  } // end step mysql

  /**
   * Step 4: Config Settings
   */
  elseif ($_POST['buttonPressed'] == "next3" || $_POST['buttonPressed'] == "back4")
  {
    $focusFormField = "clinicLanguage[1]";
?>
  <h2><?php echo sprintf(_("Step %d of %d: "), 4, 7) . _("Config Settings"); ?></h2>

  <p>
    <?php echo _("These are OpenClinic config settings."); ?>
  </p>

  <div class="center">
    <table>
      <tr>
        <td><?php Form::label("clinicLanguage", _("Language") . ":"); ?></td>
        <td><?php Form::select("clinicLanguage", $locale, $_POST['clinicLanguage']); ?></td>
        <td></td>
      </tr>

      <tr>
        <td><?php Form::label("clinicName", _("Clinic Name") . ":"); ?></td>
        <td><?php Form::text("clinicName", 30, $_POST['clinicName'], array('maxlength' => 128)); ?></td>
        <td><?php echo _("e. g.") . " My Clinic"; ?></td>
      </tr>

      <tr>
        <td><?php Form::label("clinicHours", _("Clinic Hours") . ":"); ?></td>
        <td><?php Form::text("clinicHours", 30, $_POST['clinicHours'], array('maxlength' => 128)); ?></td>
        <td><?php echo _("e. g.") . " L-V 9am-5pm"; ?></td>
      </tr>

      <tr>
        <td><?php Form::label("clinicAddress", _("Clinic Address") . ":"); ?></td>
        <td><?php Form::text("clinicAddress", 30, $_POST['clinicAddress'], array('maxlength' => 200)); ?></td>
        <td><?php echo _("e. g.") . " Sesame Street"; ?></td>
      </tr>

      <tr>
        <td><?php Form::label("clinicPhone", _("Clinic Phone") . ":"); ?></td>
        <td><?php Form::text("clinicPhone", 30, $_POST['clinicPhone'], array('maxlength' => 40)); ?></td>
        <td><?php echo _("e. g.") . " 999 66 66 66"; ?></td>
      </tr>

      <tr>
        <td><?php Form::label("timeout", _("Session Timeout (minutes):")); ?></td>
        <td><?php Form::text("timeout", 30, $_POST['timeout'], array('maxlength' => 3)); ?></td>
        <td><?php echo _("e. g.") . " 20"; ?></td>
      </tr>

      <tr>
        <td><?php Form::label("itemsPage", _("Search Results (items per page):")); ?></td>
        <td><?php Form::text("itemsPage", 30, $_POST['itemsPage'], array('maxlength' => 2)); ?></td>
        <td><?php echo _("e. g.") . " 10"; ?></td>
      </tr>

      <tr>
        <td><?php Form::label("clinicTheme", _("Theme by default") . ":"); ?></td>
        <td><?php Form::select("clinicTheme", $themes, $_POST['clinicTheme']); ?></td>
        <td></td>
      </tr>
    </table>
  </div>

  <div id="buttons">
    <?php
      _showButton("back3", _("Back"), "back");

      _showButton("next4", _("Next"));
    ?>
  </div>

  <p id="status">
    <?php echo _("Customize your site"); ?>
  </p>
<?php
  } // end step config settings

  /**
   * Step 5: Admin data
   */
  elseif ($_POST['buttonPressed'] == "next4" || $_POST['buttonPressed'] == "back5")
  {
    $focusFormField = "firstName[1]";
?>
  <h2><?php echo sprintf(_("Step %d of %d: "), 5, 7) . _("Admin Data"); ?></h2>

  <div class="center">
    <table>
      <tr>
        <td><?php Form::label("firstName", _("First Name") . ":"); ?></td>
        <td><?php Form::text("firstName", 30, $_POST['firstName'], array('maxlength' => 25)); ?></td>
        <td><?php echo _("e. g.") . " John"; ?></td>
      </tr>

      <tr>
        <td><?php Form::label("surname1", _("Surname 1") . ":"); ?></td>
        <td><?php Form::text("surname1", 30, $_POST['surname1']); ?></td>
        <td><?php echo _("e. g.") . " Doe"; ?></td>
      </tr>

      <tr>
        <td><?php Form::label("surname2", _("Surname 2") . ":"); ?></td>
        <td><?php Form::text("surname2", 30, $_POST['surname2']); ?></td>
        <td><?php echo _("e. g.") . " Smith"; ?></td>
      </tr>

      <tr>
        <td><?php Form::label("adminAddress", _("Address") . ":"); ?></td>
        <td><?php Form::text("adminAddress", 30, $_POST['adminAddress'], array('maxlength' => 200)); ?></td>
        <td></td>
      </tr>

      <tr>
        <td><?php Form::label("adminPhone", _("Phone Contact") . ":"); ?></td>
        <td><?php Form::text("adminPhone", 30, $_POST['adminPhone'], array('maxlength' => 40)); ?></td>
        <td></td>
      </tr>

      <tr>
        <td><?php echo _("Login") . ":"; ?></td>
        <td class="note">admin</td>
        <td></td>
      </tr>

      <tr>
        <td><?php Form::label("passwd", _("Password") . ":"); ?></td>
        <td><?php Form::text("passwd", 30, $_POST['passwd'], array('maxlength' => 20)); ?></td>
        <td class="note"><?php echo _("required"); ?></td>
      </tr>

      <tr>
        <td><?php Form::label("email", _("Email") . ":"); ?></td>
        <td><?php Form::text("email", 30, $_POST['email'], array('maxlength' => 40)); ?></td>
        <td></td>
      </tr>

      <tr>
        <td><?php Form::label("adminTheme", _("Theme") . ":"); ?></td>
        <td><?php Form::select("adminTheme", $themes, $_POST['adminTheme']); ?></td>
        <td></td>
      </tr>
    </table>
  </div>

  <div id="buttons">
    <?php
      _showButton("back4", _("Back"), "back");

      _showButton("next5", _("Next"));
    ?>
  </div>

  <p id="status">
    <?php echo _("Password is the most important field"); ?>
  </p>
<?php
  } // end step admin data

  /**
   * Step 6: Last Check
   */
  elseif ($_POST['buttonPressed'] == "next5")
  {
    //$focusFormField = "back5";
?>
  <h2><?php echo sprintf(_("Step %d of %d: "), 6, 7) . _("Last Check before Install"); ?></h2>

  <p>
    <?php echo sprintf(_("Here are the values you entered. %sPrint this page%s to remember your admin password and other settings."), '<a href="#" onclick="window.print(); return false;">', '</a>'); ?>
  </p>

  <?php $continue = _validateSettings(); ?>

  <h3><?php echo _("MySQL Database Settings"); ?></h3>

  <blockquote>
    <?php
      echo _("Database Host:") . ' ' . $_POST['dbHost'] . "<br />";
      echo _("Database User:") . ' ' . $_POST['dbUser'] . "<br />";
      echo _("Database Password:") . ' ' . $_POST['dbPasswd'] . "<br />";
      echo _("Database Name:") . ' ' . $_POST['dbName'];
    ?>
  </blockquote>

  <h3><?php echo _("Config Settings"); ?></h3>

  <blockquote>
    <?php
      echo _("Language") . ': ' . $locale[$_POST['clinicLanguage']] . "<br />";
      echo _("Clinic Name") . ': ' . $_POST['clinicName'] . "<br />";
      echo _("Clinic Hours") . ': ' . $_POST['clinicHours'] . "<br />";
      echo _("Clinic Address") . ': ' . $_POST['clinicAddress'] . "<br />";
      echo _("Clinic Phone") . ': ' . $_POST['clinicPhone'] . "<br />";
      echo _("Session Timeout") . ': ' . $_POST['timeout'] . "<br />";
      echo _("Search Results") . ': ' . $_POST['itemsPage'] . "<br />";
      echo _("Theme by default") . ': ' . $themes[$_POST['clinicTheme']];
    ?>
  </blockquote>

  <h3><?php echo _("Admin Data"); ?></h3>

  <blockquote>
    <?php
      echo _("First Name") . ': ' . $_POST['firstName'] . "<br />";
      echo _("Surname 1") . ': ' . $_POST['surname1'] . "<br />";
      echo _("Surname 2") . ': ' . $_POST['surname2'] . "<br />";
      echo _("Address") . ': ' . $_POST['adminAddress'] . "<br />";
      echo _("Phone Contact") . ': ' . $_POST['adminPhone'] . "<br />";
      echo _("Login") . ': <span class="note">admin</span>' . "<br />";
      echo _("Password") . ': <span class="note">' . $_POST['passwd'] . "</span><br />";
      echo _("Email") . ': ' . $_POST['email'] . "<br />";
      echo _("Theme") . ': ' . $themes[$_POST['adminTheme']];
    ?>
  </blockquote>

  <div id="buttons">
    <?php
      _showButton("back5", _("Back"), "back");

      if ($continue)
      {
        _showButton("next6", _("Install"));
      }
    ?>
  </div>

  <p id="status">
    <?php echo _("If you are not sure in any value, go back before install"); ?>
  </p>
<?php
  } // end step last check

  /**
   * Step 7: Start using OpenClinic
   */
  elseif ($_POST['buttonPressed'] == "next6")
  {
    $focusFormField = "next7";
?>
  <h2><?php echo sprintf(_("Step %d of %d: "), 7, 7) . _("Start using OpenClinic"); ?></h2>
<?php
  /**
   * Write database_constants.php file
   */
  $mode = (ereg('Win', $_SERVER["HTTP_USER_AGENT"])) ? 'wb' : 'w';
  $aux = fopen('../database_constants.php', $mode);
  if ( !$aux )
  {
    echo sprintf(_("Incorrect permissions of %s file. Continue is impossible."), "database_constants.php");
    exit();
  }

  $fileContent ='<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: wizard.php,v 1.22 2006/03/26 15:02:49 jact Exp $
 */

/**
 * database_constants.php
 *
 * Definition of database connection variables
 *
 * @author jact <jachavar@gmail.com>
 * Last modified: ' . date("d/m/Y H:i") . '
 */

  if (str_replace("\\\\", "/", __FILE__) == str_replace("\\\\", "/", $_SERVER[\'SCRIPT_FILENAME\']))
  {
    header("Location: ./index.php");
    exit();
  }

/**
 * A T T E N T I O N !
 *
 * Please modify the following database connection variables to match
 * the MySQL database and user that you have created for OpenClinic.
 */
  define("OPEN_HOST",     "' . $_POST['dbHost'] . '");
  define("OPEN_DATABASE", "' . $_POST['dbName'] . '");
  define("OPEN_USERNAME", "' . $_POST['dbUser'] . '");
  define("OPEN_PWD",      "' . $_POST['dbPasswd'] . '");
?>
';
  if ( !fwrite($aux, $fileContent) )
  {
    echo _("Error in writing proccess. Continue is impossible.");
    exit();
  }
  fclose($aux);
  flush();

  /**
   * This is needed to really flush file contents
   */
  $aux = fopen('../database_constants.php', 'r');
  fread($aux, filesize('../database_constants.php'));
  fclose($aux);

  /**
   * Database connection
   */
  $db = @mysql_connect($_POST['dbHost'], $_POST['dbUser'], $_POST['dbPasswd']);
  if (mysql_errno() > 0) // problem with server
  {
    $no = mysql_errno();
    $msg = mysql_error();

    echo '<hr />' . $no . '<br />' . $msg . '<hr />';
    echo '<p>' . _("The MySQL server does not work or login pass is false.") . '</p>';
    echo '<p>' . _("Please check these values and if any is wrong back to step 2:") . '</p>';
    echo '<ul>';
    echo '<li>' . _("Database Host:") . ' ' . $_POST['dbHost'] . '</li>';
    echo '<li>' . _("Database User:") . ' ' . $_POST['dbUser'] . '</li>';
    echo '<li>' . _("Database Password:") . ' ' . $_POST['dbPasswd'] . '</li>';
    echo '</ul>';

    exit();
  }

  /**
   * Database creation
   */
  mysql_query('DROP DATABASE IF EXISTS ' . $_POST['dbName']) or die(sprintf(_("Instruction: %s Error: %s"), $sql, mysql_error()));
  //mysql_create_db($_POST['dbName']);
  mysql_query('CREATE DATABASE ' . $_POST['dbName']) or die(sprintf(_("Instruction: %s Error: %s"), $sql, mysql_error()));
  //mysql_select_db($_POST['dbName']);
  mysql_query('USE ' . $_POST['dbName']) or die(sprintf(_("Instruction: %s Error: %s"), $sql, mysql_error()));

  /**
   * Database tables creation
   */
  require_once("../install/parse_sql_file.php");

  foreach ($tables as $tableName)
  {
    $result = parseSQLFile("./sql/" . $tableName . ".sql", $tableName, true);

    if ($result)
    {
      echo sprintf(_("Table %s dropped."), $tableName) . "<br />\n";
      echo sprintf(_("Table %s created."), $tableName) . "<br />\n";
    }
    else
    {
      HTML::message(_("Last instruction failed"), OPEN_MSG_ERROR);
      exit();
    }
  }

  /**
   * Database tables update (setting_tbl, staff_tbl, user_tbl)
   */
  //mysql_select_db($_POST['dbName']);
  mysql_connect($_POST['dbHost'], $_POST['dbUser'], $_POST['dbPasswd']) or die(sprintf(_("Instruction: %s Error: %s"), $sql, mysql_error()));
  mysql_query('USE ' . $_POST['dbName']) or die(sprintf(_("Instruction: %s Error: %s"), $sql, mysql_error()));

  $sql = "UPDATE setting_tbl SET ";
  $sql .= "clinic_name='" . $_POST['clinicName'] . "', ";
  $sql .= "clinic_hours='" . $_POST['clinicHours'] . "', ";
  $sql .= "clinic_address='" . $_POST['clinicAddress'] . "', ";
  $sql .= "clinic_phone='" . $_POST['clinicPhone'] . "', ";
  $sql .= "language='" . $_POST['clinicLanguage'] . "', ";
  $sql .= "session_timeout=" . intval($_POST['timeout']) . ", ";
  $sql .= "items_per_page=" . intval($_POST['itemsPage']) . ", ";
  $sql .= "id_theme=" . intval($_POST['clinicTheme']) . ";";
  mysql_query($sql) or die(sprintf(_("Instruction: %s Error: %s"), $sql, mysql_error()));

  $sql = "UPDATE staff_tbl SET ";
  $sql .= "first_name='" . $_POST['firstName'] . "', ";
  $sql .= "surname1='" . $_POST['surname1'] . "', ";
  $sql .= "surname2='" . $_POST['surname2'] . "', ";
  $sql .= "address=" . (($_POST['adminAddress'] == "") ? "NULL, " : "'" . $_POST['adminAddress'] . "', ");
  $sql .= "phone_contact=" . (($_POST['adminPhone'] == "") ? "NULL " : "'" . $_POST['adminPhone'] . "' ");
  $sql .= " WHERE login='admin';";
  mysql_query($sql) or die(sprintf(_("Instruction: %s Error: %s"), $sql, mysql_error()));

  $sql = "UPDATE user_tbl SET ";
  $sql .= "email=" . (($_POST['email'] == "") ? "NULL," : "'" . $_POST['email'] . "', ");
  $sql .= "id_theme=" . intval($_POST['adminTheme']) . ", ";
  $sql .= "pwd=md5('" . $_POST['passwd'] . "')";
  $sql .= " WHERE id_user=2;";
  mysql_query($sql) or die(sprintf(_("Instruction: %s Error: %s"), $sql, mysql_error()));
?>

  <p>
    <?php echo _("OpenClinic tables have been created successfully!"); ?>
  </p>

  <p>
    <?php echo sprintf(_("%sSecurity advice:%s To protect your site, make %s and <tt>'openclinic/install/'</tt> files read only (chmod 400)."), "<tt>'database_constants.php'</tt>", '<span class="note">', '</span>'); ?>
  </p>

  <div id="buttons">
    <?php _showButton("next7", _("Start OpenClinic")); ?>
  </div>

  <p id="status">
    <?php echo _("That's folks!!!"); ?>
  </p>
<?php
  } // end step 7

  /**
   * Step 1: Requirements
   */
  else
  {
    //$focusFormField = "next1";
?>
  <h2><?php echo sprintf(_("Step %d of %d: "), 1, 7) . _("Requirements"); ?></h2>

  <p>
    <?php echo sprintf(_("This script will configure OpenClinic database and %s file."), "<tt>'database_constants.php'</tt>"); ?>
  </p>

  <blockquote class="note">
    <?php echo sprintf(_("The file %s already exists in your system! This script INSTALLS and does not UPGRADE. It will remove all OpenClinic data. If this is what you want, you can go on. If you want upgrade go to the %supgrade script%s."), "<tt>'database_constants.php'</tt>", '<a href="./index.php">', '</a>'); ?>
  </blockquote>

  <h3><?php echo _("Read Thouroughly") . ":"; ?></h3>

  <p>
    <?php echo _("For OpenClinic to work, you need the following on the server:"); ?>
  </p>

  <ul>
    <li><?php echo sprintf(_("Webserver (you have <strong>%s</strong>)"), $_SERVER['SERVER_SOFTWARE']); ?></li>

    <li><?php echo sprintf(_("PHP >= 4.2 (you have <strong>PHP %s</strong>)"), phpversion()); ?>
      <ul>
        <?php
          echo '<li>' . _warnIfExtNotLoaded("standard", true) . "</li>\n";
          echo '<li>' . _warnIfExtNotLoaded("session", true) . "</li>\n";
          echo '<li>' . _warnIfExtNotLoaded("mysql", true) . "</li>\n";
          echo '<li>' . _warnIfExtNotLoaded("pcre", true) . "</li>\n";
          echo '<li>' . _warnIfExtNotLoaded("gettext", true) . "</li>\n";
        ?>
      </ul>
    </li>

    <li>MySQL >= 3.23.36</li>

    <li>
<?php
  echo sprintf(_("Write access to %s file"), "<tt>'database_constants.php'</tt>");

  if (chmod("../database_constants.php", 0666) == false)
  {
    echo '<p>' . _("This file is still not web writeable. This is good in normal time, for security reasons. However, during this installation, you need to set this file with writing permissions. You will have to do it manually.") . "</p>\n";
    if (defined('PHP_OS') && eregi('win', PHP_OS))
    {
      echo '<p class="note">' . sprintf(_("Deactivate read-only mode in the file properties of %s."), "<tt>'database_constants.php'</tt>") . "</p>\n";
    }
    else
    {
      echo '<p class="note">' . sprintf(_("Open a shell, go to <tt>openclinic</tt> directory and type <tt>chmod 666 %s</tt>."), "database_constants.php") . "</p>\n";
    }
    echo '<p>' . _("Once made it, click in Next button.") . "</p>\n";
  }
  else
  {
    echo ' - <strong>' . _("ok") . "</strong>\n";
  }
?>
    </li>
  </ul>

  <p>
    <?php echo sprintf(_("To install OpenClinic on a Windows machine for test, you just need to install %sAppServ%s before."), '<a href="http://academic.cmri.ac.th/appserv">', '</a>'); ?>
  </p>

  <p>
    <?php echo sprintf(_("For more details, see %sInstall Instructions%s."), '<a href="../install.html">', '</a>'); ?>
  </p>

  <div id="buttons">
    <?php _showButton("next1", _("Next")); ?>
  </div>

  <p id="status">
    <?php echo _("Press Next button to continue"); ?>
  </p>
<?php
  } // end step 1: requirements
?>
<!-- Footer -->
</div>
</form>
<?php
  if (isset($focusFormField) && ($focusFormField != ""))
  {
    echo '<script type="text/javascript">';
    echo "\n<!--/*--><![CDATA[/*<!--*/\n";
    echo 'self.focus(); document.forms[0].' . $focusFormField . '.focus();';
    echo "\n/*]]>*///-->\n";
    echo "</script>\n";
  }
?>
</body>
</html>
<!-- End Footer -->
<?php
/**
 * void _showButton(string $name, string $value, string $type = "next")
 *
 * Draws button html tag of type submit.
 *
 * @param string $name name of input field
 * @param string $value button value
 * @param string $type (optional) values: "next" (default), "back"
 * @return void
 * @access private
 */
function _showButton($name, $value, $type = "next")
{
  echo '<button id="' . $name . '" name="' . $name . '" type="submit"';
  echo ' class="' . $type . '"';
  echo ' onclick="document.forms[0].buttonPressed.value=\'' . $name . '\';">';

  if ($type == "next")
  {
    echo '<span>' . $value . '</span>';
    echo '<img src="../images/arrow_right.png" width="22" height="22" />';
  }
  elseif ($type == "back")
  {
    echo '<img src="../images/arrow_left.png" width="22" height="22" />';
    echo '<span>' . $value . '</span>';
  }

  echo "</button>\n";
}

/**
 * string _warnIfExtNotLoaded(string $extensionName, bool $echoWhenOk = false)
 *
 * Checks extension and returns a message
 *
 * @author Christophe Gesché
 * @param string $extensionName name of php extension to be checked
 * @param boolean $echoWhenOk true => show ok when extension exists
 * @return string message (empty or not)
 * @access private
 */
function _warnIfExtNotLoaded($extensionName, $echoWhenOk = false)
{
  $msg = "";

  if (extension_loaded($extensionName))
  {
    if ($echoWhenOk)
    {
      $msg = $extensionName . ' - <strong>' . _("ok") . '</strong>';
    }
  }
  else
  {
    $msg = '<a href="http://www.php.net/' . $extensionName . '"><strong>';
    $msg .= $extensionName . '</strong></a>';
    $msg .= ' - <span class="note">' . _("is missing!!") . '</span>';
  }

  return $msg;
}

/**
 * bool _validateSettings(void)
 *
 * Validates settings of install wizard
 *
 * @return boolean true if all settings are ok, false otherwise
 * @access private
 *
 */
function _validateSettings()
{
  $warning = "";
  if (empty($_POST['dbHost']))
  {
    $warning .= _("Database Host is empty.") . "\n";
  }
  if (empty($_POST['dbUser']))
  {
    $warning .= _("Database User is empty.") . "\n";
  }
  if (empty($_POST['dbName']))
  {
    $warning .= _("Database Name is empty.") . "\n";
  }
  if ($_POST['timeout'] <= 0)
  {
    $warning .= _("Session Timeout must be great than zero.") . "\n";
  }
  if ($_POST['itemsPage'] <= 0)
  {
    $warning .= _("Items per page must be great than zero.") . "\n";
  }
  if (strlen($_POST['passwd']) < 4)
  {
    $warning .= _("Admin password must be at least 4 characters.") . "\n";
  }

  if ( !empty($warning) )
  {
    echo '<h3 class="note">' . _("There were some errors") . "</h3>\n";
    echo '<p class="note">' . nl2br($warning) . "</p>\n";
    return false;
  }
  else
  {
    return true;
  }
}
?>
