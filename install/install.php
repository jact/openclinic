<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: install.php,v 1.3 2004/06/16 19:12:04 jact Exp $
 */

/**
 * install.php
 ********************************************************************
 * Installation process screen
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  error_reporting(55); // E_ALL & ~E_NOTICE - normal
  //error_reporting(63); // E_ALL - debug

  require_once("../install/header.php"); // i18n l10n
  require_once("../install/parse_sql_file.php");
  require_once("../classes/Setting_Query.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/error_lib.php");

  echo '<h1>' . _("OpenClinic Installation:") . "</h1>\n";

  // testing connection and current version
  $setQ = new Setting_Query();
  $setQ->connect();
  if ($setQ->errorOccurred())
  {
    showQueryError($setQ);
  }
  echo '<p>' . _("Database connection is good.") . "</p>\n";

  // show warning message if database exists
  $setQ->select();
  if ($setQ->errorOccurred())
  {
    echo '<p>' . _("Building OpenClinic tables...") . "</p>\n";
  }
  else
  {
    $set = $setQ->fetch();
    if ( !isset($_GET["confirm"]) || ($_GET["confirm"] != "yes") )
    {
?>
      <form method="post" action="../install/install.php?confirm=yes">
        <p>
          <?php echo sprintf(_("OpenClinic (version %s) is already installed."), $set->getVersion()); ?>
        </p>

        <p class="error">
          <?php echo _("Are you sure you want to delete all clinic data and create new OpenClinic tables?"); ?>
        </p>

        <p class="note">
          <?php echo _("If you continue all data will be lost."); ?>
        </p>

        <div>
          <?php
            showInputButton("continue", _("Continue"));
            showInputButton("cancel", _("Cancel"), "button", 'onclick="parent.location=\'../install/cancel_msg.php\'"');
          ?>
        </div>
      </form>
<?php
      $setQ->close();
      include_once("../install/footer.php");
      exit();
    }
  }

  $setQ->close();
  unset($setQ);
  unset($set);

  // creating each table listed in the $tables array
  foreach ($tables as $tableName)
  {
    $result = parseSQLFile("sql/" . $tableName . ".sql", $tableName, true);

    if ($result)
    {
      echo sprintf(_("Table %s dropped."), $tableName) . "<br />\n";
      echo sprintf(_("Table %s created."), $tableName) . "<br />\n";
      for ($i = 0; $i < 50; $i++)
      {
        echo '.';
      }
      echo "<br />\n";
    }
    else
    {
      echo '<p class="error">' . _("Last instruction failed") . "</p>\n";
      exit();
    }
  }
?>

<p class="note"><?php echo _("OpenClinic tables have been created successfully!"); ?></p>

<h1><a href="../home/index.php"><?php echo _("Start using OpenClinic"); ?></a></h1>

<?php require_once("../install/footer.php"); ?>
