<?php
/**
 * install.php
 *
 * Installation process screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: install.php,v 1.24 2007/11/02 20:41:23 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  error_reporting(E_ALL & ~E_NOTICE); // normal mode
  //error_reporting(E_ALL); // debug mode

  require_once(dirname(__FILE__) . "/header.php"); // i18n l10n
  require_once(dirname(__FILE__) . "/parse_sql_file.php");
  require_once("../model/Query/Setting.php");
  require_once("../lib/Form.php");

  session_start(); // to keep token_form
  if (isset($_GET["confirm"]) && $_GET["confirm"] == "yes")
  {
    Form::compareToken('./index.php');
  }

  HTML::section(1, _("OpenClinic Installation:"));

  /**
   * Testing connection and current version
   */
  $setQ = new Query_Setting();
  Msg::info(_("Database connection is good."));

  /**
   * Show warning message if database exists
   */
  $setQ->captureError(true);
  $setQ->select();
  if ($setQ->isError())
  {
    HTML::para(_("Building OpenClinic tables..."));
  }
  else
  {
    $set = $setQ->fetch();
    if ( !$set )
    {
      $setQ->close();
      Error::fetch($setQ);
    }

    if ( !isset($_GET["confirm"]) || ($_GET["confirm"] != "yes") )
    {
      HTML::para(sprintf(_("OpenClinic (version %s) is already installed."), $set->getVersion()));
      $setQ->close();

      HTML::rule();

      Msg::warning(_("Are you sure you want to delete all clinic data and create new OpenClinic tables?"));
      Msg::warning(_("If you continue all data will be lost."));

      // @todo use fieldset
      HTML::start('form',
        array(
          'method' => 'post',
          'action' => $_SERVER['PHP_SELF'] . '?confirm=yes'
        )
      );
      HTML::para(
        Form::strButton("continue", _("Continue"))
        . Form::generateToken()
      );
      HTML::end('form');

      HTML::para(HTML::strLink(_("Cancel"), './index.php'));

      include_once(dirname(__FILE__) . "/footer.php");
      exit();
    }
  }

  $setQ->close();
  unset($setQ);
  unset($set);

  /**
   * Creating each table listed in the $tables array
   */
  foreach ($tables as $tableName)
  {
    $result = parseSQLFile("./sql/" . $tableName . ".sql", $tableName, true);

    if ($result)
    {
      $text = sprintf(_("Table %s dropped."), $tableName) . PHP_EOL;
      $text .= sprintf(_("Table %s created."), $tableName) . PHP_EOL;
      $text .= str_repeat(".", 50);
      HTML::para(nl2br($text));
    }
    else
    {
      Msg::error(_("Last instruction failed"));
      exit();
    }
  }

  Msg::info(_("OpenClinic tables have been created successfully!"));

  HTML::section(1, HTML::strLink(_("Start using OpenClinic"), '../home/index.php'));

  require_once(dirname(__FILE__) . "/footer.php");
?>
