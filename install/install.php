<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: install.php,v 1.15 2006/03/25 20:00:43 jact Exp $
 */

/**
 * install.php
 *
 * Installation process screen
 *
 * @author jact <jachavar@gmail.com>
 */

  error_reporting(E_ALL & ~E_NOTICE); // normal mode
  //error_reporting(E_ALL); // debug mode

  require_once("../install/header.php"); // i18n l10n
  require_once("../install/parse_sql_file.php");
  require_once("../classes/Setting_Query.php");
  require_once("../lib/Form.php");

  echo '<h1>' . _("OpenClinic Installation:") . "</h1>\n";

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
    echo '<p>' . _("Building OpenClinic tables...") . "</p>\n";
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
      echo '<p>' . sprintf(_("OpenClinic (version %s) is already installed."), $set->getVersion()) . "</p>\n";
      $setQ->close();

      echo "<hr />\n";

      echo '<p class="error">' . _("Are you sure you want to delete all clinic data and create new OpenClinic tables?") . "</p>\n";
      echo '<p class="note">' . _("If you continue all data will be lost.") . "</p>\n";

      // @todo use fieldset
      echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?confirm=yes">' . "\n";
      echo '<p>';
      Form::button("continue", _("Continue"));
      Form::button("cancel", _("Cancel"), "button", array('onclick' => "parent.location='../install/cancel_msg.php'"));
      echo "</p>\n";
      echo "</form>\n";

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
      echo sprintf(_("Table %s dropped."), $tableName) . "<br />\n";
      echo sprintf(_("Table %s created."), $tableName) . "<br />\n";
      echo str_repeat(".", 50) . "<br />\n";
    }
    else
    {
      HTML::message(_("Last instruction failed"), OPEN_MSG_ERROR);
      exit();
    }
  }

  HTML::message(_("OpenClinic tables have been created successfully!"));

  echo '<h1>' . HTML::strLink(_("Start using OpenClinic"), '../home/index.php') . "</h1>\n";

  require_once("../install/footer.php");
?>
