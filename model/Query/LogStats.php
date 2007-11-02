<?php
/**
 * LogStats.php
 *
 * Contains the class Query_LogStats
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: LogStats.php,v 1.2 2007/11/02 20:39:00 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../Query.php");

/**
 * Query_LogStats data access component for log stats (access and operations)
 *
 * Methods:
 *  bool Query_LogStats(string $table, array $dsn = null)
 *  mixed totalHits(void)
 *  mixed hitsByYear(void)
 *  mixed yearHits(int $year)
 *  mixed yearHitsByMonth(int $year)
 *  mixed monthHits(int $year, int $month)
 *  mixed monthHitsByDay(int $year, int $month)
 *  mixed dayHits(int $year, int $month, int $day)
 *  mixed dayHitsByHour(int $year, int $month, int $day)
 *  mixed busiestYear(void)
 *  mixed busiestMonth(void)
 *  mixed busiestDay(void)
 *  mixed busiestHour(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */

class Query_LogStats extends Query
{
  /**
   * bool Query_LogStats(string $table, array $dsn = null)
   *
   * Constructor function
   *
   * @param array $dsn (optional) Data Source Name
   * @return boolean returns false, if error occurs
   * @access public
   */
  function Query_LogStats($table, $dsn = null)
  {
    if ($table != 'access' && $table != 'record')
    {
      $table = 'access';
    }
    $this->_table = $table . '_log_tbl';

    return parent::Query($dsn);
  }

  /**
   * mixed totalHits(void)
   *
   * Change this
   *
   * @return mixed int if result or false otherwise
   * @access public
   */
  function totalHits()
  {
    $sql = "SELECT COUNT(*) FROM " . $this->_table;

    if ( !$this->exec($sql) || !$this->numRows() )
    {
      return false;
    }

    list($hits) = $this->fetchRow(MYSQL_NUM);

    return $hits;
  }

  /**
   * mixed hitsByYear(void)
   *
   * Change this
   *
   * @return mixed array if result or false otherwise
   * @access public
   */
  function hitsByYear()
  {
    $sql = "SELECT YEAR(access_date), COUNT(*)";
    $sql .= " FROM " . $this->_table;
    $sql .= " GROUP BY 1 ORDER BY 1";

    if ( !$this->exec($sql) || !$this->numRows() )
    {
      return false;
    }

    $array = null;
    while (list($year, $hits) = $this->fetchRow(MYSQL_NUM))
    {
      $array[$year] = $hits;
    }

    return $array;
  }

  /**
   * mixed yearHits(int $year)
   *
   * Change this
   *
   * @param int $year
   * @return mixed int if result or false otherwise
   * @access public
   */
  function yearHits($year)
  {
    $sql = "SELECT COUNT(*)";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE YEAR(access_date)='" . sprintf('%04d', $year) . "'";
    //echo $sql; // debug

    if ( !$this->exec($sql) || !$this->numRows() )
    {
      return false;
    }

    list($hits) = $this->fetchRow(MYSQL_NUM);

    return $hits;
  }

  /**
   * mixed yearHitsByMonth(int $year)
   *
   * Change this
   *
   * @param int $year
   * @return mixed array if result or false otherwise
   * @access public
   */
  function yearHitsByMonth($year)
  {
    $sql = "SELECT MONTH(access_date), COUNT(*)";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE YEAR(access_date)='" . sprintf('%04d', $year) . "'";
    $sql .= " GROUP BY DATE_FORMAT(access_date, '%Y-%m')";

    if ( !$this->exec($sql) || !$this->numRows() )
    {
      return false;
    }

    $array = null;
    while (list($month, $hits) = $this->fetchRow(MYSQL_NUM))
    {
      $array[$month] = $hits;
    }

    return $array;
  }

  /**
   * mixed monthHits(int $year, int $month)
   *
   * Change this
   *
   * @param int $year
   * @param int $month
   * @return mixed int if result or false otherwise
   * @access public
   */
  function monthHits($year, $month)
  {
    $sql = "SELECT COUNT(*)";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE YEAR(access_date)='" . sprintf('%04d', $year) . "'";
    $sql .= " AND MONTH(access_date)='" . sprintf('%02d', $month) . "'";
    //echo $sql; // debug

    if ( !$this->exec($sql) || !$this->numRows() )
    {
      return false;
    }

    list($hits) = $this->fetchRow(MYSQL_NUM);

    return $hits;
  }

  /**
   * mixed monthHitsByDay(int $year, int $month)
   *
   * Change this
   *
   * @param int $year
   * @param int $month
   * @return mixed array if result or false otherwise
   * @access public
   */
  function monthHitsByDay($year, $month)
  {
    $sql = "SELECT DATE_FORMAT(access_date, '%d'), COUNT(*)";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE YEAR(access_date)='" . sprintf('%04d', $year) . "'";
    $sql .= " AND MONTH(access_date)='" . sprintf('%02d', $month) . "'";
    $sql .= " GROUP BY 1 ORDER BY 1";

    if ( !$this->exec($sql) || !$this->numRows() )
    {
      return false;
    }

    $array = null;
    while (list($day, $hits) = $this->fetchRow(MYSQL_NUM))
    {
      $array[$day] = $hits;
    }

    return $array;
  }

  /**
   * mixed dayHits(int $year, int $month, int $day)
   *
   * Change this
   *
   * @param int $year
   * @param int $month
   * @param int $day
   * @return mixed int if result or false otherwise
   * @access public
   */
  function dayHits($year, $month, $day)
  {
    $sql = "SELECT COUNT(*)";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE YEAR(access_date)='" . sprintf('%04d', $year) . "'";
    $sql .= " AND MONTH(access_date)='" . sprintf('%02d', $month) . "'";
    $sql .= " AND DATE_FORMAT(access_date, '%d')='" . sprintf('%02d', $day) . "'";

    if ( !$this->exec($sql) || !$this->numRows() )
    {
      return false;
    }

    list($hits) = $this->fetchRow(MYSQL_NUM);

    return $hits;
  }

  /**
   * mixed dayHitsByHour(int $year, int $month, int $day)
   *
   * Change this
   *
   * @param int $year
   * @param int $month
   * @param int $day
   * @return mixed array if result or false otherwise
   * @access public
   */
  function dayHitsByHour($year, $month, $day)
  {
    $sql = "SELECT HOUR(access_date), COUNT(*)";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE YEAR(access_date)='" . sprintf('%04d', $year) . "'";
    $sql .= " AND MONTH(access_date)='" . sprintf('%02d', $month) . "'";
    $sql .= " AND DATE_FORMAT(access_date, '%d')='" . sprintf('%02d', $day) . "'";
    $sql .= " GROUP BY DATE_FORMAT(access_date, '%Y-%m-%d %H')";
    //echo $sql; // debug

    if ( !$this->exec($sql) || !$this->numRows() )
    {
      return false;
    }

    $array = null;
    while (list($hour, $hits) = $this->fetchRow(MYSQL_NUM))
    {
      $array[$hour] = $hits;
    }

    return $array;
  }

  /**
   * mixed busiestYear(void)
   *
   * Change this
   *
   * @return mixed array if result or false otherwise
   * @access public
   */
  function busiestYear()
  {
    $sql = "SELECT YEAR(access_date), COUNT(*)";
    $sql .= " FROM " . $this->_table;
    $sql .= " GROUP BY 1";
    $sql .= " ORDER BY 2 DESC";
    $sql .= " LIMIT 0, 1";

    if ( !$this->exec($sql) || !$this->numRows() )
    {
      return false;
    }

    return $this->fetchRow(MYSQL_NUM); // 0 => $year, 1 => $hits
  }

  /**
   * mixed busiestMonth(void)
   *
   * Change this
   *
   * @return mixed array if result or false otherwise
   * @access public
   */
  function busiestMonth()
  {
    $sql = "SELECT YEAR(access_date), MONTH(access_date), COUNT(*)";
    $sql .= " FROM " . $this->_table;
    $sql .= " GROUP BY 1, 2";
    $sql .= " ORDER BY 3 DESC";
    $sql .= " LIMIT 0, 1";

    if ( !$this->exec($sql) || !$this->numRows() )
    {
      return false;
    }

    return $this->fetchRow(MYSQL_NUM); // 0 => $year, 1 => $month, 2 => $hits
  }

  /**
   * mixed busiestDay(void)
   *
   * Change this
   *
   * @return mixed array if result or false otherwise
   * @access public
   */
  function busiestDay()
  {
    $sql = "SELECT YEAR(access_date), MONTH(access_date), DATE_FORMAT(access_date, '%d'), COUNT(*)";
    $sql .= " FROM " . $this->_table;
    $sql .= " GROUP BY 1, 2, 3";
    $sql .= " ORDER BY 4 DESC";
    $sql .= " LIMIT 0, 1";

    if ( !$this->exec($sql) || !$this->numRows() )
    {
      return false;
    }

    return $this->fetchRow(MYSQL_NUM); // 0 => $year, 1 => $month, 2 => $day, 3 => $hits
  }

  /**
   * mixed busiestHour(void)
   *
   * Change this
   *
   * @return mixed array if result or false otherwise
   * @access public
   */
  function busiestHour()
  {
    $sql = "SELECT YEAR(access_date), MONTH(access_date), DATE_FORMAT(access_date, '%d'), HOUR(access_date), COUNT(*)";
    $sql .= " FROM " . $this->_table;
    $sql .= " GROUP BY 1, 2, 3, 4";
    $sql .= " ORDER BY 5 DESC";
    $sql .= " LIMIT 0, 1";

    if ( !$this->exec($sql) || !$this->numRows() )
    {
      return false;
    }

    return $this->fetchRow(MYSQL_NUM); // 0 => $year, 1 => $month, 2 => $day, 3 => $hour, 4 => $hits
  }
} // end class
?>
