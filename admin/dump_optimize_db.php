<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: dump_optimize_db.php,v 1.1 2004/03/20 20:53:16 jact Exp $
 */

/**
 * dump_optimize_db.php
 ********************************************************************
 * Optimization screen of database
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 20/03/04 21:53
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

  @set_time_limit(EXEC_TIME_LIMIT);

  $auxConn = new DbConnection();
  if ( !$auxConn->connect() )
  {
    $auxConn->close();
    showConnError($auxConn);
  }

  $localQuery = 'SHOW TABLE STATUS FROM ' . DLIB_backquote(OPEN_DATABASE);
  if ( !$auxConn->exec($localQuery) )
  {
    $auxConn->close();
    showConnError($auxConn);
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

  echo '<table>';
  echo '<thead><tr>';
  echo '<th colspan="4">' . sprintf(_("Optimizing Database: %s"), OPEN_DATABASE) . '</th>';
  echo "</tr>\n";
  echo '<tr>';
  echo '<th>' . _("Table Name") . '</th>';
  echo '<th>' . _("Size") . '</th>';
  echo '<th>' . _("Status") . '</th>';
  echo '<th>' . _("Space Saved") . '</th>';
  echo "</tr></thead><tbody>\n";

  $totalData = 0;
  $totalIndex = 0;
  $totalAll = 0;

  $numTables = $auxConn->numRows();
  if ($numTables > 0)
  {
    $rows = null;
    while ($row = $auxConn->fetchRow())
    {
      $rows[] = $row;
    }
    $auxConn->freeResult();

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
        showConnError($auxConn);
      }

      echo '<tr>';
      echo '<td>' . $row['Name'] . '</td>';
      echo '<td class="number">' . $total . ' KB</td>';
      if ($gain == 0)
      {
        echo '<td>' . _("Already optimized") . '</td>';
        echo '<td class="number">0 KB</td>';
      }
      else
      {
        echo '<td class="center"><strong>' . _("Optimized!") . '</strong></td>';
        echo '<td class="number"><strong>' . $gain . ' KB</strong></td>';
      }
      echo "</tr>\n";
    }
  }
  echo "</tbody>\n";
  $auxConn->close();
  unset($auxConn);
  unset($rows);
  unset($row);

  $totalGain = round($totalGain, 3);
  echo '<tfoot><tr>';
  echo '<th colspan="4">' . _("Optimization Results") . '</th>';
  echo "</tr>\n";
  echo '<tr>';
  echo '<th colspan="2">' . sprintf(_("Total Database Size: %d KB"), $totalAll) . '</th>';
  echo '<th colspan="2">' . sprintf(_("Total Space Saved: %d KB"), $totalGain) . '</th>';
  echo "</tr></tfoot>\n";
  echo "</table>\n";

  echo '<p><a href="../admin/dump_view_form.php">';
  echo _("Back return");
  echo "</a></p>\n";

  require_once("../shared/footer.php");
?>
