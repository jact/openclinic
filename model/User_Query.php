<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: User_Query.php,v 1.1 2004/01/29 14:30:24 jact Exp $
 */

/**
 * User_Query.php
 ********************************************************************
 * Contains the class User_Query
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 29/01/04 15:30
 */

require_once("../classes/Query.php");
require_once("../classes/User.php");

/**
 * User_Query data access component for users
 ********************************************************************
 * @author jact <jachavar@terra.es>
 * @version 0.4
 * @access public
 ********************************************************************
 * Methods:
 *  mixed select(int $idUser = 0)
 *  mixed selectLogins(void)
 *  bool existLogin(string $login, int $idUser = 0)
 *  mixed verifySignOn(string $login, string $pwd, bool $onlyCheck = false)
 *  bool deactivate(string $login)
 *  mixed fetchUser(void)
 *  bool insert(User $user)
 *  bool update(User $user)
 *  bool resetPwd(User $user)
 *  bool delete(int $idUser)
 */
class User_Query extends Query
{
  /**
   * mixed select(int $idUser = 0)
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @param int $idUser (optional) key of user to select
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function select($idUser = 0)
  {
    $sql = "SELECT login,id_member,user_tbl.*";
    $sql .= " FROM staff_tbl,user_tbl";
    $sql .= " WHERE user_tbl.id_user=staff_tbl.id_user";
    if ($idUser != "")
    {
      $sql .= " AND user_tbl.id_user=" . intval($idUser);
    }
    $sql .= " ORDER BY login;";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing user information.";
      return false;
    }

    return $this->numRows();
  }

  /**
   * mixed selectLogins(void)
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function selectLogins()
  {
    $sql = "SELECT id_member,login FROM staff_tbl";
    $sql .= " WHERE id_user IS NULL";
    $sql .= " AND login IS NOT NULL";
    $sql .= " ORDER BY login;";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing user information.";
      return false;
    }

    return $this->numRows();
  }

  /**
   * bool existLogin(string $login, int $idUser = 0)
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @param string $login login of user to know if exists
   * @param int $idUser (optional) key of user
   * @return boolean returns true if login already exists or false if error occurs
   * @access public
   */
  function existLogin($login, $idUser = 0)
  {
    $sql = "SELECT COUNT(login) FROM staff_tbl";
    $sql .= " WHERE login='" . urlencode($login) . "'";
    if ($idUser > 0)
    {
      $sql .= " AND id_user<>" . intval($idUser);
    }

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error checking for dup login.";
      return false;
    }

    $array = $this->fetchRow(MYSQL_NUM);

    return ($array[0] > 0);
  }

  /**
   * mixed verifySignOn(string $login, string $pwd, bool $onlyCheck = false)
   ********************************************************************
   * Executes a query to verify a sign on login and password
   ********************************************************************
   * @param string $login login of user to select
   * @param string $pwd password of staff member to select
   * @param bool $onlyCheck (optional)
   * @return mixed returns false, if error occurs, otherwise: if $onlyCheck is true returns a boolean, else returns an id result
   * @access public
   */
  function verifySignOn($login, $pwd, $onlyCheck = false)
  {
    $sql = "SELECT login,id_member,user_tbl.* FROM user_tbl,staff_tbl";
    $sql .= " WHERE user_tbl.id_user=staff_tbl.id_user";
    $sql .= " AND login='" . urlencode($login) . "'";
    $sql .= " AND pwd='" . urlencode($pwd) . "'"; // md5 from form

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error verifying login and password.";
    }

    return ($onlyCheck ? $this->numRows() > 0 : $result);
  }

  /**
   * bool deactivate(string $login)
   ********************************************************************
   * Updates an user and sets the actived flag to No.
   ********************************************************************
   * @param string $login login of user to deactivate
   * @return boolean returns false, if error occurs
   * @access public
   */
  function deactivate($login)
  {
    $sql = "SELECT id_user FROM staff_tbl";
    $sql .= " WHERE login='" . urlencode($login) . "';";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error deactivating user.";
      return false;
    }

    $result = $this->fetchRow();
    if ( !$result )
    {
      return false;
    }
    $idUser = $result['id_user'];

    $sql = "UPDATE user_tbl SET actived='N'";
    $sql .= " WHERE id_user=" . intval($idUser) . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error deactivating user.";
    }

    return $result;
  }

  /**
   * mixed fetchUser(void)
   ********************************************************************
   * Fetches a row from the query result and populates the User object
   ********************************************************************
   * @return User returns user or false if no more users to fetch
   * @access public
   */
  function fetchUser()
  {
    $array = $this->fetchRow();
    if ($array == false)
    {
      return false;
    }

    $user = new User();
    $user->setIdUser($array["id_user"]);
    $user->setIdMember($array["id_member"]);
    $user->setLogin(urldecode($array["login"]));
    $user->setPwd(urldecode($array["pwd"]));
    $user->setEmail(urldecode($array["email"]));
    $user->setActived($array["actived"] == "Y");
    $user->setIdTheme($array["id_theme"]);
    $user->setIdProfile($array["id_profile"]);

    return $user;
  }

  /**
   * bool insert(User $user)
   ********************************************************************
   * Inserts a new user into the users table.
   ********************************************************************
   * @param User $user user to insert
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($user)
  {
    if ($this->errorOccurred())
    {
      return false;
    }

    $sql = "INSERT INTO user_tbl ";
    $sql .= "(id_user, pwd, email, actived, id_theme, id_profile) VALUES (NULL, ";
    $sql .= "'" . urlencode($user->getPwd()) . "', "; // md5 from form
    $sql .= ($user->getEmail() == "") ? "NULL, " : "'" . urlencode($user->getEmail()) . "', ";
    $sql .= ($user->isActived()) ? "'Y', " : "'N', ";
    $sql .= $user->getIdTheme() . ", ";
    $sql .= $user->getIdProfile() . ");";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error inserting new user information.";
      return false;
    }

    $sql = "UPDATE staff_tbl SET";
    $sql .= " id_user=LAST_INSERT_ID()";
    $sql .= " WHERE id_member=" . $user->getIdMember() . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error inserting new user information.";
    }

    return $result;
  }

  /**
   * bool update(User $user)
   ********************************************************************
   * Update an user in the users table.
   ********************************************************************
   * @param User $user user to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  function update($user)
  {
    $isDupLogin = $this->existLogin($user->getLogin(), $user->getIdUser());
    if ($this->errorOccurred())
    {
      return false;
    }

    if ($isDupLogin)
    {
      $this->_error = "Login is already in use.";
      return false;
    }

    $sql = "UPDATE staff_tbl SET";
    $sql .= " login='" . urlencode($user->getLogin()) . "'";
    $sql .= " WHERE id_user=" . $user->getIdUser() . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error updating member user information.";
      return false;
    }

    $sql = "UPDATE user_tbl SET"; //" last_updated_date = curdate(),";
    $sql .= " email=" . (($user->getEmail() == "") ? "NULL," : "'" . urlencode($user->getEmail()) . "',");
    $sql .= " actived=" . ($user->isActived() ? "'Y', " : "'N', ");
    $sql .= " id_theme=" . $user->getIdTheme() . ", ";
    $sql .= " id_profile=" . $user->getIdProfile();
    $sql .= " WHERE id_user=" . $user->getIdUser() . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error updating user information.";
    }

    return $result;
  }

  /**
   * bool resetPwd(User $user)
   ********************************************************************
   * Resets an user password in the users table.
   ********************************************************************
   * @param User $user user to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  function resetPwd($user)
  {
    $sql = "UPDATE user_tbl SET";
    $sql .= " pwd='" . urlencode($user->getPwd()) . "'"; // md5 from form
    $sql .= " WHERE id_user=" . $user->getIdUser() . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error reseting password.";
    }

    return $result;
  }

  /**
   * bool delete(int $idUser)
   ********************************************************************
   * Deletes an user from the users table.
   ********************************************************************
   * @param string $idUser key of user to delete
   * @return boolean returns false, if error occurs
   * @access public
   */
  function delete($idUser)
  {
    $sql = "DELETE FROM user_tbl";
    $sql .= " WHERE id_user=" . intval($idUser) . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error deleting user information.";
      return false;
    }

    // si esto no funciona pasar además como argumento idMember
    $sql = "UPDATE staff_tbl SET";
    $sql .= " id_user=NULL";
    $sql .= " WHERE id_user=" . intval($idUser) . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error updating member user information.";
    }

    return $result;
  }
} // end class
?>
