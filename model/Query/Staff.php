<?php
/**
 * Staff.php
 *
 * Contains the class Query_Staff
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Staff.php,v 1.3 2013/01/07 18:03:34 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../Query.php");
require_once(dirname(__FILE__) . "/../Staff.php");

/**
 * Query_Staff data access component for clinic staff members
 *
 * Methods:
 *  bool Query_Staff(array $dsn = null)
 *  mixed select(int $idMember = 0)
 *  mixed selectType(string $type = 'A')
 *  mixed fetch(void)
 *  bool existLogin(string $login, int $idMember = 0)
 *  bool insert(Staff $staff)
 *  bool update(Staff $staff)
 *  bool delete(int $idMember, int $idUser = 0)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Query_Staff extends Query
{
  /**
   * bool Query_Staff(array $dsn = null)
   *
   * Constructor function
   *
   * @param array $dsn (optional) Data Source Name
   * @return boolean returns false, if error occurs
   * @access public
   */
  function Query_Staff($dsn = null)
  {
    $this->_table = "staff_tbl";
    $this->_primaryKey = array("id_member");

    $this->_map = array(
      'id_member' => array('mutator' => 'setIdMember'),
      'member_type' => array('mutator' => 'setMemberType'),
      'collegiate_number' => array('mutator' => 'setCollegiateNumber'),
      'nif' => array('mutator' => 'setNIF'),
      'first_name' => array('mutator' => 'setFirstName'),
      'surname1' => array('mutator' => 'setSurname1'),
      'surname2' => array('mutator' => 'setSurname2'),
      'address' => array('mutator' => 'setAddress'),
      'phone_contact' => array('mutator' => 'setPhone'),
      'login' => array('mutator' => 'setLogin'),
      'id_user' => array('mutator' => 'setIdUser')
    );

    return parent::Query($dsn);
  }

  /**
   * mixed select(int $idMember = 0)
   *
   * Executes a query
   *
   * @param int $idMember (optional) key of staff member to select
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function select($idMember = 0)
  {
    $sql = "SELECT *";
    $sql .= " FROM " . $this->_table;
    if ($idMember)
    {
      $sql .= " WHERE id_member=" . intval($idMember);
    }
    $sql .= " ORDER BY first_name, surname1";

    return ($this->exec($sql) ? $this->numRows() : false);
  }

  /**
   * mixed selectType(string $type = 'A')
   *
   * Executes a query
   *
   * @param string $type (optional) type of staff member to select
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function selectType($type = 'A')
  {
    switch ($type)
    {
      case 'A':
        $type = OPEN_ADMINISTRATIVE;
        break;

      case 'D':
        $type = OPEN_DOCTOR;
        break;
    }

    $sql = "SELECT *";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE member_type='" . $type . "'";
    $sql .= " ORDER BY first_name, surname1";

    return ($this->exec($sql) ? $this->numRows() : false);
  }

  /**
   * mixed fetch(void)
   *
   * Fetches a row from the query result and populates the Staff object.
   *
   * @return mixed returns staff member or false if no more staff members to fetch
   * @access public
   */
  function fetch()
  {
    $array = parent::fetchRow();
    if ($array == false)
    {
      return false;
    }

    $staff = new Staff();
    foreach ($array as $key => $value)
    {
      $setProp = $this->_map[$key]['mutator'];
      if ($setProp && $value)
      {
        $staff->$setProp(urldecode($value));
      }
    }

    return $staff;
  }

  /**
   * bool existLogin(string $login, int $idMember = 0)
   *
   * Returns true if login already exists
   *
   * @param string $login staff member login
   * @param int $idMember (optional) key of staff member
   * @return boolean returns true if login already exists or false if error occurs
   * @access public
   */
  function existLogin($login, $idMember = 0)
  {
    $sql = "SELECT COUNT(login)";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE login='" . urlencode($login) . "'"; // in BD it is urlencodeaded
    if ($idMember)
    {
      $sql .= " AND id_member<>" . intval($idMember);
    }

    if ( !$this->exec($sql) )
    {
      return false;
    }

    $array = parent::fetchRow(MYSQL_NUM);

    return ($array[0] > 0);
  }

  /**
   * bool insert(Staff $staff)
   *
   * Inserts a new staff member into the staff table.
   *
   * @param Staff $staff staff member to insert
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($staff)
  {
    if ( !$staff instanceof Staff )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    /*if ($this->existLogin($staff->getLogin()))
    {
      $this->_isError = true;
      $this->_error = "Login is already in use.";
      return false;
    }*/

    //Error::debug($staff, "", true); // debug
    $sql = "INSERT INTO " . $this->_table;
    $sql .= " (id_member, member_type, collegiate_number, nif, first_name, surname1, ";
    $sql .= "surname2, address, phone_contact, id_user, login) VALUES (NULL, ";
    $sql .= "?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

    $params = array(
      $staff->getMemberType(),
      urlencode($staff->getCollegiateNumber()),
      urlencode($staff->getNIF()),
      urlencode($staff->getFirstName()),
      urlencode($staff->getSurname1()),
      urlencode($staff->getSurname2()),
      urlencode($staff->getAddress()),
      urlencode($staff->getPhone()),
      $staff->getIdUser(),
      urlencode($staff->getLogin())
    );

    return $this->exec($sql, $params);
  }

  /**
   * bool update(Staff $staff)
   *
   * Update a staff member in the staff table.
   *
   * @param Staff $staff staff member to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  function update($staff)
  {
    if ( !$staff instanceof Staff )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    // If changing login check to see if it already exists.
    /*if ($this->existLogin($staff->getLogin(), $staff->getIdMember()))
    {
      $this->_isError = true;
      $this->_error = "Login is already in use.";
      return false;
    }*/

    $sql = "UPDATE " . $this->_table . " SET "
         . "collegiate_number=?, "
         . "nif=?, "
         . "first_name=?, "
         . "surname1=?, "
         . "surname2=?, "
         . "address=?, "
         . "phone_contact=?, "
         . "login=? "
         . "WHERE id_member=?;";

    $params = array(
      urlencode($staff->getCollegiateNumber()),
      urlencode($staff->getNIF()),
      urlencode($staff->getFirstName()),
      urlencode($staff->getSurname1()),
      urlencode($staff->getSurname2()),
      urlencode($staff->getAddress()),
      urlencode($staff->getPhone()),
      urlencode($staff->getLogin()),
      $staff->getIdMember()
    );

    return $this->exec($sql, $params);
  }

  /**
   * bool delete(int $idMember, int $idUser = 0)
   *
   * Deletes a staff member from the staff table.
   *
   * @param string $idMember key of staff member to delete
   * @param string $idUser key of user to delete
   * @return boolean returns false, if error occurs
   * @access public
   */
  function delete($idMember, $idUser = 0)
  {
    $sql = "DELETE FROM " . $this->_table;
    $sql .= " WHERE id_member=" . intval($idMember) . ";";

    $result = $this->exec($sql);

    if ($idUser == 0)
    {
      return $result;
    }

    $sql = "DELETE FROM user_tbl";
    $sql .= " WHERE id_user=" . intval($idUser) . ";";

    return $this->exec($sql);
  }
} // end class
?>
