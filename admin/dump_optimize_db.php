<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: dump_optimize_db.php,v 1.5 2005/07/19 19:50:03 jact Exp $
 */

/**
 * dump_optimize_db.php
 *
 * Optimization screen of database
 *
 * Author: jact <jachavar@gmail.com>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "dump";
  $restrictInDemo = true; // To prevent users' malice

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/dump_lib.php"); // DLIB_backquote()

  @set_time_limit(OPEN_EXEC_TIME_LIMIT);

  $auxConn = new DbConnection();
  if ( !$auxConn->connect() )
  {
    $auxConn->close();
    Error::connection($auxConn);
  }

  $localQuery = 'SHOW TABLE STATUS FROM ' . DLIB_backquote(OPEN_DATABASE);
  if ( !$auxConn->exec($localQuery) )
  {
    $auxConn->close();
    Error::connection($auxConn);
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Optimize Database");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Dumps") => "../admin/dump_view_form.php",
    $title => ""
  );
  showNavLinks($links, "dumps.png");
  unset($links);

  echo '<h3>' . sprintf(_("Optimizing Database: %s"), OPEN_DATABASE) . "</h3>\n";

  $numTables = $auxConn->numRows();
  if ( !$numTables )
  {
    $auxConn->close();
    showMessage(_("Database is empty."), OPEN_MSG_ERROR);
    include_once("../shared/footer.php");
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

  showTable($thead, $tbody, null, $options);

  $totalGain = round($totalGain, 3);
  echo '<h4>' . _("Optimization Results") . ":</h4>\n";

  showMessage(sprintf(_("Total Database Size: %d KB"), $totalAll), OPEN_MSG_INFO);
  showMessage(sprintf(_("Total Space Saved: %d KB"), $totalGain), OPEN_MSG_INFO);

  echo '<p><a href="../admin/dump_view_form.php">';
  echo _("Back return");
  echo "</a></p>\n";

  require_once("../shared/footer.php");
?>
