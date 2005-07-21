<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Problem_Page_Query.php,v 1.2 2005/07/21 15:59:46 jact Exp $
 */

/**
 * Problem_Page_Query.php
 *
 * Contains the class Problem_Page_Query
 *
 * Author: jact <jachavar@gmail.com>
 */

require_once("../classes/Page_Query.php");
require_once("../classes/Problem.php");

/**
 * Problem_Page_Query data access component for medical problems
 *
 * @author jact <jachavar@gmail.com>
 * @access public
 *
 * Methods:
 *  void Problem_Page_Query(void)
 *  bool search(int $type, array $word, int $page, string $logical, int $limitFrom = 0)
 *  mixed getLastId(void)
 *  mixed select(int $idProblem)
 *  mixed selectProblems(int $idPatient, bool $closed = false)
 *  mixed getLastOrderNumber(int $idPatient)
 *  mixed fetch(void)
 *  bool insert(Problem $problem)
 *  bool update(Problem $problem)
 *  bool delete(int $idProblem)
 */
class Problem_Page_Query extends Page_Query
{
  /**
   * void Problem_Page_Query(void)
   *
   * Constructor function
   *
   * @return void
   * @access public
   */
  function Problem_Page_Query()
  {
    $this->_table = "problem_tbl";
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
    // reset stats
    $this->_rowNumber = 0;
    $this->_currentRow = 0;
    $this->_currentPage = ($page > 1) ? intval($page) : 1;
    $this->_rowCount = 0;
    $this->_pageCount = 0;

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
    $countResult = $this->exec($sqlCount);
    if ($countResult == false)
    {
      $this->_error = "Error counting patient search results.";
      return false;
    }

    // Calculate stats based on row count
    $array = $this->fetchRow();
    $this->_rowCount = $array["row_count"];
    if ($limitFrom > 0 && $limitFrom < $this->_rowCount)
    {
      $this->_rowCount = $limitFrom;
    }
    $this->_pageCount = (intval($this->_itemsPerPage) > 0) ? ceil($this->_rowCount / $this->_itemsPerPage) : 1;

    // Running search sql statement
    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error searching problem information.";
      return false;
    }

    return $result;
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

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing last id information.";
      return false;
    }

    $array = $this->fetchRow();
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

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing medical problem information.";
      return false;
    }

    return $this->numRows();
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

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing medical problem information.";
      return false;
    }

    return $this->numRows();
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

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing medical problem information.";
      return false;
    }

    $array = $this->fetchRow();
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
    $array = $this->fetchRow();
    if ($array == false)
    {
      return false;
    }

    // increment rowNumber
    $this->_rowNumber = $this->_rowNumber + 1;
    $this->_currentRow = $this->_rowNumber + (($this->_currentPage - 1) * $this->_itemsPerPage);

    $problem = new Problem();
    $problem->setIdProblem(intval($array["id_problem"]));
    $problem->setLastUpdateDate(urldecode($array["last_update_date"]));
    $problem->setIdPatient(intval($array["id_patient"]));
    $problem->setIdMember(intval($array["id_member"]));
    $problem->setCollegiateNumber(urldecode($array["collegiate_number"]));
    $problem->setOrderNumber(intval($array["order_number"]));
    $problem->setOpeningDate(urldecode($array["opening_date"]));
    $problem->setClosingDate(urldecode($array["closing_date"]));
    $problem->setMeetingPlace(urldecode($array["meeting_place"]));
    $problem->setWording(urldecode($array["wording"]));
    $problem->setSubjective(urldecode($array["subjective"]));
    $problem->setObjective(urldecode($array["objective"]));
    $problem->setAppreciation(urldecode($array["appreciation"]));
    $problem->setActionPlan(urldecode($array["action_plan"]));
    $problem->setPrescription(urldecode($array["prescription"]));

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
    if (function_exists("is_a") && !is_a($problem, "Problem") ) // SF.net DEMO version PHP 4.1.2
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    $sql = "INSERT INTO " . $this->_table;
    $sql .= " (id_problem, last_update_date, id_patient, id_member, order_number, ";
    $sql .= "opening_date, closing_date, meeting_place, wording, subjective, objective, ";
    $sql .= "appreciation, action_plan, prescription) VALUES (NULL, ";
    $sql .= "'" . $problem->getLastUpdateDate() . "', ";
    $sql .= $problem->getIdPatient() . ", ";
    $sql .= ($problem->getIdMember() == 0) ? "NULL, " : $problem->getIdMember() . ", ";
    $sql .= $problem->getOrderNumber() . ", ";
    $sql .= "'" . $problem->getOpeningDate() . "', ";
    $sql .= "'" . $problem->getClosingDate() . "', ";
    $sql .= ($problem->getMeetingPlace() == "") ? "NULL, " : "'" . urlencode($problem->getMeetingPlace()) . "', ";
    $sql .= "'" . $problem->getWording() . "', ";
    $sql .= ($problem->getSubjective() == "") ? "NULL, " : "'" . urlencode($problem->getSubjective()) . "', ";
    $sql .= ($problem->getObjective() == "") ? "NULL, " : "'" . urlencode($problem->getObjective()) . "', ";
    $sql .= ($problem->getAppreciation() == "") ? "NULL, " : "'" . urlencode($problem->getAppreciation()) . "', ";
    $sql .= ($problem->getActionPlan() == "") ? "NULL, " : "'" . urlencode($problem->getActionPlan()) . "', ";
    $sql .= ($problem->getPrescription() == "") ? "NULL);" : "'" . urlencode($problem->getPrescription()) . "');";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error inserting new medical problem information.";
    }

    return $result;
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
    if (function_exists("is_a") && !is_a($problem, "Problem") ) // SF.net DEMO version PHP 4.1.2
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    $sql = "UPDATE " . $this->_table . " SET";
    $sql .= " last_update_date=curdate(),";
    $sql .= " id_member=" . (($problem->getIdMember() == 0) ? "NULL," : $problem->getIdMember() . ",");
    $sql .= " closing_date=" . (($problem->getClosingDate(false) == "") ? "NULL," : "'" . urlencode($problem->getClosingDate(false)) . "',");
    $sql .= " meeting_place=" . (($problem->getMeetingPlace() == "") ? "NULL," : "'" . urlencode($problem->getMeetingPlace()) . "',");
    $sql .= " wording='" . urlencode($problem->getWording()) . "',";
    $sql .= " subjective=" . (($problem->getSubjective() == "") ? "NULL," : "'" . urlencode($problem->getSubjective()) . "',");
    $sql .= " objective=" . (($problem->getObjective() == "") ? "NULL," : "'" . urlencode($problem->getObjective()) . "',");
    $sql .= " appreciation=" . (($problem->getAppreciation() == "") ? "NULL," : "'" . urlencode($problem->getAppreciation()) . "',");
    $sql .= " action_plan=" . (($problem->getActionPlan() == "") ? "NULL," : "'" . urlencode($problem->getActionPlan()) . "',");
    $sql .= " prescription=" . (($problem->getPrescription() == "") ? "NULL" : "'" . urlencode($problem->getPrescription()) . "'");
    $sql .= " WHERE id_problem=" . $problem->getIdProblem() . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error updating medical problem information.";
    }

    return $result;
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

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error deleting medical problem information.";
    }

    return $result;
  }
} // end class
?>
