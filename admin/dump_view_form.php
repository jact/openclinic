<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: dump_view_form.php,v 1.1 2004/03/22 20:10:54 jact Exp $
 */

/**
 * dump_view_form.php
 ********************************************************************
 * Choice screen of database dump
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 22/03/04 21:10
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "dump";
  $restrictInDemo = true; // To prevent users' malice

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../admin/dump_defines.php");
  require_once("../lib/dump_lib.php");
  require_once("../lib/input_lib.php");

  $auxConn = new DbConnection();
  if ( !$auxConn->connect() )
  {
    $auxConn->close();
    showConnError($auxConn);
  }

  $localQuery = 'SHOW TABLES FROM ' . DLIB_backquote(OPEN_DATABASE);
  if ( !$auxConn->exec($localQuery) )
  {
    $auxConn->close();
    showConnError($auxConn);
  }

  $numTables = $auxConn->numRows();
  if ($numTables > 0)
  {
    while ($tmp = $auxConn->fetchRow(MYSQL_NUM))
    {
      $tables[] = array('Name' => htmlspecialchars($tmp[0]));
    }
  }
  $auxConn->freeResult();
  $auxConn->close();
  unset($auxConn);

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Dumps");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  showNavLinks($links, "dumps.png");
  unset($links);

  echo '<p>';
  echo '<a href="../install/index.php">' . _("Install dump from file") . '</a>';
  echo ' | <a href="../admin/dump_optimize_db.php">' . _("Optimize Database") . '</a>';
  echo "</p>\n";
?>

<script type="text/javascript" src="../scripts/dump_functions.js" defer="defer"></script>

<form method="post" action="./dump_process.php">
  <div>
    <table>
      <thead>
<?php
  $colspan = '';
  if ($numTables > 1)
  {
    $colspan = ' colspan="2"';
?>
        <tr>
          <th<?php echo $colspan; ?>>
            <?php echo _("View dump of database"); ?>
          </th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td class="center">
<?php
    $i = 0;
    $table = null;
    while ($i < $numTables)
    {
      (DLIB_MYSQL_INT_VERSION >= 32303)
        ? $table[$tables[$i]['Name']] = $tables[$i]['Name']
        : $table[$tables[$i]] = $tables[$i];
      $i++;
    } // end while
    showSelectArray("table_select", $table, "", 8);
    unset($table);
?>

            <br />

            <a href="#" onclick="setSelectOptions(0, 'table_select[]', true); return false;"><?php echo _("Select all"); ?></a>

            &nbsp;/&nbsp;

            <a href="#" onclick="setSelectOptions(0, 'table_select[]', false); return false;"><?php echo _("Unselect all"); ?></a>
          </td>
<?php
  } // end if
  echo "\n";
?>
          <td>
            <?php showRadioButton("radio_dump_data", "what", "data", true, false, false, 'onclick="updateChecks(0, new Array(0, 0, 0, 0, 1, 0, 0));"'); ?>
            <label for="radio_dump_data"><?php echo _("Structure and data"); ?></label>

            <br />

            <?php showRadioButton("radio_dump_structure", "what", "structure", false, false, false, 'onclick="updateChecks(0, new Array(0, 1, 1, 0, 1, 0, 0));"'); ?>
            <label for="radio_dump_structure"><?php echo _("Structure only"); ?></label>

            <br />

            <?php showRadioButton("radio_dump_dataonly", "what", "dataonly", false, false, false, 'onclick="updateChecks(0, new Array(1, 0, 0, 0, 0, 0, 0));"'); ?>
            <label for="radio_dump_dataonly"><?php echo _("Data only"); ?></label>

            <br />

            <?php showRadioButton("radio_dump_xml", "what", "xml", false, false, false, 'onclick="updateChecks(0, new Array(1, 1, 1, 1, 1, 1, 0));"'); ?>
            <label for="radio_dump_xml"><?php echo _("Export to XML format"); ?></label>

            <br />

            <?php showRadioButton("radio_dump_csv", "what", "excel", false, false, false, 'onclick="updateChecks(0, new Array(1, 1, 1, 1, 1, 1, 0));"'); ?>
            <label for="radio_dump_csv"><?php echo _("Export to CSV format (data only)"); ?></label>
          </td>
        </tr>

        <tr>
          <td<?php echo $colspan; ?>>
            <?php showCheckBox("drop", "drop", "1"); ?>
            <label for="drop"><?php echo _("Add 'DROP TABLE'"); ?></label>
          </td>
        </tr>

        <tr>
          <td<?php echo $colspan; ?>>
            <?php showCheckBox("show_columns", "show_columns", "yes"); ?>
            <label for="show_columns"><?php echo _("Complete 'INSERTs'"); ?></label>
          </td>
        </tr>

        <tr>
          <td<?php echo $colspan; ?>>
            <?php showCheckBox("extended_inserts", "extended_inserts", "yes"); ?>
            <label for="extended_inserts"><?php echo _("Extended 'INSERTs'"); ?></label>
          </td>
        </tr>

        <tr>
          <td<?php echo $colspan; ?>>
            <?php showCheckBox("use_backquotes", "use_backquotes", "1"); ?>
            <label for="use_backquotes"><?php echo _("Enclose table and field names with backquotes"); ?></label>
          </td>
        </tr>

        <tr>
          <td<?php echo $colspan; ?>>
            <?php showCheckBox("add_delete", "add_delete", "yes"); ?>
            <label for="add_delete"><?php echo _("Add 'DELETE * FROM __table__'"); ?></label>
          </td>
        </tr>

        <tr>
          <td<?php echo $colspan; ?>>
            <?php showCheckBox("use_dbname", "use_dbname", "yes"); ?>
            <label for="use_dbname"><?php echo _("Add 'USE __dbname__'"); ?></label>
          </td>
        </tr>

        <tr>
          <td<?php echo $colspan; ?>>
            <?php showCheckBox("as_file", "as_file", "sendit"); ?>
            <label for="as_file"><?php echo _("Save as file"); ?></label>
          </td>
        </tr>

        <tr>
          <td<?php echo $colspan; ?> class="center">
            <?php showInputButton("button1", _("Submit")); ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</form>

<?php require_once("../shared/footer.php"); ?>
