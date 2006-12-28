<?php
/**
 * log_lib.php
 *
 * Set of log stats functions
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: log_lib.php,v 1.16 2006/12/28 16:34:34 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../model/DbConnection.php");
  require_once("../lib/HTML.php");

/**
 * Functions:
 *  string percBar(int $pperc, int $scale = 1, string $label = "")
 *  void showYearStats(string $table)
 *  void showMonthStats(string $table, int $year)
 *  void showDailyStats(string $table, int $year, int $month)
 *  void showHourlyStats(string $table, int $year, int $month, int $day)
 *  void stats(string $table)
 *  void showLinks(string $table)
 * @todo class LogStats para las consultas
 */

/*
 * string percBar(int $pperc, int $scale = 1, string $label = "")
 *
 * Returns a percentage bar
 *
 * @param int $pperc
 * @param int $scale (optional)
 * @param string $label (optional) alternative text of the images
 * @return string
 * @access public
 */
function percBar($pperc, $scale = 1, $label = "")
{
  //$leftSize = getimagesize("../img/leftbar.gif");
  //$mainSize = getimagesize("../img/mainbar.gif");
  //$rightSize = getimagesize("../img/rightbar.gif");

  $perc = round($scale * $pperc, 0);

  $html = HTML::strStart('img',
    array(
      'src' => '../img/leftbar.gif',
      'width' => 7,
      'height' => 14,
      'alt' => $label
    ),
    true
  );
  $html .= HTML::strStart('img',
    array(
      'src' => '../img/mainbar.gif',
      'width' => $perc,
      'height' => 14,
      'alt' => $label
    ),
    true
  );
  $html .= HTML::strStart('img',
    array(
      'src' => '../img/rightbar.gif',
      'width' => 7,
      'height' => 14,
      'alt' => $label
    ),
    true
  );
  $html = str_replace("\n", '', $html);

  return $html;
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
  $auxConn = new DbConnection(); // new LogStats($table)
  $auxConn->connect();

  $query = "SELECT COUNT(*) AS total_hits_year FROM " . $table . "_log_tbl";
  $auxConn->exec($query); // LogStats->yearHits()
  list($totalHitsYear) = $auxConn->fetchRow(MYSQL_NUM);

  HTML::section(4, sprintf(_("Yearly Stats: %d hits"), $totalHitsYear));

  if ($totalHitsYear <= 0)
  {
    $auxConn->close();
    HTML::message(_("There are not statistics"), OPEN_MSG_INFO);

    return;
  }

  $thead = array(
    _("Year"),
    _("Hits")
  );

  $options = array(
    //'align' => 'center',
    1 => array('nowrap' => 1)
  );

  $query = "SELECT YEAR(access_date), COUNT(*) FROM " . $table . "_log_tbl";
  $query .= " GROUP BY 1 ORDER BY 1"; // LogStats->year()
  $auxConn->exec($query);

  $tbody = array();
  while (list($year, $hits) = $auxConn->fetchRow(MYSQL_NUM))
  {
    $row = HTML::strLink($year, $_SERVER['PHP_SELF'],
      array(
        'table' => $table,
        'option' => 'yearly',
        'year' => $year
      )
    );
    $row .= OPEN_SEPARATOR;
    $widthImage = round(100 * $hits / $totalHitsYear, 0);
    $percent = substr(100 * $hits / $totalHitsYear, 0, 5);
    $row .= percBar($widthImage);
    $row .= ' ' . $percent . '% (' . HTML::strLink($hits, '../admin/log_' . $table . '_list.php', array('year' => $year)) . ')';

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  }
  HTML::table($thead, $tbody, null, $options);

  $auxConn->freeResult();
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
  $auxConn = new DbConnection(); // new LogStats($table)
  $auxConn->connect();

  $query = "SELECT COUNT(*) AS total_hits_month FROM " . $table . "_log_tbl";
  $query .= " WHERE YEAR(access_date)='" . $year . "'";
  $auxConn->exec($query); // LogStats->monthHits($year)
  list($totalHitsMonth) = $auxConn->fetchRow(MYSQL_NUM);

  HTML::section(4, sprintf(_("Monthly Stats for %d: %d hits"), intval($year), $totalHitsMonth));

  if ($totalHitsMonth <= 0)
  {
    $auxConn->close();
    HTML::message(_("There are not statistics"), OPEN_MSG_INFO);

    return;
  }

  $thead = array(
    _("Month"),
    _("Hits")
  );

  $options = array(
    //'align' => 'center',
    1 => array('nowrap' => 1)
  );

  $query = "SELECT MONTH(access_date), COUNT(*) FROM " . $table . "_log_tbl";
  $query .= " WHERE YEAR(access_date)='" . $year . "'";
  $query .= " GROUP BY DATE_FORMAT(access_date, '%Y-%m')";
  $auxConn->exec($query); // LogStats->month($year)

  $months = array(_("January"), _("February"), _("March"), _("April"), _("May"), _("June"), _("July"), _("August"), _("September"), _("October"), _("November"), _("December"));

  $tbody = array();
  while (list($month, $hits) = $auxConn->fetchRow(MYSQL_NUM))
  {
    $row = HTML::strLink($months[intval($month) - 1], $_SERVER['PHP_SELF'],
      array(
        'table' => $table,
        'option' => 'monthly',
        'year' => $year,
        'month' => $month
      )
    );
    $row .= OPEN_SEPARATOR;
    $widthImage = round(100 * $hits / $totalHitsMonth, 0);
    $percent = substr(100 * $hits / $totalHitsMonth, 0, 5);
    $row .= percBar($widthImage);
    $row .= ' ' . $percent . '% (' . HTML::strLink($hits, '../admin/log_' . $table . '_list.php',
      array(
        'year' => $year,
        'month' => $month
      )
    ) . ')';

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  }
  HTML::table($thead, $tbody, null, $options);

  $auxConn->freeResult();
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
  $auxConn = new DbConnection(); // new LogStats($table)
  $auxConn->connect();

  $query = "SELECT COUNT(*) AS total_hits_date FROM " . $table . "_log_tbl";
  $query .= " WHERE YEAR(access_date)='" . $year . "'";
  $query .= " AND MONTH(access_date)='" . $month . "'";
  $auxConn->exec($query); // LogStats->dayHits($year, $month)
  list($totalHitsDay) = $auxConn->fetchRow(MYSQL_NUM);

  $months = array(_("January"), _("February"), _("March"), _("April"), _("May"), _("June"), _("July"), _("August"), _("September"), _("October"), _("November"), _("December"));

  HTML::section(4, sprintf(_("Daily Stats for %s, %d: %d hits"), $months[intval($month) - 1], intval($year), $totalHitsDay));

  if ($totalHitsDay <= 0)
  {
    $auxConn->close();
    HTML::message(_("There are not statistics"), OPEN_MSG_INFO);

    return;
  }

  $thead = array(
    _("Day"),
    _("Hits")
  );

  $options = array(
    //'align' => 'center',
    0 => array('align' => 'right'),
    1 => array('nowrap' => 1)
  );

  $query = "SELECT YEAR(access_date), MONTH(access_date), DATE_FORMAT(access_date, '%d'), COUNT(*)";
  $query .= " FROM " . $table . "_log_tbl";
  $query .= " WHERE YEAR(access_date)='" . $year . "'";
  $query .= " AND MONTH(access_date)='" . $month . "'";
  $query .= " GROUP BY 3 ORDER BY 3";
  $auxConn->exec($query); // LogStats->day($year, $month)

  $tbody = array();
  while (list($year, $month, $day, $hits) = $auxConn->fetchRow(MYSQL_NUM))
  {
    $row = HTML::strLink(intval($day), $_SERVER['PHP_SELF'],
      array(
        'table' => $table,
        'option' => 'daily',
        'year' => $year,
        'month' => $month,
        'day' => $day
      )
    );
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
      $hits = HTML::strLink($hits, '../admin/log_' . $table . '_list.php',
        array(
          'year' => $year,
          'month' => $month,
          'day' => $day
        )
      );
    }
    $row .= percBar($widthImage);
    $row .= ' ' . $percent . '% (' . $hits . ')';

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  }
  HTML::table($thead, $tbody, null, $options);

  $auxConn->freeResult();
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
  $auxConn = new DbConnection(); // new LogStats($table)
  $auxConn->connect();

  $query = "SELECT COUNT(*) AS total_hits_hour FROM " . $table . "_log_tbl";
  $query .= " WHERE YEAR(access_date)='" . $year . "'";
  $query .= " AND MONTH(access_date)='" . $month . "'";
  $query .= " AND DATE_FORMAT(access_date, '%d')='" . $day . "'";
  $auxConn->exec($query); // LogStats->hourHits($year, $month, $day)
  list($totalHitsHour) = $auxConn->fetchRow(MYSQL_NUM);

  $months = array(_("January"), _("February"), _("March"), _("April"), _("May"), _("June"), _("July"), _("August"), _("September"), _("October"), _("November"), _("December"));

  HTML::section(4, sprintf(_("Hourly Stats for %s %d, %d: %d hits"), $months[intval($month) - 1], intval($day), intval($year), $totalHitsHour));

  if ($totalHitsHour <= 0)
  {
    $auxConn->close();
    HTML::message(_("There are not statistics"), OPEN_MSG_INFO);

    return;
  }

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
    $query = "SELECT HOUR(access_date), COUNT(*) FROM " . $table . "_log_tbl";
    $query .= " WHERE YEAR(access_date)='" . $year . "'";
    $query .= " AND MONTH(access_date)='" . $month . "'";
    $query .= " AND DATE_FORMAT(access_date, '%d')='" . $day . "'";
    $query .= " AND HOUR(access_date)='" . $k . "'";
    $query .= " GROUP BY DATE_FORMAT(access_date, '%Y-%m-%d')";

    $auxConn->exec($query); // LogStats->hour($year, $month, $day)
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
        $hits = HTML::strLink($hits, '../admin/log_' . $table . '_list.php',
          array(
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour', $k
          )
        );
      }
      $row .= percBar($widthImage);
      $row .= ' ' . $percent . '% (' . $hits . ')';

      $tbody[] = explode(OPEN_SEPARATOR, $row);
    }
  }
  HTML::table($thead, $tbody, null, $options);

  $auxConn->freeResult();
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
  $auxConn = new DbConnection(); // new LogStats($table)
  $auxConn->connect();

  $query = "SELECT COUNT(*) FROM " . $table . "_log_tbl";
  $auxConn->exec($query); // LogStats->hits()
  list($total) = $auxConn->fetchRow(MYSQL_NUM);

  if ($total <= 0)
  {
    $auxConn->close();
    HTML::message(_("There are not statistics"), OPEN_MSG_INFO);

    return;
  }

  $today = date("Y-m-d"); // calculated date
  $arrToday = explode("-", $today);

  $sectionTitle = "";
  switch ($table)
  {
    case "access":
      $sectionTitle .= _("Access Logs");
      break;

    case "record":
      $sectionTitle .= _("Record Logs");
      break;
  }
  $sectionTitle .= ': ' . $total . ' ' . strtolower(_("Hits"));
  HTML::section(3, $sectionTitle);

  $query = "SELECT YEAR(access_date), MONTH(access_date), COUNT(*) FROM " . $table . "_log_tbl";
  $query .= " GROUP BY 1, 2";
  $query .= " ORDER BY 3 DESC";
  $query .= " LIMIT 0, 1";
  $auxConn->exec($query); // LogStats->busiestMonth()
  list($year, $month, $hits) = $auxConn->fetchRow(MYSQL_NUM);

  $months = array(_("January"), _("February"), _("March"), _("April"), _("May"), _("June"), _("July"), _("August"), _("September"), _("October"), _("November"), _("December"));

  HTML::para(sprintf(_("Busiest Month: %s %d (%d hits)"), $months[intval($month) - 1], intval($year), $hits));

  $query = "SELECT YEAR(access_date), MONTH(access_date), DATE_FORMAT(access_date, '%d'), COUNT(*)";
  $query .= " FROM " . $table . "_log_tbl";
  $query .= " GROUP BY 1, 2, 3";
  $query .= " ORDER BY 4 DESC";
  $query .= " LIMIT 0, 1";
  $auxConn->exec($query); // LogStats->busiestDay()
  list($year, $month, $day, $hits) = $auxConn->fetchRow(MYSQL_NUM);

  HTML::para(sprintf(_("Busiest Day: %d %s %d (%d hits)"), intval($day), $months[intval($month) - 1], intval($year), $hits));

  $query = "SELECT YEAR(access_date), MONTH(access_date), DATE_FORMAT(access_date, '%d'), HOUR(access_date), COUNT(*)";
  $query .= " FROM " . $table . "_log_tbl";
  $query .= " GROUP BY 1, 2, 3, 4";
  $query .= " ORDER BY 5 DESC";
  $query .= " LIMIT 0, 1";
  $auxConn->exec($query); // LogStats->busiestHour()
  list($year, $month, $day, $hour, $hits) = $auxConn->fetchRow(MYSQL_NUM);

  $hour = sprintf("%02d:00 - %02d:59", $hour, $hour);
  HTML::para(sprintf(_("Busiest Hour: %s on %s %d, %d (%d hits)"), $hour, $months[intval($month) - 1], intval($day), intval($year), $hits));

  $auxConn->freeResult();
  $auxConn->close();
  unset($auxConn);

  HTML::rule();
  showYearStats($table);
  HTML::rule();
  showMonthStats($table, intval($arrToday[0]));
  HTML::rule();
  showDailyStats($table, intval($arrToday[0]), intval($arrToday[1]));
  HTML::rule();
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
  HTML::para(
    HTML::strLink(_("Back to Main Statistics"), $_SERVER['PHP_SELF'], array('table' => $table))
    . ' | '
    . HTML::strLink(_("Back return"), (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '../index.php'))
  );
}
?>
