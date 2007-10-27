<?php
/**
 * LogStats.php
 *
 * Contains the class LogStats
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: LogStats.php,v 1.7 2007/10/27 17:15:44 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @todo static class
 */

  require_once("../model/LogStats_Query.php");
  require_once("../lib/HTML.php");

/**
 * LogStats set of log stats functions
 *
 * Methods:
 *  string _percBar(int $percentage, int $scale = 1, string $label = "")
 *  array _getMonths(void)
 *  void yearly(string $table)
 *  void monthly(string $table, int $year)
 *  void daily(string $table, int $year, int $month)
 *  void hourly(string $table, int $year, int $month, int $day)
 *  void all(string $table)
 *  void links(string $table, array $date = null)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class LogStats
{
  /*
   * string _percBar(int $percentage, int $scale = 1, string $label = "")
   *
   * Returns a percentage bar
   *
   * @param int $percentage
   * @param int $scale (optional)
   * @param string $label (optional) alternative text of the images
   * @return string
   * @access private
   */
  function _percBar($percentage, $scale = 1, $label = "")
  {
    //$leftSize = getimagesize("../img/leftbar.gif");
    //$mainSize = getimagesize("../img/mainbar.gif");
    //$rightSize = getimagesize("../img/rightbar.gif");

    $perc = round($scale * $percentage, 0);

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
    $html = str_replace(PHP_EOL, '', $html);

    return $html;
  }

  /*
   * array _getMonths(void)
   *
   * Returns an array with month names
   *
   * @return array
   * @access private
   */
  function _getMonths()
  {
    $months = array(
      _("January"),
      _("February"),
      _("March"),
      _("April"),
      _("May"),
      _("June"),
      _("July"),
      _("August"),
      _("September"),
      _("October"),
      _("November"),
      _("December")
    );

    return $months;
  }

  /*
   * void yearly(string $table)
   *
   * Draws a table with yearly stats
   *
   * @param string $table
   * @return void
   * @access public
   * @static
   */
  function yearly($table)
  {
    $logQ = new LogStats_Query($table);
    $logQ->connect();

    $totalHits = $logQ->totalHits();

    HTML::section(4, sprintf(_("Yearly Stats: %d hits"), $totalHits));

    if ($totalHits == 0)
    {
      $logQ->close();
      Msg::info(_("There are not statistics"));

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

    $array = $logQ->hitsByYear();

    $tbody = array();
    foreach ($array as $year => $hits)
    {
      $row = HTML::strLink($year, '../admin/log_stats.php',
        array(
          'table' => $table,
          'option' => 'monthly',
          'year' => $year
        )
      );
      $row .= OPEN_SEPARATOR;
      $widthImage = round(100 * $hits / $totalHits, 0);
      $percent = substr(100 * $hits / $totalHits, 0, 5);
      $row .= LogStats::_percBar($widthImage);
      $row .= ' ' . $percent . '% (' . HTML::strLink($hits, '../admin/log_' . $table . '_list.php', array('year' => $year)) . ')';

      $tbody[] = explode(OPEN_SEPARATOR, $row);
    }
    HTML::table($thead, $tbody, null, $options);

    $logQ->freeResult();
    $logQ->close();
    unset($logQ);
  }

  /*
   * void monthly(string $table, int $year)
   *
   * Draws a table with monthly stats
   *
   * @param string $table
   * @param int $year
   * @return void
   * @access public
   * @static
   */
  function monthly($table, $year)
  {
    $logQ = new LogStats_Query($table);
    $logQ->connect();

    $totalHits = $logQ->yearHits($year);

    HTML::section(4, sprintf(_("Monthly Stats for %d: %d hits"), intval($year), $totalHits));

    if ($totalHits == 0)
    {
      $logQ->close();
      Msg::info(_("There are not statistics"));

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

    $array = $logQ->yearHitsByMonth($year);

    $months = LogStats::_getMonths();

    $tbody = array();
    foreach ($array as $month => $hits)
    {
      $row = HTML::strLink($months[intval($month) - 1], '../admin/log_stats.php',
        array(
          'table' => $table,
          'option' => 'daily',
          'year' => $year,
          'month' => $month
        )
      );
      $row .= OPEN_SEPARATOR;
      $widthImage = round(100 * $hits / $totalHits, 0);
      $percent = substr(100 * $hits / $totalHits, 0, 5);
      $row .= LogStats::_percBar($widthImage);
      $row .= ' ' . $percent . '% (' . HTML::strLink($hits, '../admin/log_' . $table . '_list.php',
        array(
          'year' => $year,
          'month' => $month
        )
      ) . ')';

      $tbody[] = explode(OPEN_SEPARATOR, $row);
    }
    HTML::table($thead, $tbody, null, $options);

    $logQ->freeResult();
    $logQ->close();
    unset($logQ);
  }

  /*
   * void daily(string $table, int $year, int $month)
   *
   * Draws a table with daily stats
   *
   * @param string $table
   * @param int $year
   * @param int $month
   * @return void
   * @access public
   * @static
   */
  function daily($table, $year, $month)
  {
    $logQ = new LogStats_Query($table);
    $logQ->connect();

    $totalHits = $logQ->monthHits($year, $month);

    $months = LogStats::_getMonths();

    HTML::section(4, sprintf(_("Daily Stats for %s, %d: %d hits"), $months[intval($month) - 1], intval($year), $totalHits));

    if ($totalHits == 0)
    {
      $logQ->close();
      Msg::info(_("There are not statistics"));

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

    $array = $logQ->monthHitsByDay($year, $month);

    $tbody = array();
    foreach ($array as $day => $hits)
    {
      $row = HTML::strLink(intval($day), '../admin/log_stats.php',
        array(
          'table' => $table,
          'option' => 'hourly',
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
        $widthImage = round(100 * $hits / $totalHits, 0);
        $percent = substr(100 * $hits / $totalHits, 0, 5);
        $hits = HTML::strLink($hits, '../admin/log_' . $table . '_list.php',
          array(
            'year' => $year,
            'month' => $month,
            'day' => $day
          )
        );
      }
      $row .= LogStats::_percBar($widthImage);
      $row .= ' ' . $percent . '% (' . $hits . ')';

      $tbody[] = explode(OPEN_SEPARATOR, $row);
    }
    HTML::table($thead, $tbody, null, $options);

    $logQ->freeResult();
    $logQ->close();
    unset($logQ);
  }

  /*
   * void hourly(string $table, int $year, int $month, int $day)
   *
   * Draws a table with hourly stats
   *
   * @param string $table
   * @param int $year
   * @param int $month
   * @param int $day
   * @return void
   * @access public
   * @static
   */
  function hourly($table, $year, $month, $day)
  {
    $logQ = new LogStats_Query($table);
    $logQ->connect();

    $totalHits = $logQ->dayHits($year, $month, $day);

    $months = LogStats::_getMonths();

    HTML::section(4, sprintf(_("Hourly Stats for %s %d, %d: %d hits"), $months[intval($month) - 1], intval($day), intval($year), $totalHits));

    if ($totalHits == 0)
    {
      $logQ->close();
      Msg::info(_("There are not statistics"));

      return;
    }

    $array = $logQ->dayHitsByHour($year, $month, $day);

    $thead = array(
      _("Hour"),
      _("Hits")
    );

    $options = array(
      //'align' => 'center',
      1 => array('nowrap' => 1)
    );

    $tbody = array();
    foreach ($array as $hour => $hits)
    {
      $row = sprintf("%02d:00 - %02d:59", $hour, $hour);
      $row .= OPEN_SEPARATOR;
      if ($hits == 0)
      {
        $widthImage = 0;
        $percent = 0;
      }
      else
      {
        $widthImage = round(100 * $hits / $totalHits, 0);
        $percent = substr(100 * $hits / $totalHits, 0, 5);
        $hits = HTML::strLink($hits, '../admin/log_' . $table . '_list.php',
          array(
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour', $hour
          )
        );
      }
      $row .= LogStats::_percBar($widthImage);
      $row .= ' ' . $percent . '% (' . $hits . ')';

      $tbody[] = explode(OPEN_SEPARATOR, $row);
    }
    HTML::table($thead, $tbody, null, $options);

    $logQ->freeResult();
    $logQ->close();
    unset($logQ);
  }

  /*
   * void all(string $table)
   *
   * Draws tables with all stats
   *
   * @param string $table
   * @return void
   * @access public
   * @static
   */
  function all($table)
  {
    $logQ = new LogStats_Query($table);
    $logQ->connect();

    $total = $logQ->totalHits();
    if ($total == 0)
    {
      $logQ->close();
      Msg::info(_("There are not statistics"));

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

    $array = $logQ->busiestYear();
    if (is_array($array))
    {
      list($year, $hits) = $array;

      HTML::para(sprintf(_("Busiest Year: %d (%d hits)"), intval($year), $hits));
    }

    $array = $logQ->busiestMonth();
    if (is_array($array))
    {
      list($year, $month, $hits) = $array;
      $months = LogStats::_getMonths();

      HTML::para(sprintf(_("Busiest Month: %s %d (%d hits)"), $months[intval($month) - 1], intval($year), $hits));
    }

    $array = $logQ->busiestDay();
    if (is_array($array))
    {
      list($year, $month, $day, $hits) = $array;

      HTML::para(sprintf(_("Busiest Day: %d %s %d (%d hits)"), intval($day), $months[intval($month) - 1], intval($year), $hits));
    }

    $array = $logQ->busiestHour();
    if (is_array($array))
    {
      list($year, $month, $day, $hour, $hits) = $array;

      $hour = sprintf("%02d:00 - %02d:59", $hour, $hour);
      HTML::para(sprintf(_("Busiest Hour: %s on %s %d, %d (%d hits)"), $hour, $months[intval($month) - 1], intval($day), intval($year), $hits));
    }

    $logQ->freeResult();
    $logQ->close();
    unset($logQ);

    HTML::rule();
    LogStats::yearly($table);
    HTML::rule();
    LogStats::monthly($table, intval($arrToday[0]));
    HTML::rule();
    LogStats::daily($table, intval($arrToday[0]), intval($arrToday[1]));
    HTML::rule();
    LogStats::hourly($table, intval($arrToday[0]), intval($arrToday[1]), $arrToday[2]);
  }

  /*
   * void links(string $table, array $date = null)
   *
   * Displays navigation log links
   *
   * @param string $table
   * @param array $date (optional) array('year' => int[, 'month' => int[, 'day' => int]])
   * @return void
   * @access public
   * @static
   */
  function links($table, $date = null)
  {
    $page = '../admin/log_stats.php';

    $array[] = HTML::strLink(_("Main Stats"), $page, array('table' => $table));
    if (is_array($date) && isset($date['year']) && isset($date['month']))
    {
      $array[] = HTML::strLink(_("Monthly Stats"), $page,
        array('table' => $table, 'option' => 'monthly', 'year' => $date['year'])
      );

      if (isset($date['day']))
      {
        $array[] = HTML::strLink(_("Daily Stats"), $page,
          array('table' => $table, 'option' => 'daily', 'year' => $date['year'], 'month' => $date['month'])
        );
      }
    }

    HTML::para(implode(' | ', $array));
  }
} // end class
?>
