<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: log_lib.php,v 1.9 2005/06/14 18:54:22 jact Exp $
 */

/**
 * log_lib.php
 *
 * Set of log stats functions
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['PATH_TRANSLATED']))
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../classes/DbConnection.php");

/**
 * Functions:
 *  void percBar(int $pperc, int $width = 100, bool $xecho = true, string $label = "")
 *  void showYearStats(string $table)
 *  void showMonthStats(string $table, int $year)
 *  void showDailyStats(string $table, int $year, int $month)
 *  void showHourlyStats(string $table, int $year, int $month, int $day)
 *  void stats(string $table)
 *  void showLinks(string $table)
 */

/*
 * void percBar(int $pperc, int $width = 100, bool $xecho = true, string $label = "")
 *
 * Makes a percentage bar
 *
 * @param int $pperc
 * @param int $width (optional)
 * @param bool $xecho (optional) indicate if it's printed or returned
 * @param string $label (optional) alternative text of the images
 * @return void
 * @access public
 */
function percBar($pperc, $width = 100, $xecho = true, $label = "")
{
  //$leftSize = getimagesize("../images/leftbar.gif");
  //$mainSize = getimagesize("../images/mainbar.gif");
  //$rightSize = getimagesize("../images/rightbar.gif");

  $perc = round(($width * ($pperc / 100)), 0);

  $what = '<img src="../images/leftbar.gif" height="14" width="7" alt="' . $label . '" />';
  $what .= '<img src="../images/mainbar.gif" height="14" width="' . $perc . '" alt="' . $label . '" />';
  $what .= '<img src="../images/rightbar.gif" height="14" width="7" alt="' . $label . '" />';

  if ($xecho)
  {
    echo $what;
  }
  else
  {
    return $what;
  }
}

/*
 * void showYearStats(string $table)
 *
 * Draws a table with yearly stats
 *
 * @param string $table
 * @return void
 * @access public
 */
function showYearStats($table)
{
  $auxConn = new DbConnection();
  $auxConn->connect();

  $query = "SELECT COUNT(*) AS TotalHitsYear FROM " . $table . "_log_tbl";
  $query .= " GROUP BY YEAR(access_date)";
  $auxConn->exec($query);
  list($totalHitsYear) = $auxConn->fetchRow(MYSQL_NUM);

  echo '<h4>' . _("Yearly Stats") . "</h4>\n";

  if ($totalHitsYear > 0)
  {
    $thead = array(
      _("Year"),
      _("Hits")
    );

    $options = array(
      //'align' => 'center',
      1 => array('nowrap' => 1)
    );

    $query = "SELECT YEAR(access_date),COUNT(*) FROM " . $table . "_log_tbl";
    $query .= " GROUP BY 1 ORDER BY 1";
    $auxConn->exec($query);

    $tbody = array();
    while (list($year, $hits) = $auxConn->fetchRow(MYSQL_NUM))
    {
      $row = '<a href="' . $_SERVER['PHP_SELF'] . '?table=' . $table . '&amp;option=yearly&amp;year=' . $year . '">' . $year . '</a>';
      $row .= OPEN_SEPARATOR;
      $widthImage = round(100 * $hits / $totalHitsYear, 0);
      $row .= percBar($widthImage, 200, false);
      $row .= ' (<a href="../admin/log_' . $table . '_list.php?year=' . $year . '">' . $hits . '</a>)';

      $tbody[] = explode(OPEN_SEPARATOR, $row);
    }
    showTable($thead, $tbody, null, $options);

    $auxConn->freeResult();
  }
  else
  {
    showMessage(_("There are not statistics"), OPEN_MSG_INFO);
  }

  $auxConn->close();
  unset($auxConn);
}

/*
 * void showMonthStats(string $table, int $year)
 *
 * Draws a table with monthly stats
 *
 * @param string $table
 * @param int $year
 * @return void
 * @access public
 */
function showMonthStats($table, $year)
{
  $auxConn = new DbConnection();
  $auxConn->connect();

  $query = "SELECT COUNT(*) AS TotalHitsMonth FROM " . $table . "_log_tbl";
  $query .= " WHERE YEAR(access_date)='" . $year . "'";
  $auxConn->exec($query);
  list($totalHitsMonth) = $auxConn->fetchRow(MYSQL_NUM);

  echo '<h4>' . sprintf(_("Monthly Stats for %d"), intval($year)) . "</h4>\n";

  if ($totalHitsMonth > 0)
  {
    $thead = array(
      _("Month"),
      _("Hits")
    );

    $options = array(
      //'align' => 'center',
      1 => array('nowrap' => 1)
    );

    $query = "SELECT MONTH(access_date),COUNT(*) FROM " . $table . "_log_tbl";
    $query .= " WHERE YEAR(access_date)='" . $year . "'";
    $query .= " GROUP BY DATE_FORMAT(access_date, '%Y-%m')";
    $result = $auxConn->exec($query);

    $months = array(_("January"), _("February"), _("March"), _("April"), _("May"), _("June"), _("July"), _("August"), _("September"), _("October"), _("November"), _("December"));

    $tbody = array();
    while (list($month, $hits) = $auxConn->fetchRow(MYSQL_NUM))
    {
      $row = '<a href="' . $_SERVER['PHP_SELF'] . '?table=' . $table . '&amp;option=monthly&amp;year=' . $year . '&amp;month=' . $month . '">' . $months[intval($month) - 1] . '</a>';
      $row .= OPEN_SEPARATOR;
      $widthImage = round(100 * $hits / $totalHitsMonth, 0);
      $row .= percBar($widthImage, 200, false);
      $row .= ' (<a href="../admin/log_' . $table . '_list.php?year=' . $year . '&amp;month=' . $month . '">' . $hits . '</a>)';

      $tbody[] = explode(OPEN_SEPARATOR, $row);
    }
    showTable($thead, $tbody, null, $options);

    $auxConn->freeResult();
  }
  else
  {
    showMessage(_("There are not statistics"), OPEN_MSG_INFO);
  }
  $auxConn->close();
  unset($auxConn);
}

/*
 * void showDailyStats(string $table, int $year, int $month)
 *
 * Draws a table with daily stats
 *
 * @param string $table
 * @param int $year
 * @param int $month
 * @return void
 * @access public
 */
function showDailyStats($table, $year, $month)
{
  $auxConn = new DbConnection();
  $auxConn->connect();

  $query = "SELECT COUNT(*) AS TotalHitsDate FROM " . $table . "_log_tbl";
  $query .= " WHERE YEAR(access_date)='" . $year . "'";
  $query .= " AND MONTH(access_date)='" . $month . "'";
  $auxConn->exec($query);
  list($totalHitsDay) = $auxConn->fetchRow(MYSQL_NUM);

  $months = array(_("January"), _("February"), _("March"), _("April"), _("May"), _("June"), _("July"), _("August"), _("September"), _("October"), _("November"), _("December"));

  echo '<h4>' . sprintf(_("Daily Stats for %s, %d"), $months[intval($month) - 1], intval($year)) . "</h4>\n";

  if ($totalHitsDay > 0)
  {
    $thead = array(
      _("Day"),
      _("Hits")
    );

    $options = array(
      //'align' => 'center',
      0 => array('align' => 'right'),
      1 => array('nowrap' => 1)
    );

    $query = "SELECT YEAR(access_date),MONTH(access_date),DATE_FORMAT(access_date, '%d'),COUNT(*)";
    $query .= " FROM " . $table . "_log_tbl";
    $query .= " WHERE YEAR(access_date)='" . $year . "'";
    $query .= " AND MONTH(access_date)='" . $month . "'";
    $query .= " GROUP BY 3 ORDER BY 3";
    $auxConn->exec($query);
    //$totalHitsDay = $auxConn->numRows();

    $tbody = array();
    while (list($year, $month, $day, $hits) = $auxConn->fetchRow(MYSQL_NUM))
    {
      $row = '<a href="' . $_SERVER['PHP_SELF'] . '?table=' . $table . '&amp;option=daily&amp;year=' . $year . '&amp;month=' . $month . '&amp;day=' . $day . '">' . intval($day) . '</a>';
      $row .= OPEN_SEPARATOR;
      if ($hits == 0)
      {
        $widthImage = 0;
        $percent = 0;
      }
      else
      {
        $widthImage = round(100 * $hits / $totalHitsDay, 0);
        $percent = substr(100 * $hits / $totalHitsDay, 0, 5);
        $hits = '<a href="../admin/log_' . $table . '_list.php?year=' . $year . '&amp;month=' . $month . '&amp;day=' . $day . '">' . $hits . '</a>';
      }
      $row .= percBar($widthImage, 200, false);
      $row .= ' ' . $percent . '% (' . $hits . ')';

      $tbody[] = explode(OPEN_SEPARATOR, $row);
    }
    showTable($thead, $tbody, null, $options);

    $auxConn->freeResult();
  }
  else
  {
    showMessage(_("There are not statistics"), OPEN_MSG_INFO);
  }

  $auxConn->close();
  unset($auxConn);
}

/*
 * void showHourlyStats(string $table, int $year, int $month, int $day)
 *
 * Draws a table with hourly stats
 *
 * @param string $table
 * @param int $year
 * @param int $month
 * @param int $day
 * @return void
 * @access public
 */
function showHourlyStats($table, $year, $month, $day)
{
  $auxConn = new DbConnection();
  $auxConn->connect();

  $query = "SELECT COUNT(*) AS TotalHitsHour FROM " . $table . "_log_tbl";
  $query .= " WHERE YEAR(access_date)='" . $year . "'";
  $query .= " AND MONTH(access_date)='" . $month . "'";
  $query .= " AND DATE_FORMAT(access_date, '%d')='" . $day . "'";
  $auxConn->exec($query);
  list($totalHitsHour) = $auxConn->fetchRow(MYSQL_NUM);

  $months = array(_("January"), _("February"), _("March"), _("April"), _("May"), _("June"), _("July"), _("August"), _("September"), _("October"), _("November"), _("December"));

  echo '<h4>' . sprintf(_("Hourly Stats for %s %d, %d"), $months[intval($month) - 1], intval($day), intval($year)) . "</h4>\n";

  if ($totalHitsHour > 0)
  {
    $thead = array(
      _("Hour"),
      _("Hits")
    );

    $options = array(
      //'align' => 'center',
      1 => array('nowrap' => 1)
    );

    $tbody = array();
    for ($k = 0; $k <= 23; $k++)
    {
      $query = "SELECT HOUR(access_date),COUNT(*) FROM " . $table . "_log_tbl";
      $query .= " WHERE YEAR(access_date)='" . $year . "'";
      $query .= " AND MONTH(access_date)='" . $month . "'";
      $query .= " AND DATE_FORMAT(access_date, '%d')='" . $day . "'";
      $query .= " AND HOUR(access_date)='" . $k . "'";
      $query .= " GROUP BY DATE_FORMAT(access_date, '%Y-%m-%d')";

      $auxConn->exec($query);
      if ($auxConn->numRows() > 0)
      {
        list($hour, $hits) = $auxConn->fetchRow(MYSQL_NUM);

        $row = sprintf("%02d:00 - %02d:59", $k, $k);
        $row .= OPEN_SEPARATOR;
        if ($hits == 0)
        {
          $widthImage = 0;
          $percent = 0;
        }
        else
        {
          $widthImage = round(100 * $hits / $totalHitsHour, 0);
          $percent = substr(100 * $hits / $totalHitsHour, 0, 5);
          $hits = '<a href="../admin/log_' . $table . '_list.php?year=' . $year . '&amp;month=' . $month . '&amp;day=' . $day . '&amp;hour=' . $k . '">' . $hits . '</a>';
        }
        $row .= percBar($widthImage, 200, false);
        $row .= ' ' . $percent . '% (' . $hits . ')';

        $tbody[] = explode(OPEN_SEPARATOR, $row);
      }
    }
    showTable($thead, $tbody, null, $options);

    $auxConn->freeResult();
  }
  else
  {
    showMessage(_("There are not statistics"), OPEN_MSG_INFO);
  }

  $auxConn->close();
  unset($auxConn);
}

/*
 * void stats(string $table)
 *
 * Draws tables with stats
 *
 * @param string $table
 * @return void
 * @access public
 */
function stats($table)
{
  $auxConn = new DbConnection();
  $auxConn->connect();

  $query = "SELECT COUNT(*) FROM " . $table . "_log_tbl";
  $auxConn->exec($query);
  list($total) = $auxConn->fetchRow(MYSQL_NUM);

  $today = date("Y-m-d"); // calculated date
  $arrToday = explode("-", $today);

  echo '<h3>';
  switch ($table)
  {
    case "access":
      echo _("Access Logs");
      break;

    case "record":
      echo _("Record Logs");
      break;
  }
  echo ': ' . $total . ' ';
  echo strtolower(_("Hits"));
  echo "</h3>\n";

  $query = "SELECT YEAR(access_date), MONTH(access_date), COUNT(*) FROM " . $table . "_log_tbl";
  $query .= " GROUP BY 2";
  $query .= " ORDER BY DATE_FORMAT(access_date, '%Y-%m') DESC";
  $query .= " LIMIT 0,1";
  $auxConn->exec($query);
  list($year, $month, $hits) = $auxConn->fetchRow(MYSQL_NUM);

  $months = array(_("January"), _("February"), _("March"), _("April"), _("May"), _("June"), _("July"), _("August"), _("September"), _("October"), _("November"), _("December"));

  echo '<p>' . sprintf(_("Busiest Month: %s %d (%d hits)"), $months[intval($month) - 1], intval($year), $hits) . "</p>\n";

  $query = "SELECT YEAR(access_date), MONTH(access_date), DATE_FORMAT(access_date, '%d'), COUNT(*)";
  $query .= " FROM " . $table . "_log_tbl";
  $query .= " GROUP BY 3";
  $query .= " ORDER BY 4 DESC";
  $query .= " LIMIT 0,1";
  $auxConn->exec($query);
  list($year, $month, $day, $hits) = $auxConn->fetchRow(MYSQL_NUM);

  echo '<p>' . sprintf(_("Busiest Day: %d %s %d (%d hits)"), intval($day), $months[intval($month) - 1], intval($year), $hits) . "</p>\n";

  $query = "SELECT YEAR(access_date), MONTH(access_date), DATE_FORMAT(access_date, '%d'), HOUR(access_date), COUNT(*)";
  $query .= " FROM " . $table . "_log_tbl";
  $query .= " GROUP BY DATE_FORMAT(access_date, '%Y-%m-%d %H')";
  $query .= " ORDER BY 5 DESC";
  $query .= " LIMIT 0,1";
  $auxConn->exec($query);
  list($year, $month, $day, $hour, $hits) = $auxConn->fetchRow(MYSQL_NUM);

  $auxConn->freeResult();
  $auxConn->close();
  unset($auxConn);

  $hour = sprintf("%02d:00 - %02d:59", $hour, $hour);
  echo '<p>' . sprintf(_("Busiest Hour: %s on %s %d, %d (%d hits)"), $hour, $months[intval($month) - 1], intval($day), intval($year), $hits) . "</p>\n";

  echo "<hr />\n";
  showYearStats($table);
  echo "<hr />\n";
  showMonthStats($table, intval($arrToday[0]));
  echo "<hr />\n";
  showDailyStats($table, intval($arrToday[0]), intval($arrToday[1]));
  echo "<hr />\n";
  showHourlyStats($table, intval($arrToday[0]), intval($arrToday[1]), $arrToday[2]);
}

/*
 * void showLinks(string $table)
 *
 * Displays navigation log links
 *
 * @param string $table
 * @return void
 * @access public
 */
function showLinks($table)
{
  echo '<p>';
  echo '<a href="' . $_SERVER['PHP_SELF'] . '?table=' . $table . '">';
  echo _("Back to Main Statistics");
  echo '</a> | <a href="' . (isset($_SERVER["HTTP_REFERER"]) ? htmlspecialchars($_SERVER["HTTP_REFERER"]) : '../index.php') . '">';
  echo _("Back return");
  echo "</a></p>\n";
}
?>
