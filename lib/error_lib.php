<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: error_lib.php,v 1.5 2004/07/07 17:24:52 jact Exp $
 */

/**
 * error_lib.php
 ********************************************************************
 * Set of show error functions
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

/**
 * Functions:
 *  void showQueryError(Query $query, bool $goOut = true)
 *  void showConnError(DbConnection $conn, bool $goOut = true)
 *  void showFetchError(Query $query, bool $goOut = true)
 *  void showErrorMsg(string $errorMsg, int $errorType = E_USER_WARNING)
 */

/**
 * void showQueryError(Query $query, bool $goOut = true)
 ********************************************************************
 * Displays the query error page
 ********************************************************************
 * @param Query $query Query object containing query error parameters.
 * @param bool $goOut if true, execute an exit()
 * @return void
 * @access public
 */
function showQueryError($query, $goOut = true)
{
  echo "\n<!-- _dbErrno = " . $query->getDbErrno() . "-->\n";
  echo "<!-- _dbError = " . $query->getDbError() . "-->\n";
  if ($query->getSQL() != "")
  {
    echo "<!-- _SQL = " . $query->getSQL() . "-->\n";
  }

  if ($query->getDbErrno() == 1049) // Unable to connect to database
  {
    echo '<p><a href="../install.html">' . "Install instructions". "</a></p>\n";
  }

  if ($goOut)
  {
    exit($query->getError());
  }
}

/**
 * void showConnError(DbConnection $conn, bool $goOut = true)
 ********************************************************************
 * Displays the connection error page
 ********************************************************************
 * @param DbConnection $conn DbConnection object containing connection error parameters.
 * @param bool $goOut if true, execute an exit()
 * @return void
 * @access public
 */
function showConnError($conn, $goOut = true)
{
  echo "\n<!-- _dbErrno = " . $conn->getDbErrno() . "-->\n";
  echo "<!-- _dbError = " . $conn->getDbError() . "-->\n";
  echo "<!-- _error = " . $conn->getError() . "-->\n";
  if ($conn->getSQL() != "")
  {
    echo "<!-- _SQL = " . $conn->getSQL() . "-->\n";
  }

  if ($conn->getDbErrno() == 1049) // Unable to connect to database
  {
    echo '<p><a href="../install.html">' . "Install instructions". "</a></p>\n";
  }

  if ($goOut)
  {
    exit($conn->getError());
  }
}

/**
 * void showFetchError(Query $query, bool $goOut = true)
 ********************************************************************
 * Displays the fetch error page
 ********************************************************************
 * @param bool $goOut if true, execute an exit()
 * @return void
 * @access public
 */
function showFetchError($query, $goOut = true)
{
  if ($query->getSQL() != "")
  {
    echo "\n<!-- _SQL = " . $query->getSQL() . "-->\n";
  }

  if ($goOut)
  {
    exit($query->getError());
  }
}

/**
 * void showErrorMsg(string $errorMsg, int $errorType = E_USER_WARNING)
 ********************************************************************
 * Displays an error message
 ********************************************************************
 * @param string $errorMsg
 * @param int $errorType (optional) E_USER_WARNING by default
 * @return void
 * @access public
 */
function showErrorMsg($errorMsg, $errorType = E_USER_WARNING)
{
  trigger_error($errorMsg, $errorType);
}
?>
