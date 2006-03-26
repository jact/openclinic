<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: DelPatient_Query.php,v 1.9 2006/03/26 16:12:34 jact Exp $
 */

/**
 * DelPatient_Query.php
 *
 * Contains the class DelPatient_Query
 *
 * @author jact <jachavar@gmail.com>
 */

require_once("../classes/Query.php");

/**
 * DelPatient_Query data access component for deleted patients
 *
 * Methods:
 *  void DelPatient_Query(void)
 *  bool insert(Patient $patient, History $historyP, History $historyF, int $idUser, string $login)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class DelPatient_Query extends Query
{
  /**
   * void DelPatient_Query(void)
   *
   * Constructor function
   *
   * @return void
   * @access public
   */
  function DelPatient_Query()
  {
    $this->_table = "deleted_patient_tbl";
    $this->_primaryKey = null;
  }

  /**
   * bool insert(Patient $patient, History $historyP, History $historyF, int $idUser, string $login)
   *
   * Inserts a new deleted patient into the database.
   *
   * @param Patient $patient patient to insert
   * @param History $historyP patient's personal antecedents to insert
   * @param History $historyF patient's family antecedents to insert
   * @param int $idUser key of user that makes deletion
   * @param string $login login session of user that makes deletion
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($patient, $historyP, $historyF, $idUser, $login)
  {
    $sql = "INSERT INTO " . $this->_table;
    $sql .= " (id_patient, nif, first_name, surname1, surname2, address, phone_contact, ";
    $sql .= "sex, race, birth_date, birth_place, decease_date, nts, nss, ";
    $sql .= "family_situation, labour_situation, education, insurance_company, ";
    $sql .= "id_member, collegiate_number, birth_growth, growth_sexuality, feed, habits, ";
    $sql .= "peristaltic_conditions, psychological, children_complaint, venereal_disease, ";
    $sql .= "accident_surgical_operation, medicinal_intolerance, mental_illness, ";
    $sql .= "parents_status_health, brothers_status_health, spouse_childs_status_health, ";
    $sql .= "family_illness, create_date, id_user, login) VALUES (";
    $sql .= "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ";
    $sql .= "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ";
    $sql .= "?, ?, ?, ?, NOW(), ?, ?);";

    $params = array(
      $patient->getIdPatient(),
      urlencode($patient->getNIF()),
      urlencode($patient->getFirstName()),
      urlencode($patient->getSurname1()),
      urlencode($patient->getSurname2()),
      urlencode($patient->getAddress()),
      urlencode($patient->getPhone()),
      $patient->getSex(),
      urlencode($patient->getRace()),
      $patient->getBirthDate(),
      urlencode($patient->getBirthPlace()),
      $patient->getDeceaseDate(),
      urlencode($patient->getNTS()),
      urlencode($patient->getNSS()),
      urlencode($patient->getFamilySituation()),
      urlencode($patient->getLabourSituation()),
      urlencode($patient->getEducation()),
      urlencode($patient->getInsuranceCompany()),
      $patient->getIdMember(),
      urlencode($patient->getCollegiateNumber()),
      urlencode($historyP->getBirthGrowth()),
      urlencode($historyP->getGrowthSexuality()),
      urlencode($historyP->getFeed()),
      urlencode($historyP->getHabits()),
      urlencode($historyP->getPeristalticConditions()),
      urlencode($historyP->getPsychological()),
      urlencode($historyP->getChildrenComplaint()),
      urlencode($historyP->getVenerealDisease()),
      urlencode($historyP->getAccidentSurgicalOperation()),
      urlencode($historyP->getMedicinalIntolerance()),
      urlencode($historyP->getMentalIllness()),
      urlencode($historyF->getParentsStatusHealth()),
      urlencode($historyF->getBrothersStatusHealth()),
      urlencode($historyF->getSpouseChildsStatusHealth()),
      urlencode($historyF->getFamilyIllness()),
      intval($idUser),
      urlencode($login)
    );

    return $this->exec($sql, $params);
  }
} // end class
?>
