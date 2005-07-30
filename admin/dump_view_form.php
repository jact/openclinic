<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: dump_view_form.php,v 1.9 2005/07/30 18:58:25 jact Exp $
 */

/**
 * dump_view_form.php
 *
 * Choice screen of database dump
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "dump";
  $restrictInDemo = true; // To prevent users' malice

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../admin/dump_defines.php");
  require_once("../lib/dump_lib.php");
  require_once("../lib/Form.php");

  $auxConn = new DbConnection();
  if ( !$auxConn->connect() )
  {
    $auxConn->close();
    Error::connection($auxConn);
  }

  $localQuery = 'SHOW TABLES FROM ' . DLIB_backquote(OPEN_DATABASE);
  if ( !$auxConn->exec($localQuery) )
  {
    $auxConn->close();
    Error::connection($auxConn);
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

  /**
   * Show page
   */
  $title = _("Dumps");
  require_once("../shared/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon dumpIcon");
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
    Form::select("table_select", "table_select", $table, "", 8);
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
            <?php
              Form::radioButton("radio_dump_data", "what", "data", true, false, false, 'onclick="updateChecks(0, new Array(0, 0, 0, 0, 1, 0, 0, 0));"');
              Form::label("radio_dump_data", _("Structure and data"));

              echo "<br />\n";

              Form::radioButton("radio_dump_structure", "what", "structure", false, false, false, 'onclick="updateChecks(0, new Array(0, 1, 1, 0, 1, 0, 0, 0));"');
              Form::label("radio_dump_structure", _("Structure only"));

              echo "<br />\n";

              Form::radioButton("radio_dump_dataonly", "what", "dataonly", false, false, false, 'onclick="updateChecks(0, new Array(1, 0, 0, 0, 0, 0, 1, 0));"');
              Form::label("radio_dump_dataonly", _("Data only"));

              echo "<br />\n";

              Form::radioButton("radio_dump_xml", "what", "xml", false, false, false, 'onclick="updateChecks(0, new Array(1, 1, 1, 1, 1, 1, 1, 0));"');
              Form::label("radio_dump_xml", _("Export to XML format"));

              echo "<br />\n";

              Form::radioButton("radio_dump_csv", "what", "excel", false, false, false, 'onclick="updateChecks(0, new Array(1, 1, 1, 1, 1, 1, 1, 0));"');
              Form::label("radio_dump_csv", _("Export to CSV format (data only)"));
            ?>
          </td>
        </tr>

        <tr>
          <td<?php echo $colspan; ?>>
            <?php
              Form::checkBox("drop", "drop", "yes");
              Form::label("drop", _("Add 'DROP TABLE'"));
            ?>
          </td>
        </tr>

        <tr>
          <td<?php echo $colspan; ?>>
            <?php
              Form::checkBox("show_columns", "show_columns", "yes");
              Form::label("show_columns", _("Complete 'INSERTs'"));
            ?>
          </td>
        </tr>

        <tr>
          <td<?php echo $colspan; ?>>
            <?php
              Form::checkBox("extended_inserts", "extended_inserts", "yes");
              Form::label("extended_inserts", _("Extended 'INSERTs'"));
            ?>
          </td>
        </tr>

        <tr>
          <td<?php echo $colspan; ?>>
            <?php
              Form::checkBox("use_backquotes", "use_backquotes", "yes");
              Form::label("use_backquotes", _("Enclose table and field names with backquotes"));
            ?>
          </td>
        </tr>

        <tr>
          <td<?php echo $colspan; ?>>
            <?php
              Form::checkBox("add_delete", "add_delete", "yes");
              Form::label("add_delete", _("Add 'DELETE * FROM __table__'"));
            ?>
          </td>
        </tr>

        <tr>
          <td<?php echo $colspan; ?>>
            <?php
              Form::checkBox("use_dbname", "use_dbname", "yes");
              Form::label("use_dbname", _("Add 'USE __dbname__'"));
            ?>
          </td>
        </tr>

        <tr>
          <td<?php echo $colspan; ?>>
            <?php
              Form::checkBox("create_db", "create_db", "yes");
              Form::label("create_db", _("Add 'CREATE DATABASE __dbname__'"));
            ?>
          </td>
        </tr>

        <tr>
          <td<?php echo $colspan; ?>>
            <?php
              Form::checkBox("as_file", "as_file", "sendit");
              Form::label("as_file", _("Save as file"));
            ?>
          </td>
        </tr>

        <tr>
          <td<?php echo $colspan; ?> class="center">
            <?php Form::button("button1", "button1", _("Submit")); ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</form>

<?php
  HTML::message(_("Note: Some check options are exclusive. Be carefully!"));

  require_once("../shared/footer.php");
?>
