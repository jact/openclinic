<?php
/**
 * History.php
 *
 * Contains the class Query_History
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: History.php,v 1.3 2013/01/07 18:02:55 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../Query.php");
require_once(dirname(__FILE__) . "/../History.php");

/**
 * Query_History data access component for History class
 *
 * Methods:
 *  bool Query_History(array $dsn = null)
 *  mixed selectPersonal(int $idPatient = 0)
 *  mixed selectFamily(int $idPatient = 0)
 *  mixed fetch(void)
 *  bool updatePersonal(History $history)
 *  bool updateFamily(History $history)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Query_History extends Query
{
  /**
   * bool Query_History(array $dsn = null)
   *
   * Constructor function
   *
   * @param array $dsn (optional) Data Source Name
   * @return boolean returns false, if error occurs
   * @access public
   */
  function Query_History($dsn = null)
  {
    $this->_table = "history_tbl";
    $this->_primaryKey = array("id_patient");

    $this->_map = array(
      'id_patient' => array('mutator' => 'setIdPatient'),
      'birth_growth' => array('mutator' => 'setBirthGrowth'),
      'growth_sexuality' => array('mutator' => 'setGrowthSexuality'),
      'feed' => array('mutator' => 'setFeed'),
      'habits' => array('mutator' => 'setHabits'),
      'peristaltic_conditions' => array('mutator' => 'setPeristalticConditions'),
      'psychological' => array('mutator' => 'setPsychological'),
      'children_complaint' => array('mutator' => 'setChildrenComplaint'),
      'venereal_disease' => array('mutator' => 'setVenerealDisease'),
      'accident_surgical_operation' => array('mutator' => 'setAccidentSurgicalOperation'),
      'medicinal_intolerance' => array('mutator' => 'setMedicinalIntolerance'),
      'mental_illness' => array('mutator' => 'setMentalIllness'),
      'parents_status_health' => array('mutator' => 'setParentsStatusHealth'),
      'brothers_status_health' => array('mutator' => 'setBrothersStatusHealth'),
      'spouse_childs_status_health' => array('mutator' => 'setSpouseChildsStatusHealth'),
      'family_illness' => array('mutator' => 'setFamilyIllness')
    );

    return parent::Query($dsn);
  }

  /**
   * mixed selectPersonal(int $idPatient = 0)
   *
   * Executes a query
   *
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
    if ($idPatient)
    {
      $sql .= " WHERE id_patient=" . intval($idPatient);
    }
    $sql .= " ORDER BY id_patient";

    return ($this->exec($sql) ? $this->numRows() : false);
  }

  /**
   * mixed selectFamily(int $idPatient = 0)
   *
   * Executes a query
   *
   * @param int $idPatient key of history to select
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function selectFamily($idPatient = 0)
  {
    $sql = "SELECT id_patient,parents_status_health,brothers_status_health,";
    $sql .= "spouse_childs_status_health,family_illness";
    $sql .= " FROM " . $this->_table;
    if ($idPatient)
    {
      $sql .= " WHERE id_patient=" . intval($idPatient);
    }
    $sql .= " ORDER BY id_patient";

    return ($this->exec($sql) ? $this->numRows() : false);
  }

  /**
   * mixed fetch(void)
   *
   * Fetches a row from the query result and populates the History object.
   *
   * @return History returns family antecedents or false if no more histories to fetch
   * @access public
   */
  function fetch()
  {
    $array = parent::fetchRow();
    if ($array == false)
    {
      return false;
    }

    $history = new History();
    foreach ($array as $key => $value)
    {
      $setProp = $this->_map[$key]['mutator'];
      if ($setProp && $value)
      {
        $history->$setProp(urldecode($value));
      }
    }

    return $history;
  }

  /**
   * bool updatePersonal(History $history)
   *
   * Update personal antecedents in the history table.
   *
   * @param History $history personal antecedents to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  function updatePersonal($history)
  {
    if ( !$history instanceof History )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    $sql = "UPDATE " . $this->_table . " SET "
         . "birth_growth=?, "
         . "growth_sexuality=?, "
         . "feed=?, "
         . "habits=?, "
         . "peristaltic_conditions=?, "
         . "psychological=?, "
         . "children_complaint=?, "
         . "venereal_disease=?, "
         . "accident_surgical_operation=?, "
         . "medicinal_intolerance=?, "
         . "mental_illness=? "
         . "WHERE id_patient=?;";

    $params = array(
      urlencode($history->getBirthGrowth()),
      urlencode($history->getGrowthSexuality()),
      urlencode($history->getFeed()),
      urlencode($history->getHabits()),
      urlencode($history->getPeristalticConditions()),
      urlencode($history->getPsychological()),
      urlencode($history->getChildrenComplaint()),
      urlencode($history->getVenerealDisease()),
      urlencode($history->getAccidentSurgicalOperation()),
      urlencode($history->getMedicinalIntolerance()),
      urlencode($history->getMentalIllness()),
      $history->getIdPatient()
    );

    return $this->exec($sql, $params);
  }

  /**
   * bool updateFamily(History $history)
   *
   * Update family antecedents in the history table.
   *
   * @param History $history family antecedents to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  function updateFamily($history)
  {
    if ( !$history instanceof History )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    $sql = "UPDATE " . $this->_table . " SET "
         . "parents_status_health=?, "
         . "brothers_status_health=?, "
         . "spouse_childs_status_health=?, "
         . "family_illness=? "
         . "WHERE id_patient=?;";

    $params = array(
      urlencode($history->getParentsStatusHealth()),
      urlencode($history->getBrothersStatusHealth()),
      urlencode($history->getSpouseChildsStatusHealth()),
      urlencode($history->getFamilyIllness()),
      $history->getIdPatient()
    );

    return $this->exec($sql, $params);
  }
} // end class
?>
