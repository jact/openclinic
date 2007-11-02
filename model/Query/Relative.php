<?php
/**
 * Relative.php
 *
 * Contains the class Query_Relative
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Relative.php,v 1.2 2007/11/02 20:39:00 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../Query.php");

/**
 * Query_Relative data access component for relative table
 *
 * Methods:
 *  bool Query_Relative(array $dsn = null)
 *  mixed select(int $idPatient, int $idRelative = 0)
 *  mixed fetch(void)
 *  bool insert(int $idPatient, int $idRelative)
 *  bool delete(int $idPatient, int $idRelative)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Query_Relative extends Query
{
  /**
   * bool Query_Relative(array $dsn = null)
   *
   * Constructor function
   *
   * @param array $dsn (optional) Data Source Name
   * @return boolean returns false, if error occurs
   * @access public
   */
  function Query_Relative($dsn = null)
  {
    $this->_table = "relative_tbl";

    return parent::Query($dsn);
  }

  /**
   * mixed select(int $idPatient, int $idRelative = 0)
   *
   * Executes a query
   *
   * @param int $idPatient key of patient
   * @param int $idRelative (optional) key of relative
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function select($idPatient, $idRelative = 0)
  {
    $sql = "SELECT *";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE id_patient=" . intval($idPatient);
    if ($idRelative > 0)
    {
      $sql .= " AND id_relative=" . intval($idRelative);
    }

    return ($this->exec($sql) ? $this->numRows() : false);
  }

  /**
   * mixed fetch(void)
   *
   * Fetches a row from the query result.
   *
   * @return array returns array or false if no more rows to fetch
   * @access public
   */
  function fetch()
  {
    $array = parent::fetchRow(MYSQL_NUM);

    return $array; // false or array
  }

  /**
   * bool insert(int $idPatient, int $idRelative)
   *
   * Inserts a new relative patient into the database.
   *
   * @param int $idPatient
   * @param int $idRelative
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($idPatient, $idRelative)
  {
    $sql = "INSERT INTO " . $this->_table;
    $sql .= " (id_patient, id_relative) VALUES (";
    $sql .= intval($idPatient) . ", ";
    $sql .= intval($idRelative) . ");";

    if ( !$this->exec($sql) )
    {
      return false;
    }

    $sql = "INSERT INTO " . $this->_table;
    $sql .= " (id_patient, id_relative) VALUES (";
    $sql .= intval($idRelative) . ", ";
    $sql .= intval($idPatient) . ");";

    return $this->exec($sql);
  }

  /**
   * bool delete(int $idPatient, int $idRelative)
   *
   * Delete a row in the relative table.
   *
   * @param int $idPatient
   * @param int $idRelative
   * @return boolean returns false, if error occurs
   * @access public
   */
  function delete($idPatient, $idRelative)
  {
    $sql = "DELETE FROM " . $this->_table;
    $sql .= " WHERE id_patient=" . intval($idPatient);
    $sql .= " AND id_relative=" . intval($idRelative);

    if ( !$this->exec($sql) )
    {
      return false;
    }

    $sql = "DELETE FROM " . $this->_table;
    $sql .= " WHERE id_patient=" . intval($idRelative);
    $sql .= " AND id_relative=" . intval($idPatient);

    return $this->exec($sql);
  }
} // end class
?>
