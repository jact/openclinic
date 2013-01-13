<?php
/**
 * wizard.php
 *
 * OpenClinic Install Wizard
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: wizard.php,v 1.38 2013/01/13 14:17:56 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.5
 */

/**
 * Functions:
 *  void _showButton(string $name, string $value, string $type = "next")
 *  string _warnIfExtNotLoaded(string $extensionName, bool $echoWhenOk = false)
 *  bool _validateSettings(void)
 */

  define("OPEN_PHP_VERSION", '5.3.1'); // @fixme in global_constants.php

  error_reporting(E_ALL & ~E_NOTICE); // normal mode
  //error_reporting(E_ALL); // debug mode

  /**
   * Step 8: If we have concluded...
   */
  if (isset($_POST['buttonPressed']) && $_POST['buttonPressed'] == "next7")
  {
    header("Location: ../index.php");
    exit();
  } // end step 8

  require_once("../lib/Form.php");
  require_once("../lib/Msg.php");
  require_once("../lib/Error.php");
  require_once("../lib/Check.php");

  $themes = array(
    1 => "OpenClinic"
  );

  /**
   * Step 0: Variables initialization if first visit
   */
  if ( !isset($_POST['alreadyVisited']) || !$_POST['alreadyVisited'] )
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
    $_POST['clinicTheme'] = 1; // OpenClinic theme by default

    $_POST['firstName'] = "John";
    $_POST['surname1'] = "Doe";
    $_POST['surname2'] = "Smith";
    $_POST['adminAddress'] = "";
    $_POST['adminPhone'] = "";
    $_POST['passwd'] = "";
    $_POST['email'] = "";
    $_POST['adminTheme'] = 1; // OpenClinic theme by default
  } // end step 0

  /**
   * i18n l10n
   */
  require_once("../config/i18n.php");

  $locale = I18n::languageList();
  // end i18n l10n

  /**
   * XHTML Start (XML prolog, DOCTYPE, title page and meta data)
   */
  $title = _("OpenClinic Install Wizard");
  require_once("../layout/xhtml_start.php");

  echo HTML::start('link', array('rel' => 'stylesheet', 'href' => '../css/wizard.css', 'type' => 'text/css'), true);

  echo HTML::insertScript('wizard.js');

  echo HTML::end('head');
  echo HTML::start('body');
  //echo "<!-- Header -->\n";

  echo HTML::start('form',
    array(
      'method' => 'post',
      'action' => $_SERVER['PHP_SELF'],
      'onsubmit' => 'return validateInstall();'
    )
  );
  //Error::debug($_POST);
  $_POST = Check::safeArray($_POST);

  echo Form::hidden("alreadyVisited", 1, array('id' => 'h_alreadyVisited'));
  echo Form::hidden("buttonPressed", null, array('id' => 'h_buttonPressed'));

  echo Form::hidden("dbHost", preg_replace("/ /", "", $_POST['dbHost']), array('id' => 'h_dbHost'));
  echo Form::hidden("dbUser", preg_replace("/ /", "", $_POST['dbUser']), array('id' => 'h_dbUser'));
  echo Form::hidden("dbPasswd", $_POST['dbPasswd'], array('id' => 'h_dbPasswd'));
  echo Form::hidden("dbName", preg_replace("/ /", "", $_POST['dbName']), array('id' => 'h_dbName'));

  echo Form::hidden("clinicLanguage", $_POST['clinicLanguage'], array('id' => 'h_clinicLanguage'));
  echo Form::hidden("clinicName", $_POST['clinicName'], array('id' => 'h_clinicName'));
  echo Form::hidden("clinicHours", $_POST['clinicHours'], array('id' => 'h_clinicHours'));
  echo Form::hidden("clinicAddress", $_POST['clinicAddress'], array('id' => 'h_clinicAddress'));
  echo Form::hidden("clinicPhone", $_POST['clinicPhone'], array('id' => 'h_clinicPhone'));
  echo Form::hidden("timeout", intval($_POST['timeout']), array('id' => 'h_timeout'));
  echo Form::hidden("itemsPage", intval($_POST['itemsPage']), array('id' => 'h_itemsPage'));
  echo Form::hidden("clinicTheme", $_POST['clinicTheme'], array('id' => 'h_clinicTheme'));

  echo Form::hidden("firstName", $_POST['firstName'], array('id' => 'h_firstName'));
  echo Form::hidden("surname1", $_POST['surname1'], array('id' => 'h_surname1'));
  echo Form::hidden("surname2", $_POST['surname2'], array('id' => 'h_surname2'));
  echo Form::hidden("adminAddress", $_POST['adminAddress'], array('id' => 'h_adminAddress'));
  echo Form::hidden("adminPhone", $_POST['adminPhone'], array('id' => 'h_adminPhone'));
  echo Form::hidden("passwd", $_POST['passwd'], array('id' => 'h_passwd'));
  echo Form::hidden("email", preg_replace("/ /", "", $_POST['email']), array('id' => 'h_email'));
  echo Form::hidden("adminTheme", $_POST['adminTheme'], array('id' => 'h_adminTheme'));

  echo HTML::start('div', array('id' => 'window'));
  echo HTML::section(1, _("OpenClinic Install Wizard"));
  //echo "<!-- End Header -->\n";

  /**
   * Step 2: License
   */
  if (isset($_POST['buttonPressed']) && ($_POST['buttonPressed'] == "next1" || $_POST['buttonPressed'] == "back2"))
  {
    $focusFormField = "license";
    //Error::debug(OPEN_LANGUAGE);

    echo HTML::section(2, sprintf(_("Step %d of %d: "), 2, 7) . _("License"));

    echo HTML::para(_("OpenClinic is free software, distributed under GNU General Public License (GPL). Please read the license and, if you agree, click on 'I accept' button."));

    echo HTML::para(
      Form::textArea("license", file_get_contents("../LICENSE"),
        array(
          'rows' => 15,
          'cols' => 75
        )
      ),
      array('class' => 'center')
    );

    echo HTML::start('div', array('id' => 'buttons'));
    _showButton("back1", _("Back"), "back");
    _showButton("next2", _("I accept"));
    echo HTML::end('div');

    echo HTML::para(_("Read the license before continue"), array('id' => 'status'));
  } // end step license

  /**
   * Step 3: MySQL database settings
   */
  elseif (isset($_POST['buttonPressed']) && ($_POST['buttonPressed'] == "next2" || $_POST['buttonPressed'] == "back3"))
  {
    $focusFormField = "dbHost[1]";

    echo HTML::section(2, sprintf(_("Step %d of %d: "), 3, 7) . _("MySQL Database Settings"));

    echo HTML::para(sprintf(_("Install script create OpenClinic database. These following values will be written in %s file. All fields are required."), HTML::tag('code', 'config/database_constants.php')));

    $tbody = array();

    $tbody[] = array(
      Form::label("dbHost", _("Database Host:")),
      Form::text("dbHost", $_POST['dbHost'],
        array(
          'size' => 25,
          'maxlength' => 100
        )
      ),
      _("e. g.") . " localhost"
    );

    $tbody[] = array(
      Form::label("dbUser", _("Database User:")),
      Form::text("dbUser", $_POST['dbUser'],
        array(
          'size' => 25,
          'maxlength' => 100
        )
      ),
      _("e. g.") . " openclinic_user"
    );

    $tbody[] = array(
      Form::label("dbPasswd", _("Database Password:")),
      Form::text("dbPasswd", $_POST['dbPasswd'],
        array(
          'size' => 25,
          'maxlength' => 100
        )
      ),
      _("e. g.") . " s45gh72cv"
    );

    $tbody[] = array(
      Form::label("dbName", _("Database Name:")),
      Form::text("dbName", $_POST['dbName'],
        array(
          'size' => 25,
          'maxlength' => 100
        )
      ),
      _("e. g.") . " openclinic"
    );

    $thead = array(/*_("MySQL Database Settings")*/'' => array('colspan' => 3));

    echo HTML::table($thead, $tbody, null, array('align' => 'center'));

    echo HTML::start('div', array('id' => 'buttons'));
    _showButton("back2", _("Back"), "back");
    _showButton("next3", _("Next"));
    echo HTML::end('div');

    echo HTML::para(_("Check data before continue"), array('id' => 'status'));
  } // end step mysql

  /**
   * Step 4: Config Settings
   */
  elseif (isset($_POST['buttonPressed']) && ($_POST['buttonPressed'] == "next3" || $_POST['buttonPressed'] == "back4"))
  {
    $focusFormField = "clinicLanguage[1]";

    echo HTML::section(2, sprintf(_("Step %d of %d: "), 4, 7) . _("Config Settings"));

    echo HTML::para(_("These are OpenClinic config settings."));

    $tbody = array();

    $tbody[] = array(
      Form::label("clinicLanguage", _("Language") . ":"),
      Form::select("clinicLanguage", $locale, $_POST['clinicLanguage'])
    );

    $tbody[] = array(
      Form::label("clinicName", _("Clinic Name") . ":"),
      Form::text("clinicName", $_POST['clinicName'],
        array(
          'size' => 30,
          'maxlength' => 128
        )
      ),
      _("e. g.") . " My Clinic"
    );

    $tbody[] = array(
      Form::label("clinicHours", _("Clinic Hours") . ":"),
      Form::text("clinicHours", $_POST['clinicHours'],
        array(
          'size' => 30,
          'maxlength' => 128
        )
      ),
      _("e. g.") . " L-V 9am-5pm"
    );

    $tbody[] = array(
      Form::label("clinicAddress", _("Clinic Address") . ":"),
      Form::text("clinicAddress", $_POST['clinicAddress'],
        array(
          'size' => 30,
          'maxlength' => 200
        )
      ),
      _("e. g.") . " Sesame Street"
    );

    $tbody[] = array(
      Form::label("clinicPhone", _("Clinic Phone") . ":"),
      Form::text("clinicPhone", $_POST['clinicPhone'],
        array(
          'size' => 30,
          'maxlength' => 40
        )
      ),
      _("e. g.") . " 999 66 66 66"
    );

    $tbody[] = array(
      Form::label("timeout", _("Session Timeout (minutes):")),
      Form::text("timeout", $_POST['timeout'],
        array(
          'size' => 30,
          'maxlength' => 3
        )
      ),
      _("e. g.") . " 20"
    );

    $tbody[] = array(
      Form::label("itemsPage", _("Search Results (items per page):")),
      Form::text("itemsPage", $_POST['itemsPage'],
        array(
          'size' => 30,
          'maxlength' => 2
        )
      ),
      _("e. g.") . " 10"
    );

    $tbody[] = array(
      Form::label("clinicTheme", _("Theme by default") . ":"),
      Form::select("clinicTheme", $themes, $_POST['clinicTheme'])
    );

    $thead = array(/*_("Config Settings")*/'' => array('colspan' => 3));

    echo HTML::table($thead, $tbody, null, array('align' => 'center'));

    echo HTML::start('div', array('id' => 'buttons'));
    _showButton("back3", _("Back"), "back");
    _showButton("next4", _("Next"));
    echo HTML::end('div');

    echo HTML::para(_("Customize your site"), array('id' => 'status'));
  } // end step config settings

  /**
   * Step 5: Admin data
   */
  elseif (isset($_POST['buttonPressed']) && ($_POST['buttonPressed'] == "next4" || $_POST['buttonPressed'] == "back5"))
  {
    $focusFormField = "firstName[1]";

    echo HTML::section(2, sprintf(_("Step %d of %d: "), 5, 7) . _("Admin Data"));

    $tbody = array();

    $tbody[] = array(
      Form::label("firstName", _("First Name") . ":"),
      Form::text("firstName", $_POST['firstName'],
        array(
          'size' => 30,
          'maxlength' => 25
        )
      ),
      _("e. g.") . " John"
    );

    $tbody[] = array(
      Form::label("surname1", _("Surname 1") . ":"),
      Form::text("surname1", $_POST['surname1'], array('size' => 30)),
      _("e. g.") . " Doe"
    );

    $tbody[] = array(
      Form::label("surname2", _("Surname 2") . ":"),
      Form::text("surname2", $_POST['surname2'], array('size' => 30)),
      _("e. g.") . " Smith"
    );

    $tbody[] = array(
      Form::label("adminAddress", _("Address") . ":"),
      Form::text("adminAddress", $_POST['adminAddress'],
        array(
          'size' => 30,
          'maxlength' => 200
        )
      )
    );

    $tbody[] = array(
      Form::label("adminPhone", _("Phone Contact") . ":"),
      Form::text("adminPhone", $_POST['adminPhone'],
        array(
          'size' => 30,
          'maxlength' => 40
        )
      )
    );

    $tbody[] = array(
      _("Login"),
      HTML::tag('span', "admin", array('class' => 'note'))
    );

    $tbody[] = array(
      Form::label("passwd", _("Password") . ":"),
      Form::text("passwd", $_POST['passwd'],
        array(
          'size' => 30,
          'maxlength' => 20
        )
      ),
      HTML::tag('span', _("required"), array('class' => 'note'))
    );

    $tbody[] = array(
      Form::label("email", _("Email") . ":"),
      Form::text("email", $_POST['email'],
        array(
          'size' => 30,
          'maxlength' => 40
        )
      )
    );

    $tbody[] = array(
      Form::label("adminTheme", _("Theme") . ":"),
      Form::select("adminTheme", $themes, $_POST['adminTheme'])
    );

    $thead = array(/*_("Admin Data")*/'' => array('colspan' => 3));

    echo HTML::table($thead, $tbody, null, array('align' => 'center'));

    echo HTML::start('div', array('id' => 'buttons'));
    _showButton("back4", _("Back"), "back");
    _showButton("next5", _("Next"));
    echo HTML::end('div');

    echo HTML::para(_("Password is the most important field"), array('id' => 'status'));
  } // end step admin data

  /**
   * Step 6: Last Check
   */
  elseif (isset($_POST['buttonPressed']) && $_POST['buttonPressed'] == "next5")
  {
    //$focusFormField = "back5";

    echo HTML::section(2, sprintf(_("Step %d of %d: "), 6, 7) . _("Last Check before Install"));

    echo HTML::para(
      sprintf(_("Here are the values you entered. %s to remember your admin password and other settings."),
        HTML::link(_("Print this page"), '#', null, array('onclick' => 'window.print(); return false;'))
      )
    );

    $continue = _validateSettings();

    echo HTML::section(3, _("MySQL Database Settings"));

    echo HTML::start('blockquote');
    echo _("Database Host:") . ' ' . $_POST['dbHost'] . "<br />";
    echo _("Database User:") . ' ' . $_POST['dbUser'] . "<br />";
    echo _("Database Password:") . ' ' . $_POST['dbPasswd'] . "<br />";
    echo _("Database Name:") . ' ' . $_POST['dbName'];
    echo HTML::end('blockquote');

    echo HTML::section(3, _("Config Settings"));

    echo HTML::start('blockquote');
    echo _("Language") . ': ' . $locale[$_POST['clinicLanguage']] . "<br />";
    echo _("Clinic Name") . ': ' . $_POST['clinicName'] . "<br />";
    echo _("Clinic Hours") . ': ' . $_POST['clinicHours'] . "<br />";
    echo _("Clinic Address") . ': ' . $_POST['clinicAddress'] . "<br />";
    echo _("Clinic Phone") . ': ' . $_POST['clinicPhone'] . "<br />";
    echo _("Session Timeout") . ': ' . $_POST['timeout'] . "<br />";
    echo _("Search Results") . ': ' . $_POST['itemsPage'] . "<br />";
    echo _("Theme by default") . ': ' . $themes[$_POST['clinicTheme']];
    echo HTML::end('blockquote');

    echo HTML::section(3, _("Admin Data"));

    echo HTML::start('blockquote');
    echo _("First Name") . ': ' . $_POST['firstName'] . "<br />";
    echo _("Surname 1") . ': ' . $_POST['surname1'] . "<br />";
    echo _("Surname 2") . ': ' . $_POST['surname2'] . "<br />";
    echo _("Address") . ': ' . $_POST['adminAddress'] . "<br />";
    echo _("Phone Contact") . ': ' . $_POST['adminPhone'] . "<br />";
    echo _("Login") . ': ' . HTML::tag('span', "admin", array('class' => 'note')) . "<br />";
    echo _("Password") . ': ' . HTML::tag('span', $_POST['passwd'], array('class' => 'note')) . "<br />";
    echo _("Email") . ': ' . $_POST['email'] . "<br />";
    echo _("Theme") . ': ' . $themes[$_POST['adminTheme']];
    echo HTML::end('blockquote');

    echo HTML::start('div', array('id' => 'buttons'));
    _showButton("back5", _("Back"), "back");
    if ($continue)
    {
      _showButton("next6", _("Install"));
    }
    echo HTML::end('div');

    echo HTML::para(_("If you are not sure in any value, go back before install"), array('id' => 'status'));
  } // end step last check

  /**
   * Step 7: Start using OpenClinic
   */
  elseif (isset($_POST['buttonPressed']) && $_POST['buttonPressed'] == "next6")
  {
    $focusFormField = "next7";

    echo HTML::section(2, sprintf(_("Step %d of %d: "), 7, 7) . _("Start using OpenClinic"));

    /**
     * Write config/database_constants.php file
     */
    $mode = (preg_match('/win/i', $_SERVER["HTTP_USER_AGENT"])) ? 'wb' : 'w';
    $aux = fopen('../config/database_constants.php', $mode);
    if ( !$aux )
    {
      echo Msg::error(
        sprintf(
          _("Incorrect permissions of %s file. Continue is impossible."),
          HTML::tag('tt', "config/database_constants.php")
        )
      );
      exit();
    }

    $fileContent = '<?php
/**
 * database_constants.php
 *
 * Definition of database connection variables
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-' . date('Y') . ' jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: wizard.php,v 1.38 2013/01/13 14:17:56 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

/**
 * A T T E N T I O N !
 *
 * Please modify the following database connection variables to match
 * the MySQL database and user that you have created for OpenClinic.
 */
  define("OPEN_HOST",       "' . $_POST['dbHost'] . '");
  define("OPEN_DATABASE",   "' . $_POST['dbName'] . '");
  define("OPEN_USERNAME",   "' . $_POST['dbUser'] . '");
  define("OPEN_PWD",        "' . $_POST['dbPasswd'] . '");
  define("OPEN_PERSISTENT", true);
?>
';
    if ( !fwrite($aux, $fileContent) )
    {
      echo Msg::error(_("Error in writing proccess. Continue is impossible."));
      exit();
    }
    fclose($aux);
    flush();

    /**
     * This is needed to really flush file contents
     */
    $aux = fopen('../config/database_constants.php', 'r');
    fread($aux, filesize('../config/database_constants.php'));
    fclose($aux);

    /**
     * Database connection
     */
    $db = @mysql_connect($_POST['dbHost'], $_POST['dbUser'], $_POST['dbPasswd']);
    if (mysql_errno() > 0) // problem with server
    {
      $no = mysql_errno();
      $msg = mysql_error();

      echo HTML::rule();
      echo HTML::para($no . PHP_EOL . $msg);
      echo HTML::rule();

      echo HTML::para(_("The MySQL server does not work or login pass is false."));
      echo HTML::para(_("Please check these values and if any is wrong back to step 2:"));

      $array = array(
        _("Database Host:") . ' ' . $_POST['dbHost'],
        _("Database User:") . ' ' . $_POST['dbUser'],
        _("Database Password:") . ' ' . $_POST['dbPasswd']
      );
      echo HTML::itemList($array);

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
    require_once(dirname(__FILE__) . "/parse_sql_file.php");

    $tables = getTables();
    foreach ($tables as $tableName)
    {
      $result = parseSqlFile("./sql/" . $tableName . ".sql", $tableName, true);

      if ($result)
      {
        $text = sprintf(_("Table %s dropped."), $tableName) . PHP_EOL;
        $text .= sprintf(_("Table %s created."), $tableName);
        echo HTML::para($text);
      }
      else
      {
        echo Msg::error(_("Last instruction failed"));
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

    echo HTML::para(_("OpenClinic tables have been created successfully!"));
    echo HTML::para(
      sprintf(_("%s: To protect your site, make %s and %s files read only (chmod 400)."),
        HTML::tag('span', _("Security advice"), array('class' => 'note')),
        HTML::tag('tt', 'config/database_constants.php'),
        HTML::tag('tt', 'openclinic/install/')
      )
    );

    echo HTML::start('div', array('id' => 'buttons'));
    _showButton("next7", _("Start OpenClinic"));
    echo HTML::end('div');

    echo HTML::para(_("That's folks!!!"), array('id' => 'status'));
  } // end step 7

  /**
   * Step 1: Requirements
   */
  else
  {
    //$focusFormField = "next1";

    echo HTML::section(2, sprintf(_("Step %d of %d: "), 1, 7) . _("Requirements"));

    echo HTML::para(
      sprintf(_("This script will configure OpenClinic database and %s file."),
        HTML::tag('tt', 'config/database_constants.php')
      )
    );

    echo HTML::start('blockquote', array('class' => 'error'));
    echo sprintf(_("The file %s already exists in your system! This script INSTALLS and DOES NOT UPGRADE. It will remove all OpenClinic data. If this is what you want, you can go on. If you want upgrade go to the %s."),
      HTML::tag('tt', 'config/database_constants.php'),
      HTML::link(_("upgrade script"), './index.php')
    );
    echo HTML::end('blockquote');

    echo HTML::section(3, _("Read Thouroughly") . ":");

    echo HTML::para(_("For OpenClinic to work, you need the following on the server:"));

    $itemArray = array(
      sprintf(_("Webserver (you have %s)"), HTML::tag('strong', $_SERVER['SERVER_SOFTWARE']))
    );

    $text = sprintf(_("PHP >= %s (you have PHP %s)"), OPEN_PHP_VERSION, HTML::tag('strong', phpversion()));
    if (version_compare(phpversion(), OPEN_PHP_VERSION) >= 0)
    {
      $text .= ' - ';
      $text .= HTML::tag('strong', _("ok"));
    }
    else
    {
      $text .= ' - ';
      $text .= HTML::tag('strong', _("error"), array('class' => 'error'));
      $notPHP = true;
    }

    $extArray = array(
      _warnIfExtNotLoaded("standard", true),
      _warnIfExtNotLoaded("session", true),
      _warnIfExtNotLoaded("mysql", true),
      _warnIfExtNotLoaded("pcre", true),
      _warnIfExtNotLoaded("gettext", true)
    );
    $text .= HTML::itemList($extArray);
    unset($extArray);

    $itemArray[] = $text;
    unset($text);

    $itemArray[] = 'MySQL >= 3.23.36';

    $text =sprintf(_("Write access to %s file"), HTML::tag('tt', 'config/database_constants.php'));
    if (chmod("../config/database_constants.php", 0666) == false)
    {
      $text .= HTML::para(_("This file is still not web writeable. This is good in normal time, for security reasons. However, during this installation, you need to set this file with writing permissions. You will have to do it manually."));
      if (defined('PHP_OS') && preg_match('/win/i', PHP_OS))
      {
        $text .= Msg::error(sprintf(_("Deactivate read-only mode in the file properties of %s."),
          HTML::tag('tt', 'config/database_constants.php'))
        );
      }
      else
      {
        $text .= Msg::error(sprintf(_("Open a shell, go to %s directory and type %s."),
            HTML::tag('tt', 'openclinic'),
            HTML::tag('tt', sprintf("chmod 666 %s", "config/database_constants.php"))
          )
        );
      }
      $text .= HTML::para(_("Once made it, click in Next button."));
    }
    else
    {
      $text .= ' - ';
      $text .= HTML::tag('strong', _("ok"));
    }
    $itemArray[] = $text;
    unset($text);

    echo HTML::itemList($itemArray);
    unset($itemArray);

    echo HTML::para(
      sprintf(
        _("To install OpenClinic on a Windows machine for test, you just need to install %s before."),
        HTML::link("AppServ", 'http://academic.cmri.ac.th/appserv')
      )
    );

    echo HTML::para(
      sprintf(
        _("For more details, see %s."),
        HTML::link(_("Install Instructions"), '../install.html')
      )
    );

    if ( !isset($notPHP) )
    {
      echo HTML::start('div', array('id' => 'buttons'));
      _showButton("next1", _("Next"));
      echo HTML::end('div');

      echo HTML::para(_("Press Next button to continue"), array('id' => 'status'));
    }
  } // end step 1: requirements

  //echo "<!-- Footer -->\n";
  echo HTML::end('div'); // #window
  echo HTML::end('form');

  if (isset($focusFormField) && ( !empty($focusFormField) ))
  {
    echo HTML::insertScript('event.js');

    echo HTML::start('script', array('src' => '../js/focus.php?field=' . $focusFormField, 'type' => 'text/javascript'));
    echo HTML::end('script');
  }

  echo HTML::end('body');
  echo HTML::end('html');
  //echo "<!-- End Footer -->\n";

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
 * @todo helper that returns string (not echo)
 */
function _showButton($name, $value, $type = "next")
{
  $_html = HTML::start('button',
    array(
      'id' => $name,
      'name' => $name,
      'type' => 'submit',
      'class' => $type,
      'onclick' => 'document.forms[0].buttonPressed.value=\'' . $name . '\';'
    )
  );

  if ($type == "next")
  {
    $_html .= HTML::tag('span', $value);
    $_html .= HTML::image('../img/arrow_right.png', $value, array('width' => 22, 'height' => 22));
  }
  elseif ($type == "back")
  {
    $_html .= HTML::image('../img/arrow_left.png', $value, array('width' => 22, 'height' => 22));
    $_html .= HTML::tag('span', $value);
  }

  $_html .= HTML::end('button');

  echo $_html;
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
      $msg = $extensionName . ' - ' . HTML::tag('strong', _("ok"));
    }
  }
  else
  {
    $msg = HTML::link(HTML::tag('strong', $extensionName), 'http://www.php.net/' . $extensionName);
    $msg .= ' - ' . HTML::tag('strong', _("is missing!!"), array('class' => 'error'));
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
  $error = "";
  if (empty($_POST['dbHost']))
  {
    $error .= _("Database Host is empty.") . PHP_EOL;
  }
  if (empty($_POST['dbUser']))
  {
    $error .= _("Database User is empty.") . PHP_EOL;
  }
  if (empty($_POST['dbName']))
  {
    $error .= _("Database Name is empty.") . PHP_EOL;
  }
  if ($_POST['timeout'] <= 0)
  {
    $error .= _("Session Timeout must be great than zero.") . PHP_EOL;
  }
  if ($_POST['itemsPage'] <= 0)
  {
    $error .= _("Items per page must be great than zero.") . PHP_EOL;
  }
  if (strlen($_POST['passwd']) < 4)
  {
    $error .= _("Admin password must be at least 4 characters.") . PHP_EOL;
  }

  if ( !empty($error) )
  {
    echo HTML::section(3, _("There were some errors"), array('class' => 'error'));
    echo Msg::error($error);
  }

  return empty($error);
}
?>
