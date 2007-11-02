<?php
/**
 * Session.php
 *
 * Contains the class Query_Session
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Session.php,v 1.2 2007/11/02 20:39:01 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../Query.php");

/**
 * Query_Session data access component for sign on sessions
 *
 * Methods:
 *  bool Query_Session(array $dsn = null)
 *  bool validToken(string $login, int $token)
 *  mixed getToken(string $login)
 *  bool _updateToken(int $token)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Query_Session extends Query
{
  /**
   * bool Query_Session(array $dsn = null)
   *
   * Constructor function
   *
   * @param array $dsn (optional) Data Source Name
   * @return boolean returns false, if error occurs
   * @access public
   */
  function Query_Session($dsn = null)
  {
    $this->_table = "session_tbl";

    return parent::Query($dsn);
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
    $sql .= " WHERE login='" . urlencode($login) . "'";
    $sql .= " AND token=" . intval($token);
    $sql .= " AND last_updated_date>=DATE_SUB(SYSDATE(), INTERVAL " . OPEN_SESSION_TIMEOUT . " MINUTE)";

    if ( !$this->exec($sql) )
    {
      return false;
    }

    return ($this->numRows() > 0 ? $this->_updateToken($token) : false);
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
    $sql .= " AND last_updated_date<DATE_SUB(SYSDATE(), INTERVAL " . OPEN_SESSION_TIMEOUT . " MINUTE)";

    if ( !$this->exec($sql) )
    {
      return false;
    }

    srand((double) microtime() * 1000000);
    $token = rand(-10000, 10000);

    $sql = "INSERT INTO " . $this->_table;
    $sql .= " (login, last_updated_date, token) VALUES (";
    $sql .= "'" . urlencode($login) . "', ";
    $sql .= "SYSDATE(), ";
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
    $sql .= " last_updated_date=SYSDATE()";
    $sql .= " WHERE token=" . intval($token) . ";";

    return $this->exec($sql);
  }
} // end class
?>
