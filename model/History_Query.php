<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: History_Query.php,v 1.6 2004/08/23 17:58:43 jact Exp $
 */

/**
 * History_Query.php
 ********************************************************************
 * Contains the class History_Query
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

require_once("../classes/Query.php");
require_once("../classes/History.php");

/**
 * History_Query data access component for History class
 ********************************************************************
 * @author jact <jachavar@terra.es>
 * @access public
 ********************************************************************
 * Methods:
 *  void History_Query(void)
 *  mixed selectPersonal(int $idPatient = 0)
 *  mixed selectFamily(int $idPatient = 0)
 *  mixed fetchPersonal(void)
 *  mixed fetchFamily(void)
 *  bool updatePersonal(History $history)
 *  bool updateFamily(History $history)
 */
class History_Query extends Query
{
  /**
   * void History_Query(void)
   ********************************************************************
   * Constructor function
   ********************************************************************
   * @return void
   * @access public
   */
  function History_Query()
  {
    $this->_table = "history_tbl";
  }

  /**
   * mixed selectPersonal(int $idPatient = 0)
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @param int $idPatient key of history to select
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function selectPersonal($idPatient = 0)
  {
    $sql = "SELECT id_patient,birth_growth,growth_sexuality,feed,habits,";
    $sql .= "peristaltic_conditions,psychological,children_complaint,venereal_disease,";
    $sql .= "accident_surgical_operation,medicinal_intolerance,mental_illness";
    $sql .= " FROM " . $this->_table;
    if ($idPatient > 0)
    {
      $sql .= " WHERE id_patient=" . intval($idPatient);
    }
    $sql .= " ORDER BY id_patient";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing history information.";
      return false;
    }

    return $this->numRows();
  }

  /**
   * mixed selectFamily(int $idPatient = 0)
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @param int $idPatient key of history to select
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function selectFamily($idPatient = 0)
  {
    $sql = "SELECT id_patient,parents_status_health,brothers_status_health,";
    $sql .= "spouse_childs_status_health,family_illness";
    $sql .= " FROM " . $this->_table;
    if ($idPatient > 0)
    {
      $sql .= " WHERE id_patient=" . intval($idPatient);
    }
    $sql .= " ORDER BY id_patient";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing history information.";
      return false;
    }

    return $this->numRows();
  }

  /**
   * mixed fetchPersonal(void)
   ********************************************************************
   * Fetches a row from the query result and populates the History object.
   ********************************************************************
   * @return History returns personal antecedents or false if no more histories to fetch
   * @access public
   */
  function fetchPersonal()
  {
    $array = $this->fetchRow();
    if ($array == false)
    {
      return false;
    }

    $history = new History();
    $history->setIdPatient(intval($array["id_patient"]));

    $history->setBirthGrowth(urldecode($array["birth_growth"]));
    $history->setGrowthSexuality(urldecode($array["growth_sexuality"]));
    $history->setFeed(urldecode($array["feed"]));
    $history->setHabits(urldecode($array["habits"]));
    $history->setPeristalticConditions(urldecode($array["peristaltic_conditions"]));
    $history->setPsychological(urldecode($array["psychological"]));
    $history->setChildrenComplaint(urldecode($array["children_complaint"]));
    $history->setVenerealDisease(urldecode($array["venereal_disease"]));
    $history->setAccidentSurgicalOperation(urldecode($array["accident_surgical_operation"]));
    $history->setMedicinalIntolerance(urldecode($array["medicinal_intolerance"]));
    $history->setMentalIllness(urldecode($array["mental_illness"]));

    return $history;
  }

  /**
   * mixed fetchFamily(void)
   ********************************************************************
   * Fetches a row from the query result and populates the History object.
   ********************************************************************
   * @return History returns family antecedents or false if no more histories to fetch
   * @access public
   */
  function fetchFamily()
  {
    $array = $this->fetchRow();
    if ($array == false)
    {
      return false;
    }

    $history = new History();
    $history->setIdPatient(intval($array["id_patient"]));

    $history->setParentsStatusHealth(urldecode($array["parents_status_health"]));
    $history->setBrothersStatusHealth(urldecode($array["brothers_status_health"]));
    $history->setSpouseChildsStatusHealth(urldecode($array["spouse_childs_status_health"]));
    $history->setFamilyIllness(urldecode($array["family_illness"]));

    return $history;
  }

  /**
   * bool updatePersonal(History $history)
   ********************************************************************
   * Update personal antecedents in the history table.
   ********************************************************************
   * @param History $history personal antecedents to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  function updatePersonal($history)
  {
    if (function_exists("is_a") && !is_a($history, "History") ) // SF.net DEMO version PHP 4.1.2
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    $sql = "UPDATE " . $this->_table . " SET ";
    $sql .= "birth_growth=" . (($history->getBirthGrowth() == "") ? "NULL, " : "'" . urlencode($history->getBirthGrowth()) . "', ");
    $sql .= "growth_sexuality=" . (($history->getGrowthSexuality() == "") ? "NULL, " : "'" . urlencode($history->getGrowthSexuality()) . "', ");
    $sql .= "feed=" . (($history->getFeed() == "") ? "NULL, " : "'" . urlencode($history->getFeed()) . "', ");
    $sql .= "habits=" . (($history->getHabits() == "") ? "NULL, " : "'" . urlencode($history->getHabits()) . "', ");
    $sql .= "peristaltic_conditions=" . (($history->getPeristalticConditions() == "") ? "NULL, " : "'" . urlencode($history->getPeristalticConditions()) . "', ");
    $sql .= "psychological=" . (($history->getPsychological() == "") ? "NULL, " : "'" . urlencode($history->getPsychological()) . "', ");
    $sql .= "children_complaint=" . (($history->getChildrenComplaint() == "") ? "NULL, " : "'" . urlencode($history->getChildrenComplaint()) . "', ");
    $sql .= "venereal_disease=" . (($history->getVenerealDisease() == "") ? "NULL, " : "'" . urlencode($history->getVenerealDisease()) . "', ");
    $sql .= "accident_surgical_operation=" . (($history->getAccidentSurgicalOperation() == "") ? "NULL, " : "'" . urlencode($history->getAccidentSurgicalOperation()) . "', ");
    $sql .= "medicinal_intolerance=" . (($history->getMedicinalIntolerance() == "") ? "NULL, " : "'" . urlencode($history->getMedicinalIntolerance()) . "', ");
    $sql .= "mental_illness=" . (($history->getMentalIllness() == "") ? "NULL " : "'" . urlencode($history->getMentalIllness()) . "' ");
    $sql .= " WHERE id_patient=" . $history->getIdPatient() . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error updating personal antecedents.";
    }

    return $result;
  }

  /**
   * bool updateFamily(History $history)
   ********************************************************************
   * Update family antecedents in the history table.
   ********************************************************************
   * @param History $history family antecedents to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  function updateFamily($history)
  {
    if (function_exists("is_a") && !is_a($history, "History") ) // SF.net DEMO version PHP 4.1.2
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    $sql = "UPDATE " . $this->_table . " SET ";
    $sql .= "parents_status_health=" . (($history->getParentsStatusHealth() == "") ? "NULL, " : "'" . urlencode($history->getParentsStatusHealth()) . "', ");
    $sql .= "brothers_status_health=" . (($history->getBrothersStatusHealth() == "") ? "NULL, " : "'" . urlencode($history->getBrothersStatusHealth()) . "', ");
    $sql .= "spouse_childs_status_health=" . (($history->getSpouseChildsStatusHealth() == "") ? "NULL, " : "'" . urlencode($history->getSpouseChildsStatusHealth()) . "', ");
    $sql .= "family_illness=" . (($history->getFamilyIllness() == "") ? "NULL " : "'" . urlencode($history->getFamilyIllness()) . "' ");
    $sql .= " WHERE id_patient=" . $history->getIdPatient() . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error updating family antecedents.";
    }

    return $result;
  }
} // end class
?>
