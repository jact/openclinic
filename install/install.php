<?php
/**
 * install.php
 *
 * Installation process screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2019 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @author    jact <jachavar@gmail.com>
 */

  $tab = "install";
  $nav = "create";

  // Instead of include environment.php (because maybe database connection doesn't exists)
  define("OPEN_THEME_NAME",     "OpenClinic");
  define("OPEN_THEME_CSS_FILE", "openclinic.css");
  require_once("../config/i18n.php");
  require_once("../config/session_info.php");
  require_once("../lib/FlashMsg.php");

  $title = _("Database Creation");
  require_once("../layout/header.php");

  require_once(dirname(__FILE__) . "/parse_sql_file.php");
  require_once("../model/Query/Setting.php");
  require_once("../lib/Form.php");

  if (isset($_GET["confirm"]) && $_GET["confirm"] == "yes")
  {
    Form::compareToken('./index.php');
  }

  echo HTML::section(1, $title);

  /**
   * Testing connection and current version
   */
  $setQ = new Query_Setting();
  echo Msg::info(_("Database connection is good."));

  /**
   * Show warning message if database exists
   */
  $setQ->captureError(true);
  $setQ->select();
  if ($setQ->isError())
  {
    echo HTML::para(_("Building OpenClinic tables..."));
  }
  else
  {
    $set = $setQ->fetch();
    if ( !$set )
    {
      $setQ->close();
      AppError::fetch($setQ);
    }

    if ( !isset($_GET["confirm"]) || ($_GET["confirm"] != "yes") )
    {
      echo HTML::para(sprintf(_("OpenClinic (version %s) is already installed."), $set->getVersion()));
      $setQ->close();

      echo Msg::warning(_("Are you sure you want to delete all clinic data and create new OpenClinic tables?"));
      echo Msg::warning(_("If you continue all data will be lost."));

      // @todo use fieldset
      echo HTML::start('form',
        array(
          'method' => 'post',
          'action' => $_SERVER['PHP_SELF'] . '?confirm=yes'
        )
      );
      echo HTML::para(
        Form::button("continue", _("Continue"))
        . Form::generateToken()
      );
      echo HTML::end('form');

      echo HTML::para(HTML::link(_("Cancel"), './index.php'));

      include_once("../layout/footer.php");
      exit();
    }
  }

  $setQ->close();
  unset($setQ);
  unset($set);

  /**
   * Creating each table listed in the $tables array
   */
  $tables = getTables();
  foreach ($tables as $tableName)
  {
    $result = parseSqlFile("./sql/" . $tableName . ".sql", $tableName, true);

    if ($result)
    {
      $text = sprintf(_("Table %s dropped."), $tableName) . PHP_EOL;
      $text .= sprintf(_("Table %s created."), $tableName) . PHP_EOL;
      $text .= str_repeat(".", 50);
      echo HTML::para(nl2br($text));
    }
    else
    {
      echo Msg::error(_("Last instruction failed"));
      include_once("../layout/footer.php");
      exit();
    }
  }

  echo Msg::info(_("OpenClinic tables have been created successfully!"));

  echo HTML::section(1, HTML::link(_("Start using OpenClinic"), '../home/index.php'));

  require_once("../layout/footer.php");
