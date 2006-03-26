<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Connection_Query.php,v 1.10 2006/03/26 16:12:33 jact Exp $
 */

/**
 * Connection_Query.php
 *
 * Contains the class Connection_Query
 *
 * @author jact <jachavar@gmail.com>
 */

require_once("../classes/Query.php");

/**
 * Connection_Query data access component for connection table
 *
 * Methods:
 *  void Connection_Query(void)
 *  mixed select(int $idProblem, int $idConnection = 0)
 *  mixed fetch(void)
 *  bool insert(int $idProblem, int $idConnection)
 *  bool delete(int $idProblem, int $idConnection)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Connection_Query extends Query
{
  /**
   * void Connection_Query(void)
   *
   * Constructor function
   *
   * @return void
   * @access public
   */
  function Connection_Query()
  {
    $this->_table = "connection_problem_tbl";
    $this->_primaryKey = array("id_problem", "id_connection");
  }

  /**
   * mixed select(int $idProblem, int $idConnection = 0)
   *
   * Executes a query
   *
   * @param int $idProblem key of medical problem
   * @param int $idConnection (optional) key of connection problem
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function select($idProblem, $idConnection = 0)
  {
    $sql = "SELECT *";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE id_problem=" . intval($idProblem);
    if ($idConnection)
    {
      $sql .= " AND id_connection=" . intval($idConnection);
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
   * bool insert(int $idProblem, int $idConnection)
   *
   * Inserts a new connection problem into the database.
   *
   * @param int $idProblem
   * @param int $idConnection
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($idProblem, $idConnection)
  {
    $sql = "INSERT INTO " . $this->_table;
    $sql .= " (id_problem, id_connection) VALUES (";
    $sql .= intval($idProblem) . ", ";
    $sql .= intval($idConnection) . ");";

    if ( !$this->exec($sql) )
    {
      return false;
    }

    $sql = "INSERT INTO " . $this->_table;
    $sql .= " (id_problem, id_connection) VALUES (";
    $sql .= intval($idConnection) . ", ";
    $sql .= intval($idProblem) . ");";

    return $this->exec($sql);
  }

  /**
   * bool delete(int $idProblem, int $idConnection)
   *
   * Delete a row in the connection table.
   *
   * @param int $idProblem
   * @param int $idConnection
   * @return boolean returns false, if error occurs
   * @access public
   */
  function delete($idProblem, $idConnection)
  {
    $sql = "DELETE FROM " . $this->_table;
    $sql .= " WHERE id_problem=" . intval($idProblem);
    $sql .= " AND id_connection=" . intval($idConnection);

    if ( !$this->exec($sql) )
    {
      return false;
    }

    $sql = "DELETE FROM " . $this->_table;
    $sql .= " WHERE id_problem=" . intval($idConnection);
    $sql .= " AND id_connection=" . intval($idProblem);

    return $this->exec($sql);
  }
} // end class
?>
