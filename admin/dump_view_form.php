<?php
/**
 * dump_view_form.php
 *
 * Choice screen of database dump
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: dump_view_form.php,v 1.22 2008/03/23 11:58:56 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "dump";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR, false); // Not in DEMO to prevent users' malice

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
   * Breadcrumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_dump");
  unset($links);

  echo HTML::para(
    HTML::link(_("Install dump from file"), '../install/index.php')
    . ' | '
    . HTML::link(_("Optimize Database"), '../admin/dump_optimize_db.php')
  );

  if ($numTables < 1)
  {
    // @todo message
    require_once("../layout/footer.php");
    exit();
  }

  echo HTML::insertScript('dump_functions.js');

  echo HTML::start('form', array('method' => 'post', 'action' => './dump_process.php'));

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
    Form::select("table_select", $table, null, array('size' => 15))
  );
  unset($table);

  $fieldFoot = array(
    HTML::link(_("Select all"), '#', null, array('id' => 'select_all')) // @todo created by JS
    . ' / '
    . HTML::link(_("Unselect all"), '#', null, array('id' => 'unselect_all')) // @todo created by JS
  );

  echo Form::fieldset(_("View dump of database"), $fieldArray, $fieldFoot, array('id' => 'dump_tables'));

  $fieldArray = null;

  $fieldArray[] = Form::radioButton("what", "data", array('id' => 'radio_dump_data', 'checked' => true))
    . Form::label("radio_dump_data", _("Structure and data"));

  $fieldArray[] = Form::radioButton("what", "structure", array('id' => 'radio_dump_structure'))
    . Form::label("radio_dump_structure", _("Structure only"));

  $fieldArray[] = Form::radioButton("what", "dataonly", array('id' => 'radio_dump_dataonly'))
    . Form::label("radio_dump_dataonly", _("Data only"));

  $fieldArray[] = Form::radioButton("what", "xml", array('id' => 'radio_dump_xml'))
    . Form::label("radio_dump_xml", _("Export to XML format"));

  $fieldArray[] = Form::radioButton("what", "excel", array('id' => 'radio_dump_csv'))
    . Form::label("radio_dump_csv", _("Export to CSV format (data only)"));

  echo Form::fieldset(_("Options"), $fieldArray, null, array('id' => 'dump_options'));

  $fieldArray = array(
    Form::checkBox("drop", "yes") . Form::label("drop", _("Add 'DROP TABLE'")),
    Form::checkBox("show_columns", "yes") . Form::label("show_columns", _("Complete 'INSERTs'")),
    Form::checkBox("extended_inserts", "yes") . Form::label("extended_inserts", _("Extended 'INSERTs'")),
    Form::checkBox("use_backquotes", "yes") . Form::label("use_backquotes", _("Enclose table and field names with backquotes")),
    Form::checkBox("add_delete", "yes") . Form::label("add_delete", _("Add 'DELETE * FROM __table__'")),
    Form::checkBox("use_dbname", "yes") . Form::label("use_dbname", _("Add 'USE __dbname__'")),
    Form::checkBox("create_db", "yes") . Form::label("create_db", _("Add 'CREATE DATABASE __dbname__'")),
    Form::checkBox("as_file", "sendit") . Form::label("as_file", _("Save as file"))
  );

  echo Form::fieldset(_("Extended options"), $fieldArray, null, array('id' => 'dump_extended'));

  echo HTML::para(
    Form::button("dump", _("Submit")) . Form::generateToken(),
    array('class' => 'center')
  );
  echo HTML::end('form');

  echo Msg::hint(_("Note: Some check options are exclusive. Be carefully!"));

  require_once("../layout/footer.php");
?>
