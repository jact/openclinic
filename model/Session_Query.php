<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Session_Query.php,v 1.2 2004/04/18 14:40:46 jact Exp $
 */

/**
 * Session_Query.php
 ********************************************************************
 * Contains the class Session_Query
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

require_once("../classes/Query.php");

/**
 * Session_Query data access component for sign on sessions
 ********************************************************************
 * @author jact <jachavar@terra.es>
 * @access public
 ********************************************************************
 * Methods:
 *  bool validToken(string $login, int $token)
 *  mixed getToken(string $login)
 *  bool _updateToken(int $token)
 */
class Session_Query extends Query
{
  /**
   * bool validToken(string $login, int $token)
   ********************************************************************
   * Executes a query to validate the token
   ********************************************************************
   * @param string $login login of user to validate
   * @param int $token sign on token of user to validate
   * @return boolean returns true if token is valid, false if token is not.
   * @access public
   */
  function validToken($login, $token)
  {
    $sql = "SELECT last_updated_date, token FROM session_tbl";
    $sql .= " WHERE login='" . urlencode($login);
    $sql .= "' AND token=" . intval($token);
    $sql .= " AND last_updated_date >= date_sub(sysdate(), interval ";
    $sql .= OPEN_SESSION_TIMEOUT . " minute)";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing session information.";
      return false;
    }

    $rowCount = $this->numRows();
    if ($rowCount > 0)
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
   ********************************************************************
   * Inserts or updates the session table and returns a new valid sign on token
   ********************************************************************
   * @param string $login login of user to validate
   * @return int returns token or false, if error occurs
   * @access public
   */
  function getToken($login)
  {
    ////////////////////////////////////////////////////////////////////
    // Only purpose of the delete is to clean up old session rows so that
    // the session table doesn't get too full.
    ////////////////////////////////////////////////////////////////////
    $sql = "DELETE FROM session_tbl WHERE login='" . urlencode($login) . "'";
    $sql .= " AND last_updated_date < date_sub(sysdate(), interval ";
    $sql .= OPEN_SESSION_TIMEOUT . " minute)";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error deleting session information.";
      return false;
    }

    srand((double) microtime() * 1000000);
    $token = rand(-10000, 10000);

    $sql = "INSERT INTO session_tbl ";
    $sql .= "(login, last_updated_date, token) VALUES (";
    $sql .= "'" . urlencode($login) . "', sysdate(), ";
    $sql .= $token . ")";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error creating a new session.";
    }

    return $token;
  }

  /**
   * bool _updateToken(int $token)
   ********************************************************************
   * Updates last updated date in session table so that the session will
   * not time out.
   ********************************************************************
   * @param int $token token of session to update
   * @return boolean returns true if successful, false if error occurs.
   * @access private
   */
  function _updateToken($token)
  {
    $sql = "UPDATE session_tbl SET";
    $sql .= " last_updated_date=sysdate()";
    $sql .= " WHERE token=" . intval($token) . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error updating session timeout.";
    }

    return $result;
  }
} // end class
?>
