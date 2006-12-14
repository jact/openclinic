<?php
/**
 * dump_view_form.php
 *
 * Choice screen of database dump
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: dump_view_form.php,v 1.16 2006/12/14 22:26:57 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "dump";
  $restrictInDemo = true; // To prevent users' malice

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/Dump.php");
  require_once("../lib/Form.php");

  $auxConn = new DbConnection();
  if ( !$auxConn->connect() )
  {
    $auxConn->close();
    Error::connection($auxConn);
  }

  $localQuery = 'SHOW TABLES FROM ' . Dump::backQuote(OPEN_DATABASE);
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
  require_once("../layout/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon dumpIcon");
  unset($links);

  HTML::para(
    HTML::strLink(_("Install dump from file"), '../install/index.php')
    . ' | '
    . HTML::strLink(_("Optimize Database"), '../admin/dump_optimize_db.php')
  );

  if ($numTables < 1)
  {
    // @todo message
    require_once("../layout/footer.php");
    exit();
  }

  HTML::start('script', array('type' => 'text/javascript', 'src' => '../js/dump_functions.js', 'defer' => true));
  HTML::end('script');

  HTML::start('form', array('method' => 'post', 'action' => './dump_process.php'));

  $i = 0;
  $table = null;
  while ($i < $numTables)
  {
    (DUMP_MYSQL_INT_VERSION >= 32303)
      ? $table[$tables[$i]['Name']] = $tables[$i]['Name']
      : $table[$tables[$i]] = $tables[$i];
    $i++;
  }

  $fieldArray = array(
    Form::strSelect("table_select", $table, null, array('size' => 15))
  );
  unset($table);

  $fieldFoot = array(
    HTML::strLink(_("Select all"), '#', null, array('onclick' => "setSelectOptions(0, 'table_select[]', true); return false;"))
    . ' / '
    . HTML::strLink(_("Unselect all"), '#', null, array('onclick' => "setSelectOptions(0, 'table_select[]', false); return false;"))
  );

  Form::fieldset(_("View dump of database"), $fieldArray, $fieldFoot, array('id' => 'dumpTables'));

  $fieldArray = null;

  $fieldArray[] = Form::strRadioButton("what", "data", true,
      array(
        'id' => 'radio_dump_data',
        'onclick' => 'updateChecks(0, new Array(0, 0, 0, 0, 1, 0, 0, 0));'
      )
    )
    . Form::strLabel("radio_dump_data", _("Structure and data"));

  $fieldArray[] = Form::strRadioButton("what", "structure", false,
      array(
        'id' => 'radio_dump_structure',
        'onclick' => 'updateChecks(0, new Array(0, 1, 1, 0, 1, 0, 0, 0));'
      )
    )
    . Form::strLabel("radio_dump_structure", _("Structure only"));

  $fieldArray[] = Form::strRadioButton("what", "dataonly", false,
      array(
        'id' => 'radio_dump_dataonly',
        'onclick' => 'updateChecks(0, new Array(1, 0, 0, 0, 0, 0, 1, 0));'
      )
    )
    . Form::strLabel("radio_dump_dataonly", _("Data only"));

  $fieldArray[] = Form::strRadioButton("what", "xml", false,
      array(
        'id' => 'radio_dump_xml',
        'onclick' => 'updateChecks(0, new Array(1, 1, 1, 1, 1, 1, 1, 0));'
      )
    )
    . Form::strLabel("radio_dump_xml", _("Export to XML format"));

  $fieldArray[] = Form::strRadioButton("what", "excel", false,
      array(
        'id' => 'radio_dump_csv',
        'onclick' => 'updateChecks(0, new Array(1, 1, 1, 1, 1, 1, 1, 0));'
      )
    )
    . Form::strLabel("radio_dump_csv", _("Export to CSV format (data only)"));

  Form::fieldset(_("Options"), $fieldArray, null, array('id' => 'dumpOptions'));

  $fieldArray = array(
    Form::strCheckBox("drop", "yes") . Form::strLabel("drop", _("Add 'DROP TABLE'")),
    Form::strCheckBox("show_columns", "yes") . Form::strLabel("show_columns", _("Complete 'INSERTs'")),
    Form::strCheckBox("extended_inserts", "yes") . Form::strLabel("extended_inserts", _("Extended 'INSERTs'")),
    Form::strCheckBox("use_backquotes", "yes") . Form::strLabel("use_backquotes", _("Enclose table and field names with backquotes")),
    Form::strCheckBox("add_delete", "yes") . Form::strLabel("add_delete", _("Add 'DELETE * FROM __table__'")),
    Form::strCheckBox("use_dbname", "yes") . Form::strLabel("use_dbname", _("Add 'USE __dbname__'")),
    Form::strCheckBox("create_db", "yes") . Form::strLabel("create_db", _("Add 'CREATE DATABASE __dbname__'")),
    Form::strCheckBox("as_file", "sendit") . Form::strLabel("as_file", _("Save as file"))
  );

  Form::fieldset(_("Extended options"), $fieldArray, null, array('id' => 'dumpExtended'));

  HTML::para(
    Form::strButton("dump", _("Submit")) . Form::generateToken(),
    array('class' => 'center')
  );
  HTML::end('form');

  HTML::message(_("Note: Some check options are exclusive. Be carefully!"));

  require_once("../layout/footer.php");
?>
