<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: DelProblem_Query.php,v 1.5 2004/10/16 14:47:47 jact Exp $
 */

/**
 * DelProblem_Query.php
 ********************************************************************
 * Contains the class DelProblem_Query
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

require_once("../classes/Query.php");

/**
 * DelProblem_Query data access component for deleted medical problems
 ********************************************************************
 * @author jact <jachavar@terra.es>
 * @access public
 ********************************************************************
 * Methods:
 *  void DelProblem_Query(void)
 *  bool insert(Problem $problem, int $idUser, string $login)
 */
class DelProblem_Query extends Query
{
  /**
   * void DelProblem_Query(void)
   ********************************************************************
   * Constructor function
   ********************************************************************
   * @return void
   * @access public
   */
  function DelProblem_Query()
  {
    $this->_table = "deleted_problem_tbl";
  }

  /**
   * bool insert(Problem $problem, int $idUser, string $login)
   ********************************************************************
   * Inserts a new medical problem into the deleted problems table.
   ********************************************************************
   * @param Problem $problem medical problem to insert
   * @param int $idUser key of user that makes deletion
   * @param string $login login session of user that makes deletion
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($problem, $idUser, $login)
  {
    $sql = "INSERT INTO " . $this->_table;
    $sql .= " (id_problem, last_update_date, id_patient, id_member, collegiate_number, order_number, ";
    $sql .= "opening_date, closing_date, meeting_place, wording, subjective, objective, ";
    $sql .= "appreciation, action_plan, prescription, create_date, id_user, login) VALUES (";
    $sql .= $problem->getIdProblem() . ", ";
    $sql .= "'" . $problem->getLastUpdateDate() . "', ";
    $sql .= $problem->getIdPatient() . ", ";
    $sql .= ($problem->getIdMember() == 0) ? "NULL, " : $problem->getIdMember() . ", ";
    $sql .= ($problem->getCollegiateNumber() == "") ? "NULL, " : "'" . urlencode($problem->getCollegiateNumber()) . "', ";
    $sql .= $problem->getOrderNumber() . ", ";
    $sql .= "'" . $problem->getOpeningDate() . "', ";
    $sql .= "'" . $problem->getClosingDate() . "', ";
    $sql .= ($problem->getMeetingPlace() == "") ? "NULL, " : "'" . urlencode($problem->getMeetingPlace()) . "', ";
    $sql .= "'" . $problem->getWording() . "', ";
    $sql .= ($problem->getSubjective() == "") ? "NULL, " : "'" . urlencode($problem->getSubjective()) . "', ";
    $sql .= ($problem->getObjective() == "") ? "NULL, " : "'" . urlencode($problem->getObjective()) . "', ";
    $sql .= ($problem->getAppreciation() == "") ? "NULL, " : "'" . urlencode($problem->getAppreciation()) . "', ";
    $sql .= ($problem->getActionPlan() == "") ? "NULL, " : "'" . urlencode($problem->getActionPlan()) . "', ";
    $sql .= ($problem->getPrescription() == "") ? "NULL, " : "'" . urlencode($problem->getPrescription()) . "', ";

    $sql .= "NOW(), ";
    $sql .= intval($idUser) . ", ";
    $sql .= "'" . urlencode($login) . "');";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error inserting new deleted medical problem information.";
    }

    return $result;
  }
} // end class
?>
