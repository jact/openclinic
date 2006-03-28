<?php
/**
 * DelProblem_Query.php
 *
 * Contains the class DelProblem_Query
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: DelProblem_Query.php,v 1.11 2006/03/28 19:06:39 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once("../classes/Query.php");

/**
 * DelProblem_Query data access component for deleted medical problems
 *
 * Methods:
 *  void DelProblem_Query(void)
 *  bool insert(Problem $problem, int $idUser, string $login)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class DelProblem_Query extends Query
{
  /**
   * void DelProblem_Query(void)
   *
   * Constructor function
   *
   * @return void
   * @access public
   */
  function DelProblem_Query()
  {
    $this->_table = "deleted_problem_tbl";
    $this->_primaryKey = null;
  }

  /**
   * bool insert(Problem $problem, int $idUser, string $login)
   *
   * Inserts a new medical problem into the deleted problems table.
   *
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
    $sql .= "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?);";

    $params = array(
      $problem->getIdProblem(),
      $problem->getLastUpdateDate(),
      $problem->getIdPatient(),
      $problem->getIdMember(),
      urlencode($problem->getCollegiateNumber()),
      $problem->getOrderNumber(),
      $problem->getOpeningDate(),
      $problem->getClosingDate(),
      urlencode($problem->getMeetingPlace()),
      $problem->getWording(),
      urlencode($problem->getSubjective()),
      urlencode($problem->getObjective()),
      urlencode($problem->getAppreciation()),
      urlencode($problem->getActionPlan()),
      urlencode($problem->getPrescription()),
      intval($idUser),
      urlencode($login)
    );

    return $this->exec($sql, $params);
  }
} // end class
?>
