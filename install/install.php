<?php
/**
 * install.php
 *
 * Installation process screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: install.php,v 1.16 2006/09/30 16:59:21 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  error_reporting(E_ALL & ~E_NOTICE); // normal mode
  //error_reporting(E_ALL); // debug mode

  require_once("../install/header.php"); // i18n l10n
  require_once("../install/parse_sql_file.php");
  require_once("../classes/Setting_Query.php");
  require_once("../lib/Form.php");

  HTML::section(1, _("OpenClinic Installation:"));

  /**
   * Testing connection and current version
   */
  $setQ = new Setting_Query();
  $setQ->connect();
  HTML::message(_("Database connection is good."), OPEN_MSG_INFO);

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

      HTML::message(
        _("Are you sure you want to delete all clinic data and create new OpenClinic tables?"),
        OPEN_MSG_ERROR
      );
      HTML::message(_("If you continue all data will be lost."));

      // @todo use fieldset
      HTML::start('form',
        array(
          'method' => 'post',
          'action' => $_SERVER['PHP_SELF'] . '?confirm=yes'
        )
      );
      HTML::para(
        Form::strButton("continue", _("Continue"))
        . Form::strButton("cancel", _("Cancel"), "button",
            array('onclick' => "parent.location='../install/cancel_msg.php'")
          )
      );
      HTML::end('form');

      include_once("../install/footer.php");
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
      $text = sprintf(_("Table %s dropped."), $tableName) . "\n";
      $text .= sprintf(_("Table %s created."), $tableName) . "\n";
      $text .= str_repeat(".", 50);
      HTML::para(nl2br($text));
    }
    else
    {
      HTML::message(_("Last instruction failed"), OPEN_MSG_ERROR);
      exit();
    }
  }

  HTML::message(_("OpenClinic tables have been created successfully!"));

  HTML::section(1, HTML::strLink(_("Start using OpenClinic"), '../home/index.php'));

  require_once("../install/footer.php");
?>
