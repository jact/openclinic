<?php
/**
 * dump_optimize_db.php
 *
 * Optimization screen of database
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: dump_optimize_db.php,v 1.18 2008/03/23 11:58:56 jact Exp $
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

  @set_time_limit(OPEN_EXEC_TIME_LIMIT);

  $auxConn = new DbConnection();
  if ( !$auxConn->connect() )
  {
    $auxConn->close();
    Error::connection($auxConn);
  }

  $localQuery = 'SHOW TABLE STATUS FROM ' . Dump::backQuote(OPEN_DATABASE);
  if ( !$auxConn->exec($localQuery) )
  {
    $auxConn->close();
    Error::connection($auxConn);
  }

  /**
   * Show page
   */
  $title = _("Optimize Database");
  require_once("../layout/header.php");

  /**
   * Breadcrumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Dumps") => "../admin/dump_view_form.php",
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_dump");
  unset($links);

  echo HTML::section(2, sprintf(_("Optimizing Database: %s"), OPEN_DATABASE));

  $numTables = $auxConn->numRows();
  if ( !$numTables )
  {
    $auxConn->close();

    echo Msg::error(_("Database is empty."));
    include_once("../layout/footer.php");
    exit();
  }

  $thead = array(
    _("Table Name"),
    _("Size"),
    _("Status"),
    _("Space Saved")
  );

  $options = array(
    1 => array('align' => 'right'),
    3 => array('align' => 'right')
  );

  $totalData = 0;
  $totalIndex = 0;
  $totalAll = 0;
  $totalGain = 0;

  $rows = null;
  while ($row = $auxConn->fetchRow())
  {
    $rows[] = $row;
  }
  $auxConn->freeResult();

  $tbody = array();
  while ($row = array_shift($rows))
  {
    $totalData = $row['Data_length'];
    $totalIndex = $row['Index_length'];
    $total = $totalData + $totalIndex;
    $total = $total / 1024;
    $total = round($total, 3);
    $totalAll += $total;
    $gain= $row['Data_free'];
    $gain = $gain / 1024;
    $totalGain += $gain;
    $gain = round($gain, 3);

    $localQuery = 'OPTIMIZE TABLE ' . $row['Name'];
    if ( !$auxConn->exec($localQuery) )
    {
      $auxConn->close();
      Error::connection($auxConn);
    }

    $content = $row['Name'];
    $content .= OPEN_SEPARATOR;
    $content .= $total . ' KB';
    $content .= OPEN_SEPARATOR;
    if ($gain == 0)
    {
      $content .= _("Already optimized");
      $content .= OPEN_SEPARATOR;
      $content .= '0 KB';
    }
    else
    {
      $content .= _("Optimized!");
      $content .= OPEN_SEPARATOR;
      $content .= $gain . ' KB';
    }
    $tbody[] = explode(OPEN_SEPARATOR, $content);
  }
  $auxConn->close();
  unset($auxConn);
  unset($rows);
  unset($row);

  echo HTML::table($thead, $tbody, null, $options);

  $totalGain = round($totalGain, 3);
  echo HTML::section(3, _("Optimization Results") . ":");

  echo Msg::info(sprintf(_("Total Database Size: %d KB"), $totalAll));
  echo Msg::info(sprintf(_("Total Space Saved: %d KB"), $totalGain));

  echo HTML::para(HTML::link(_("Back return"), '../admin/dump_view_form.php'));

  require_once("../layout/footer.php");
?>
