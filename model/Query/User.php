<?php
/**
 * User.php
 *
 * Contains the class Query_User
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: User.php,v 1.3 2013/01/07 18:04:27 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../Query.php");
require_once(dirname(__FILE__) . "/../User.php");

/**
 * Query_User data access component for users
 *
 * Methods:
 *  bool Query_User(array $dsn = null)
 *  mixed select(int $idUser = 0)
 *  mixed selectLogins(void)
 *  bool existLogin(string $login, int $idMember = 0)
 *  mixed verifySignOn(string $login, string $pwd, bool $onlyCheck = false)
 *  bool isActivated(string $login)
 *  bool deactivate(string $login)
 *  mixed fetch(void)
 *  bool insert(User $user)
 *  bool update(User $user)
 *  bool resetPwd(User $user)
 *  bool delete(int $idUser)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Query_User extends Query
{
  /**
   * bool Query_User(array $dsn = null)
   *
   * Constructor function
   *
   * @param array $dsn (optional) Data Source Name
   * @return boolean returns false, if error occurs
   * @access public
   */
  function Query_User($dsn = null)
  {
    $this->_table = "user_tbl";
    $this->_primaryKey = array("id_user");

    $this->_map = array(
      'id_user' => array(/*'accessor' => 'getIdUser',*/ 'mutator' => 'setIdUser'),
      'id_member' => array(/*'accessor' => 'getIdMember',*/ 'mutator' => 'setIdMember'),
      'login' => array(/*'accessor' => 'getLogin',*/ 'mutator' => 'setLogin'),
      'pwd' => array(/*'accessor' => 'getPwd',*/ 'mutator' => 'setPwd'),
      'email' => array(/*'accessor' => 'getEmail',*/ 'mutator' => 'setEmail'),
      'actived' => array(/*'accessor' => 'isActived',*/ 'mutator' => 'setActived'),
      'id_theme' => array(/*'accessor' => 'getIdTheme',*/ 'mutator' => 'setIdTheme'),
      'id_profile' => array(/*'accessor' => 'getIdProfile',*/ 'mutator' => 'setIdProfile')
    );

    return parent::Query($dsn);
  }

  /**
   * mixed select(int $idUser = 0)
   *
   * Executes a query
   *
   * @param int $idUser (optional) key of user to select
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function select($idUser = 0)
  {
    $sql = "SELECT login,id_member," . $this->_table . ".*";
    $sql .= " FROM staff_tbl," . $this->_table;
    $sql .= " WHERE " . $this->_table . ".id_user=staff_tbl.id_user";
    if ($idUser)
    {
      $sql .= " AND " . $this->_table . ".id_user=" . intval($idUser);
    }
    $sql .= " ORDER BY login;";

    return ($this->exec($sql) ? $this->numRows() : false);
  }

  /**
   * mixed selectLogins(void)
   *
   * Executes a query
   *
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function selectLogins()
  {
    $sql = "SELECT id_member,login";
    $sql .= " FROM staff_tbl";
    $sql .= " WHERE id_user IS NULL";
    $sql .= " AND login IS NOT NULL";
    $sql .= " ORDER BY login;";

    return ($this->exec($sql) ? $this->numRows() : false);
  }

  /**
   * bool existLogin(string $login, int $idMember = 0)
   *
   * Executes a query
   *
   * @param string $login login of user to know if exists
   * @param int $idMember (optional) key of staff member
   * @return boolean returns true if login already exists or false if error occurs
   * @access public
   */
  function existLogin($login, $idMember = 0)
  {
    $sql = "SELECT COUNT(login)";
    $sql .= " FROM staff_tbl";
    $sql .= " WHERE login='" . urlencode($login) . "'";
    if ($idMember > 0)
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
   * mixed verifySignOn(string $login, string $pwd, bool $onlyCheck = false)
   *
   * Executes a query to verify a sign on login and password
   *
   * @param string $login login of user to select
   * @param string $pwd password of staff member to select
   * @param bool $onlyCheck (optional)
   * @return mixed returns false, if error occurs, otherwise: if $onlyCheck is true returns a boolean, else returns an id result
   * @access public
   */
  function verifySignOn($login, $pwd, $onlyCheck = false)
  {
    $sql = "SELECT login,id_member," . $this->_table . ".*";
    $sql .= " FROM staff_tbl," . $this->_table;
    $sql .= " WHERE " . $this->_table . ".id_user=staff_tbl.id_user";
    $sql .= " AND login='" . urlencode($login) . "'";
    $sql .= " AND pwd='" . urlencode($pwd) . "'"; // md5 from form

    $result = $this->exec($sql);

    return ($onlyCheck ? $this->numRows() > 0 : $result);
  }

  /**
   * bool isActivated(string $login)
   *
   * Verifies if a login user is activated or not
   *
   * @param string $login login of user to see if is deactivated
   * @return boolean returns false, if error occurs or user is deactivated
   * @access public
   * @since 0.7
   */
  function isActivated($login)
  {
    $sql = "SELECT id_user";
    $sql .= " FROM staff_tbl";
    $sql .= " WHERE login='" . urlencode($login) . "';";

    if ( !$this->exec($sql) )
    {
      return false;
    }

    $array = parent::fetchRow();
    if ( !$array )
    {
      return false;
    }

    $sql = "SELECT actived";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE id_user=" . intval($array['id_user']);

    if ( !$this->exec($sql) )
    {
      return false;
    }

    $array = parent::fetchRow();
    if ( !$array )
    {
      return false;
    }

    return ($array['actived'] == "Y");
  }

  /**
   * bool deactivate(string $login)
   *
   * Updates an user and sets the actived flag to No.
   *
   * @param string $login login of user to deactivate
   * @return boolean returns false, if error occurs
   * @access public
   */
  function deactivate($login)
  {
    $sql = "SELECT id_user";
    $sql .= " FROM staff_tbl";
    $sql .= " WHERE login='" . urlencode($login) . "';";

    if ( !$this->exec($sql) )
    {
      return false;
    }

    $array = parent::fetchRow();
    if ( !$array )
    {
      return false;
    }

    $sql = "UPDATE " . $this->_table . " SET actived='N'";
    $sql .= " WHERE id_user=" . intval($array['id_user']) . ";";

    return $this->exec($sql);
  }

  /**
   * mixed fetch(void)
   *
   * Fetches a row from the query result and populates the User object
   *
   * @return User returns user or false if no more users to fetch
   * @access public
   */
  function fetch()
  {
    $array = parent::fetchRow();
    if ($array == false)
    {
      return false;
    }

    $user = new User();
    foreach ($array as $key => $value)
    {
      $setProp = $this->_map[$key]['mutator'];
      if ($setProp && $value)
      {
        $user->$setProp(urldecode($value));
      }
    }

    return $user;
  }

  /**
   * bool insert(User $user)
   *
   * Inserts a new user into the users table.
   *
   * @param User $user user to insert
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($user)
  {
    if ( !$user instanceof User )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    $sql = "INSERT INTO " . $this->_table;
    $sql .= " (id_user, pwd, email, actived, id_theme, id_profile) VALUES (NULL, ";
    $sql .= "?, ?, ?, ?, ?);";

    $params = array(
      urlencode($user->getPwd()), // md5 from form
      urlencode($user->getEmail()),
      ($user->isActived() ? "Y" : "N"),
      $user->getIdTheme(),
      $user->getIdProfile()
    );

    if ( !$this->exec($sql, $params) )
    {
      return false;
    }

    $sql = "UPDATE staff_tbl SET";
    $sql .= " id_user=LAST_INSERT_ID()";
    $sql .= " WHERE id_member=" . $user->getIdMember() . ";";

    return $this->exec($sql);
  }

  /**
   * bool update(User $user)
   *
   * Update an user in the users table.
   *
   * @param User $user user to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  function update($user)
  {
    if ( !$user instanceof User )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    if ($this->existLogin($user->getLogin(), $user->getIdMember()))
    {
      $this->_isError = true;
      $this->_error = "Login is already in use.";
      return false;
    }

    $sql = "UPDATE staff_tbl SET";
    $sql .= " login='" . urlencode($user->getLogin()) . "'";
    $sql .= " WHERE id_user=" . $user->getIdUser() . ";";

    if ( !$this->exec($sql) )
    {
      return false;
    }

    $sql = "UPDATE " . $this->_table . " SET "
         //. "last_updated_date = CURDATE(), "
         . "email=?, "
         . "actived=?, "
         . "id_theme=?, "
         . "id_profile=? "
         . "WHERE id_user=?;";

    $params = array(
      urlencode($user->getEmail()),
      ($user->isActived() ? "Y" : "N"),
      $user->getIdTheme(),
      $user->getIdProfile(),
      $user->getIdUser()
    );

    return $this->exec($sql, $params);
  }

  /**
   * bool resetPwd(User $user)
   *
   * Resets an user password in the users table.
   *
   * @param User $user user to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  function resetPwd($user)
  {
    if ( !$user instanceof User )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    $sql = "UPDATE " . $this->_table . " SET";
    $sql .= " pwd='" . urlencode($user->getPwd()) . "'"; // md5 from form
    $sql .= " WHERE id_user=" . $user->getIdUser() . ";";

    return $this->exec($sql);
  }

  /**
   * bool delete(int $idUser)
   *
   * Deletes an user from the users table.
   *
   * @param string $idUser key of user to delete
   * @return boolean returns false, if error occurs
   * @access public
   */
  function delete($idUser)
  {
    $sql = "DELETE FROM " . $this->_table;
    $sql .= " WHERE id_user=" . intval($idUser) . ";";

    if ( !$this->exec($sql) )
    {
      return false;
    }

    $sql = "UPDATE staff_tbl SET";
    $sql .= " id_user=NULL";
    $sql .= " WHERE id_user=" . intval($idUser) . ";";

    return $this->exec($sql);
  }
} // end class
?>
