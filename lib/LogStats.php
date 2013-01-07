<?php
/**
 * LogStats.php
 *
 * Contains the class LogStats
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: LogStats.php,v 1.13 2013/01/07 18:36:03 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @todo static class
 * @todo methods only return string (not echos)
 */

  require_once("../model/Query/LogStats.php");
  require_once("../lib/HTML.php");

/**
 * LogStats set of log stats functions
 *
 * Methods:
 *  string _percBar(int $percentage, int $scale = 1, string $label = "")
 *  mixed getMonthName(int $index = 0)
 *  void yearly(string $table)
 *  void monthly(string $table, int $year)
 *  void daily(string $table, int $year, int $month)
 *  void hourly(string $table, int $year, int $month, int $day)
 *  void summary(string $table)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class LogStats
{
  /**
   * string _percBar(int $percentage, int $scale = 1, string $label = "")
   *
   * Returns a percentage bar
   *
   * @param int $percentage
   * @param int $scale (optional)
   * @param string $label (optional) alternative text of the images
   * @return string
   * @access private
   * @static
   */
  private static function _percBar($percentage, $scale = 1, $label = "")
  {
    //$leftSize = getimagesize("../img/leftbar.gif");
    //$mainSize = getimagesize("../img/mainbar.gif");
    //$rightSize = getimagesize("../img/rightbar.gif");

    $perc = round($scale * $percentage, 0);

    $html = HTML::image('../img/leftbar.gif', $label, array('width' => 7, 'height' => 14));
    $html .= HTML::image('../img/mainbar.gif', $label, array('width' => $perc, 'height' => 14));
    $html .= HTML::image('../img/rightbar.gif', $label, array('width' => 7, 'height' => 14));
    $html = str_replace(PHP_EOL, '', $html);

    return $html;
  }

  /**
   * mixed getMonthName(int $index = 0)
   *
   * Returns a month name
   *
   * @return mixed string with month name or complete array
   * @access public
   * @static
   */
  public static function getMonthName($index = 0)
  {
    $_months = array(
      1 => _("January"),
      2 => _("February"),
      3 => _("March"),
      4 => _("April"),
      5 => _("May"),
      6 => _("June"),
      7 => _("July"),
      8 => _("August"),
      9 => _("September"),
      10 => _("October"),
      11 => _("November"),
      12 => _("December")
    );

    return (isset($_months[$index])) ? $_months[$index] : $_months;
  }

  /**
   * void yearly(string $table)
   *
   * Draws a table with yearly stats
   *
   * @param string $table
   * @return void
   * @access public
   * @static
   */
  public static function yearly($table)
  {
    $logQ = new Query_LogStats($table);
    $totalHits = $logQ->totalHits();

    echo HTML::section(4, sprintf(_("Yearly Stats: %d hits"), $totalHits));

    if ($totalHits == 0)
    {
      $logQ->close();
      echo Msg::info(_("There are not statistics"));

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
      $row = HTML::link($year, '../admin/log_list.php',
        array(
          'table' => $table,
          'year' => $year
        )
      );
      $row .= OPEN_SEPARATOR;
      $widthImage = round(100 * $hits / $totalHits, 0);
      $percent = substr(100 * $hits / $totalHits, 0, 5);
      $row .= self::_percBar($widthImage);
      $row .= ' ' . $percent . '% (' . $hits . ')';

      $tbody[] = explode(OPEN_SEPARATOR, $row);
    }
    echo HTML::table($thead, $tbody, null, $options);

    $logQ->freeResult();
    $logQ->close();
    unset($logQ);
  }

  /**
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
  public static function monthly($table, $year)
  {
    $logQ = new Query_LogStats($table);
    $totalHits = $logQ->yearHits($year);

    echo HTML::section(4, sprintf(_("Monthly Stats for %d: %d hits"), intval($year), $totalHits));

    if ($totalHits == 0)
    {
      $logQ->close();
      echo Msg::info(_("There are not statistics"));

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

    $months = self::getMonthName();

    $tbody = array();
    foreach ($array as $month => $hits)
    {
      $row = HTML::link($months[intval($month)], '../admin/log_list.php',
        array(
          'table' => $table,
          'year' => $year,
          'month' => $month
        )
      );
      $row .= OPEN_SEPARATOR;
      $widthImage = round(100 * $hits / $totalHits, 0);
      $percent = substr(100 * $hits / $totalHits, 0, 5);
      $row .= self::_percBar($widthImage);
      $row .= ' ' . $percent . '% (' . $hits . ')';

      $tbody[] = explode(OPEN_SEPARATOR, $row);
    }
    echo HTML::table($thead, $tbody, null, $options);

    $logQ->freeResult();
    $logQ->close();
    unset($logQ);
  }

  /**
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
  public static function daily($table, $year, $month)
  {
    $logQ = new Query_LogStats($table);
    $totalHits = $logQ->monthHits($year, $month);

    $monthName = self::getMonthName($month);

    echo HTML::section(4, sprintf(_("Daily Stats for %s, %d: %d hits"),
      $monthName, intval($year), $totalHits)
    );

    if ($totalHits == 0)
    {
      $logQ->close();
      echo Msg::info(_("There are not statistics"));

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
      $row = HTML::link(intval($day), '../admin/log_list.php',
        array(
          'table' => $table,
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
      }
      $row .= self::_percBar($widthImage);
      $row .= ' ' . $percent . '% (' . $hits . ')';

      $tbody[] = explode(OPEN_SEPARATOR, $row);
    }
    echo HTML::table($thead, $tbody, null, $options);

    $logQ->freeResult();
    $logQ->close();
    unset($logQ);
  }

  /**
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
  public static function hourly($table, $year, $month, $day)
  {
    $logQ = new Query_LogStats($table);
    $totalHits = $logQ->dayHits($year, $month, $day);

    $monthName = self::getMonthName($month);

    echo HTML::section(4, sprintf(_("Hourly Stats for %s %d, %d: %d hits"),
      $monthName, intval($day), intval($year), $totalHits)
    );

    if ($totalHits == 0)
    {
      $logQ->close();
      echo Msg::info(_("There are not statistics"));

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
      }
      $row .= self::_percBar($widthImage);
      $row .= ' ' . $percent . '% (' . $hits . ')';

      $tbody[] = explode(OPEN_SEPARATOR, $row);
    }
    echo HTML::table($thead, $tbody, null, $options);

    $logQ->freeResult();
    $logQ->close();
    unset($logQ);
  }

  /**
   * void summary(string $table)
   *
   * Draws summary stats
   *
   * @param string $table
   * @return void
   * @access public
   * @static
   */
  public static function summary($table)
  {
    $logQ = new Query_LogStats($table);
    $total = $logQ->totalHits();
    if ($total == 0)
    {
      $logQ->close();
      echo Msg::info(_("There are not statistics"));

      return;
    }

    $today = date("Y-m-d"); // calculated date
    $arrToday = explode("-", $today);

    $sectionTitle = _("Total") . ': ' . $total . ' ' . strtolower(_("Hits"));
    echo HTML::section(3, $sectionTitle);

    $array = $logQ->busiestYear();
    if (is_array($array))
    {
      list($year, $hits) = $array;

      echo HTML::para(sprintf(_("Busiest Year: %d (%d hits)"), intval($year), $hits));
    }

    $array = $logQ->busiestMonth();
    if (is_array($array))
    {
      list($year, $month, $hits) = $array;
      $months = self::getMonthName();

      echo HTML::para(sprintf(_("Busiest Month: %s %d (%d hits)"), $months[intval($month)], intval($year), $hits));
    }

    $array = $logQ->busiestDay();
    if (is_array($array))
    {
      list($year, $month, $day, $hits) = $array;

      echo HTML::para(sprintf(_("Busiest Day: %d %s %d (%d hits)"),
        intval($day), $months[intval($month)], intval($year), $hits)
      );
    }

    $array = $logQ->busiestHour();
    if (is_array($array))
    {
      list($year, $month, $day, $hour, $hits) = $array;

      $hour = sprintf("%02d:00 - %02d:59", $hour, $hour);
      echo HTML::para(sprintf(_("Busiest Hour: %s on %s %d, %d (%d hits)"),
        $hour, $months[intval($month)], intval($day), intval($year), $hits)
      );
    }

    $logQ->freeResult();
    $logQ->close();
    unset($logQ);
  }
} // end class
?>
