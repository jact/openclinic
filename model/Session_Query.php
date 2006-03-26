<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Session_Query.php,v 1.8 2006/03/26 16:12:40 jact Exp $
 */

/**
 * Session_Query.php
 *
 * Contains the class Session_Query
 *
 * @author jact <jachavar@gmail.com>
 */

require_once("../classes/Query.php");

/**
 * Session_Query data access component for sign on sessions
 *
 * Methods:
 *  void Session_Query(void)
 *  bool validToken(string $login, int $token)
 *  mixed getToken(string $login)
 *  bool _updateToken(int $token)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Session_Query extends Query
{
  /**
   * void Session_Query(void)
   *
   * Constructor function
   *
   * @return void
   * @access public
   */
  function Session_Query()
  {
    $this->_table = "session_tbl";
  }

  /**
   * bool validToken(string $login, int $token)
   *
   * Executes a query to validate the token
   *
   * @param string $login login of user to validate
   * @param int $token sign on token of user to validate
   * @return boolean returns true if token is valid, false if token is not.
   * @access public
   */
  function validToken($login, $token)
  {
    $sql = "SELECT last_updated_date, token";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE login='" . urlencode($login);
    $sql .= "' AND token=" . intval($token);
    $sql .= " AND last_updated_date >= date_sub(sysdate(), interval ";
    $sql .= OPEN_SESSION_TIMEOUT . " minute)";

    if ( !$this->exec($sql) )
    {
      return false;
    }

    if ($this->numRows() > 0)
    {
      $this->_updateToken($token);
      return true;
    }
    else
    {
      return false;
    }
  }

  /**
   * mixed getToken(string $login)
   *
   * Inserts or updates the session table and returns a new valid sign on token
   *
   * @param string $login login of user to validate
   * @return int returns token or false, if error occurs
   * @access public
   */
  function getToken($login)
  {
    /**
     * Only purpose of the delete is to clean up old session rows so that
     * the session table doesn't get too full.
     */
    $sql = "DELETE FROM " . $this->_table;
    $sql .= " WHERE login='" . urlencode($login) . "'";
    $sql .= " AND last_updated_date < date_sub(sysdate(), interval ";
    $sql .= OPEN_SESSION_TIMEOUT . " minute)";

    if ( !$this->exec($sql) )
    {
      return false;
    }

    srand((double) microtime() * 1000000);
    $token = rand(-10000, 10000);

    $sql = "INSERT INTO " . $this->_table;
    $sql .= " (login, last_updated_date, token) VALUES (";
    $sql .= "'" . urlencode($login) . "', sysdate(), ";
    $sql .= $token . ")";

    return ($this->exec($sql) ? $token : false);
  }

  /**
   * bool _updateToken(int $token)
   *
   * Updates last updated date in session table so that the session will not time out.
   *
   * @param int $token token of session to update
   * @return boolean returns true if successful, false if error occurs.
   * @access private
   */
  function _updateToken($token)
  {
    $sql = "UPDATE " . $this->_table . " SET";
    $sql .= " last_updated_date=sysdate()";
    $sql .= " WHERE token=" . intval($token) . ";";

    return $this->exec($sql);
  }
} // end class
?>
