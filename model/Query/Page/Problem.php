<?php
/**
 * Problem.php
 *
 * Contains the class Query_Page_Problem
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Problem.php,v 1.4 2013/01/07 18:05:09 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../Page.php");
require_once(dirname(__FILE__) . "/../../Problem.php");

/**
 * Query_Page_Problem data access component for medical problems
 *
 * Methods:
 *  bool Query_Page_Problem(array $dsn = null)
 *  bool search(int $type, array $word, int $page, string $logical, int $limitFrom = 0)
 *  mixed getLastId(void)
 *  mixed select(int $idProblem)
 *  mixed selectProblems(int $idPatient, bool $closed = false)
 *  mixed getLastOrderNumber(int $idPatient)
 *  mixed fetch(void)
 *  bool insert(Problem $problem)
 *  bool update(Problem $problem)
 *  bool delete(int $idProblem)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Query_Page_Problem extends Query_Page
{
  /**
   * bool Query_Page_Problem(array $dsn = null)
   *
   * Constructor function
   *
   * @param array $dsn (optional) Data Source Name
   * @return boolean returns false, if error occurs
   * @access public
   */
  function Query_Page_Problem($dsn = null)
  {
    $this->_table = "problem_tbl";
    $this->_primaryKey = array("id_problem");

    $this->_map = array(
      'id_problem' => array('mutator' => 'setIdProblem'),
      'last_update_date' => array('mutator' => 'setLastUpdateDate'),
      'id_patient' => array('mutator' => 'setIdPatient'),
      'id_member' => array('mutator' => 'setIdMember'),
      'collegiate_number' => array('mutator' => 'setCollegiateNumber'),
      'order_number' => array('mutator' => 'setOrderNumber'),
      'opening_date' => array('mutator' => 'setOpeningDate'),
      'closing_date' => array('mutator' => 'setClosingDate'),
      'meeting_place' => array('mutator' => 'setMeetingPlace'),
      'wording' => array('mutator' => 'setWording'),
      'subjective' => array('mutator' => 'setSubjective'),
      'objective' => array('mutator' => 'setObjective'),
      'appreciation' => array('mutator' => 'setAppreciation'),
      'action_plan' => array('mutator' => 'setActionPlan'),
      'prescription' => array('mutator' => 'setPrescription')
    );

    return parent::Query($dsn);
  }

  /**
   * bool search(int $type, array $word, int $page, string $logical, int $limitFrom = 0)
   *
   * Executes a query search
   *
   * @param int $type one of the global constants
   * @param array (string) $word string(s) to search for
   * @param int $page What page should be returned if results are more than one page
   * @param string $logical logical operator to concatenate string(s) to search for
   * @param int $limitFrom (optional) maximum number of results
   * @return boolean returns false, if error occurs
   * @access public
   * @since 0.4
   */
  function search($type, $word, $page, $logical, $limitFrom = 0)
  {
    parent::_resetStats($page);

    // Building sql statements
    switch ($type)
    {
      case OPEN_SEARCH_WORDING:
        $field = "wording";
        break;

      case OPEN_SEARCH_SUBJECTIVE:
        $field = "subjective";
        break;

      case OPEN_SEARCH_OBJECTIVE:
        $field = "objective";
        break;

      case OPEN_SEARCH_APPRECIATION:
        $field = "appreciation";
        break;

      case OPEN_SEARCH_ACTIONPLAN:
        $field = "action_plan";
        break;

      case OPEN_SEARCH_PRESCRIPTION:
        $field = "prescription";
        break;

      default:
        $field = "no_field";
        break;
    }

    // Building sql statements
    $sql = " FROM " . $this->_table . " LEFT JOIN staff_tbl ON " . $this->_table . ".id_member=staff_tbl.id_member";
    $sql .= " WHERE ";

    $num = sizeof($word);
    if ($num > 1)
    {
      for ($i = 0; $i < ($num - 1); $i++)
      {
        if ($logical == OPEN_NOT)
        {
          $sql .= $field . " NOT LIKE '%" . $word[$i] . "%' AND ";
        }
        else
        {
          $sql .= $field . " LIKE '%" . $word[$i] . "%' " . $logical . " ";
        }
      }
    }
    if ($logical == OPEN_NOT)
    {
      $sql .= $field . " NOT LIKE '%" . $word[$num - 1] . "%'";
    }
    else
    {
      $sql .= $field . " LIKE '%" . $word[$num - 1] . "%'";
    }

    $sqlCount = "SELECT COUNT(*) AS row_count " . $sql;

    $sql = "SELECT " . $this->_table . ".*, staff_tbl.collegiate_number " . $sql;
    $sql .= " ORDER BY " . $field;
    // setting limit so we can page through the results
    $offset = ($this->_currentPage - 1) * intval($this->_itemsPerPage);
    if ($offset >= $limitFrom && $limitFrom > 0)
    {
      $offset = 0;
    }
    $limitTo = intval($this->_itemsPerPage);
    if ($limitTo > 0)
    {
      $sql .= " LIMIT " . $offset . "," . $limitTo . ";";
    }

    //Error::debug($limitFrom, "limitFrom"); // debug
    //Error::debug($offset, "offset"); // debug
    //Error::debug($sql, "sql"); // debug

    // Running row count sql statement
    if ( !$this->exec($sqlCount) )
    {
      return false;
    }

    $array = parent::fetchRow();
    parent::_calculateStats($array["row_count"], $limitFrom);
    if ( !$this->getRowCount() )
    {
      return false;
    }

    // Running search sql statement
    return $this->exec($sql);
  }

  /**
   * mixed getLastId(void)
   *
   * Executes a query
   *
   * @return mixed if error occurs returns false, else last insert id
   * @access public
   * @since 0.3
   */
  function getLastId()
  {
    $sql = "SELECT LAST_INSERT_ID() AS last_id";
    $sql .= " FROM " . $this->_table;

    if ( !$this->exec($sql) )
    {
      return false;
    }

    $array = parent::fetchRow();
    return ($array == false ? 0 : $array["last_id"]);
  }

  /**
   * mixed select(int $idProblem)
   *
   * Executes a query
   *
   * @param int $idProblem key of problem to select
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function select($idProblem)
  {
    $sql = "SELECT " . $this->_table . ".*, staff_tbl.collegiate_number";
    $sql .= " FROM " . $this->_table . " LEFT JOIN staff_tbl ON " . $this->_table . ".id_member=staff_tbl.id_member";
    $sql .= " WHERE id_problem=" . intval($idProblem);

    return $this->exec($sql);
  }

  /**
   * mixed selectProblems(int $idPatient, bool $closed = false)
   *
   * Executes a query to get the problems of a determinate patient
   *
   * @param int $idPatient key of patient to select medical problems
   * @param bool $closed (optional) indicate if the problems are closed or not
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function selectProblems($idPatient, $closed = false)
  {
    $sql = "SELECT " . $this->_table . ".*, staff_tbl.collegiate_number";
    $sql .= " FROM " . $this->_table . " LEFT JOIN staff_tbl ON " . $this->_table . ".id_member=staff_tbl.id_member";
    $sql .= " WHERE id_patient=" . intval($idPatient);
    $sql .= ($closed ? " AND (closing_date IS NOT NULL AND closing_date != '0000-00-00')" : " AND (closing_date IS NULL OR closing_date='0000-00-00')");
    $sql .= " ORDER BY order_number;";

    return ($this->exec($sql) ? $this->numRows() : false);
  }

  /**
   * mixed getLastOrderNumber(int $idPatient)
   *
   * Executes a query to get the last order number of the problem of a patient.
   *
   * @param int $idPatient key of patient to select order number
   * @return mixed if error occurs returns false, else last order number
   * @access public
   */
  function getLastOrderNumber($idPatient)
  {
    $sql = "SELECT MAX(order_number) AS last";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE id_patient=" . intval($idPatient);

    if ( !$this->exec($sql) )
    {
      return false;
    }

    $array = parent::fetchRow();

    return ($array == false ? 0 : $array["last"]);
  }

  /**
   * mixed fetch(void)
   *
   * Fetches a row from the query result and populates the Problem object.
   *
   * @return Problem returns user or false if no more problems to fetch
   * @access public
   */
  function fetch()
  {
    $array = parent::fetchRow();
    if ($array == false)
    {
      return false;
    }

    parent::_incrementRow();

    $problem = new Problem();
    foreach ($array as $key => $value)
    {
      $setProp = $this->_map[$key]['mutator'];
      if ($setProp && $value)
      {
        $problem->$setProp(urldecode($value));
      }
    }

    return $problem;
  }

  /**
   * bool insert(Problem $problem)
   *
   * Inserts a new medical problem into the problems table.
   *
   * @param Problem $problem medical problem to insert
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($problem)
  {
    if ( !$problem instanceof Problem )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    $sql = "INSERT INTO " . $this->_table;
    $sql .= " (id_problem, last_update_date, id_patient, id_member, order_number, ";
    $sql .= "opening_date, closing_date, meeting_place, wording, subjective, objective, ";
    $sql .= "appreciation, action_plan, prescription) VALUES (NULL, ";
    $sql .= "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

    $params = array(
      $problem->getLastUpdateDate(),
      $problem->getIdPatient(),
      $problem->getIdMember(),
      $problem->getOrderNumber(),
      $problem->getOpeningDate(),
      $problem->getClosingDate(),
      urlencode($problem->getMeetingPlace()),
      $problem->getWording(),
      urlencode($problem->getSubjective()),
      urlencode($problem->getObjective()),
      urlencode($problem->getAppreciation()),
      urlencode($problem->getActionPlan()),
      urlencode($problem->getPrescription())
    );

    return $this->exec($sql, $params);
  }

  /**
   * bool update(Problem $problem)
   *
   * Update a medical problem in the problems table.
   *
   * @param Problem $problem medical problem to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  function update($problem)
  {
    if ( !$problem instanceof Problem )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    $sql = "UPDATE " . $this->_table . " SET "
         . "last_update_date=CURDATE(), "
         . "id_member=?, "
         . "closing_date=?, "
         . "meeting_place=?, "
         . "wording=?, "
         . "subjective=?, "
         . "objective=?, "
         . "appreciation=?, "
         . "action_plan=?, "
         . "prescription=? "
         . "WHERE id_problem=?;";

    $params = array(
      $problem->getIdMember(),
      urlencode($problem->getClosingDate(false)),
      urlencode($problem->getMeetingPlace()),
      urlencode($problem->getWording()),
      urlencode($problem->getSubjective()),
      urlencode($problem->getObjective()),
      urlencode($problem->getAppreciation()),
      urlencode($problem->getActionPlan()),
      urlencode($problem->getPrescription()),
      $problem->getIdProblem()
    );

    return $this->exec($sql, $params);
  }

  /**
   * bool delete(int $idProblem)
   *
   * Deletes a medical problem from the problems table.
   *
   * @param string $idProblem key of medical problem to delete
   * @return boolean returns false, if error occurs
   * @access public
   */
  function delete($idProblem)
  {
    $sql = "DELETE FROM " . $this->_table;
    $sql .= " WHERE id_problem=" . intval($idProblem) . ";";

    return $this->exec($sql);
  }
} // end class
?>
