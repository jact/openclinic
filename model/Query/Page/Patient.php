<?php
/**
 * Patient.php
 *
 * Contains the class Query_Page_Patient
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Patient.php,v 1.4 2013/01/07 18:04:52 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../Page.php");
require_once(dirname(__FILE__) . "/../../Patient.php");

/**
 * Query_Page_Patient data access component for patients
 *
 * Methods:
 *  bool Query_Page_Patient(array $dsn = null)
 *  bool search(int $type, array $word, int $page, string $logical, int $limitFrom = 0)
 *  mixed getLastId(void)
 *  mixed select(int $idPatient)
 *  mixed fetch(void)
 *  bool existName(string $firstName, string $surname1, string $surname2, int $idPatient = 0)
 *  bool insert(Patient $patient)
 *  bool update(Patient $patient)
 *  bool delete(int $idPatient)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Query_Page_Patient extends Query_Page
{
  /**
   * bool Query_Page_Patient(array $dsn = null)
   *
   * Constructor function
   *
   * @param array $dsn (optional) Data Source Name
   * @return boolean returns false, if error occurs
   * @access public
   */
  function Query_Page_Patient($dsn = null)
  {
    $this->_table = "patient_tbl";
    $this->_primaryKey = array("id_patient");

    $this->_map = array(
      'id_patient' => array('mutator' => 'setIdPatient'),
      //'last_update_date' => array('mutator' => 'setLastUpdateDate'),
      'id_member' => array('mutator' => 'setIdMember'),
      'collegiate_number' => array('mutator' => 'setCollegiateNumber'),
      'nif' => array('mutator' => 'setNIF'),
      'first_name' => array('mutator' => 'setFirstName'),
      'surname1' => array('mutator' => 'setSurname1'),
      'surname2' => array('mutator' => 'setSurname2'),
      'address' => array('mutator' => 'setAddress'),
      'phone_contact' => array('mutator' => 'setPhone'),
      'sex' => array('mutator' => 'setSex'),
      'race' => array('mutator' => 'setRace'),
      'birth_date' => array('mutator' => 'setBirthDate'),
      'age' => array('mutator' => 'setAge'),
      'birth_place' => array('mutator' => 'setBirthPlace'),
      'decease_date' => array('mutator' => 'setDeceaseDate'),
      'nts' => array('mutator' => 'setNTS'),
      'nss' => array('mutator' => 'setNSS'),
      'family_situation' => array('mutator' => 'setFamilySituation'),
      'labour_situation' => array('mutator' => 'setLabourSituation'),
      'education' => array('mutator' => 'setEducation'),
      'insurance_company' => array('mutator' => 'setInsuranceCompany')
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
   */
  function search($type, $word, $page, $logical, $limitFrom = 0)
  {
    parent::_resetStats($page);

    // Building sql statements
    switch ($type)
    {
      case OPEN_SEARCH_SURNAME1:
        $field = $this->_table . "." . "surname1";
        break;

      case OPEN_SEARCH_SURNAME2:
        $field = $this->_table . "." . "surname2";
        break;

      case OPEN_SEARCH_FIRSTNAME:
        $field = $this->_table . "." . "first_name";
        break;

      case OPEN_SEARCH_NIF:
        $field = $this->_table . "." . "nif";
        break;

      case OPEN_SEARCH_NTS:
        $field = $this->_table . "." . "nts";
        break;

      case OPEN_SEARCH_NSS:
        $field = $this->_table . "." . "nss";
        break;

      case OPEN_SEARCH_BIRTHPLACE:
        $field = $this->_table . "." . "birth_place";
        break;

      case OPEN_SEARCH_ADDRESS:
        $field = $this->_table . "." . "address";
        break;

      case OPEN_SEARCH_PHONE:
        $field = $this->_table . "." . "phone_contact";
        break;

      case OPEN_SEARCH_INSURANCE:
        $field = $this->_table . "." . "insurance_company";
        break;

      case OPEN_SEARCH_COLLEGIATE:
        $field = "staff_tbl.collegiate_number";
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

    $sql = "SELECT " . $this->_table . ".*, staff_tbl.collegiate_number, FLOOR((TO_DAYS(GREATEST(IF(decease_date='0000-00-00',CURRENT_DATE,decease_date),IFNULL(decease_date,CURRENT_DATE))) - TO_DAYS(birth_date)) / 365) AS age " . $sql;
    $sql .= " ORDER BY " . $this->_table . ".surname1, " . $this->_table . ".surname2, " . $this->_table . ".first_name";
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
   * mixed select(int $idPatient)
   *
   * Executes a query
   *
   * @param int $idPatient key of patient to select
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function select($idPatient)
  {
    $sql = "SELECT " . $this->_table . ".*, staff_tbl.collegiate_number, ";
    $sql .= "FLOOR((TO_DAYS(GREATEST(IF(decease_date='0000-00-00',CURRENT_DATE,decease_date),IFNULL(decease_date,CURRENT_DATE))) - TO_DAYS(birth_date)) / 365) AS age";
    $sql .= " FROM " . $this->_table . " LEFT JOIN staff_tbl ON " . $this->_table . ".id_member=staff_tbl.id_member";
    $sql .= " WHERE id_patient=" . intval($idPatient);

    return ($this->exec($sql) ? $this->numRows() : false);
  }

  /**
   * mixed fetch(void)
   *
   * Fetches a row from the query result and populates the Patient object.
   *
   * @return mixed returns patient or false if no more patients to fetch
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

    $patient = new Patient();
    foreach ($array as $key => $value)
    {
      $setProp = $this->_map[$key]['mutator'];
      if ($setProp && $value)
      {
        $patient->$setProp(urldecode($value));
      }
    }

    return $patient;
  }

  /**
   * bool existName(string $firstName, string $surname1, string $surname2, int $idPatient = 0)
   *
   * Returns true if patient name already exists
   *
   * @param string $firstName
   * @param string $surname1
   * @param string $surname2
   * @param int $idPatient (optional) key of patient
   * @return boolean returns true if name already exists
   * @access public
   */
  function existName($firstName, $surname1, $surname2, $idPatient = 0)
  {
    $sql = "SELECT COUNT(id_patient)";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE first_name='" . urlencode($firstName) . "'";
    $sql .= " AND surname1='" . urlencode($surname1) . "'";
    $sql .= " AND surname2='" . urlencode($surname2) . "'";
    if ($idPatient)
    {
      $sql .= " AND id_patient<>" . intval($idPatient);
    }

    if ( !$this->exec($sql) )
    {
      return false;
    }

    $array = parent::fetchRow(MYSQL_NUM);

    return ($array[0] > 0);
  }

  /**
   * bool insert(Patient $patient)
   *
   * Inserts a new patient into the database.
   *
   * @param Patient $patient patient to insert
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($patient)
  {
    if ( !$patient instanceof Patient )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    /*if ($this->existName($patient->getFirstName(), $patient->getSurname1(), $patient->getSurname2()))
    {
      $this->_isError = true;
      $this->_error = "Patient name, " . $patient->getFirstName();
      $this->_error .= " " . $patient->getSurname1();
      $this->_error .= " " . $patient->getSurname2() . ", is already in use.";
      return false;
    }*/

    $sql = "INSERT INTO " . $this->_table;
    $sql .= " (id_patient, nif, first_name, surname1, surname2, address, ";
    $sql .= "phone_contact, sex, race, birth_date, birth_place, decease_date, nts, nss, ";
    $sql .= "family_situation, labour_situation, education, insurance_company, ";
    $sql .= "id_member) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

    $params = array(
      //$patient->getLastUpdateDate(),
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
      $patient->getIdMember()
    );

    if ( !$this->exec($sql, $params) )
    {
      return false;
    }

    $sql = "INSERT INTO history_tbl (id_patient) VALUES (";
    $sql .= $this->getLastId() . ");";

    return $this->exec($sql);
  }

  /**
   * bool update(Patient $patient)
   *
   * Update a patient in the database.
   *
   * @param Patient $patient patient to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  function update($patient)
  {
    if ( !$patient instanceof Patient )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    /*if ($this->existName($patient->getFirstName(), $patient->getSurname1(), $patient->getSurname2(), $patient->getIdPatient()))
    {
      $this->_isError = true;
      $this->_error = "Patient name, " . $patient->getFirstName();
      $this->_error .= " " . $patient->getSurname1();
      $this->_error .= " " . $patient->getSurname2() . ", is already in use.";
      return false;
    }*/

    $sql = "UPDATE " . $this->_table . " SET "
         //. "last_update_date=CURDATE(), "
         . "nif=?, "
         . "first_name=?, "
         . "surname1=?, "
         . "surname2=?, "
         . "address=?, "
         . "phone_contact=?, "
         . "sex=?, "
         . "race=?, "
         . "birth_date=?, "
         . "birth_place=?, "
         . "decease_date=?, "
         . "nts=?, "
         . "nss=?, "
         . "family_situation=?, "
         . "labour_situation=?, "
         . "education=?, "
         . "insurance_company=?, "
         . "id_member=? "
         . "WHERE id_patient=?;";

    $params = array(
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
      $patient->getIdPatient()
    );

    return $this->exec($sql, $params);
  }

  /**
   * bool delete(int $idPatient)
   *
   * Deletes a patient from the database.
   *
   * @param int $idPatient key of patient to delete
   * @return boolean returns false, if error occurs
   * @access public
   */
  function delete($idPatient)
  {
    $sql = "DELETE FROM " . $this->_table;
    $sql .= " WHERE id_patient=" . intval($idPatient);

    if ( !$this->exec($sql) )
    {
      return false;
    }

    $sql = "DELETE FROM history_tbl";
    $sql .= " WHERE id_patient=" . intval($idPatient);

    return $this->exec($sql);
  }
} // end class
?>
