<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: record_log.php,v 1.13 2005/08/03 17:40:50 jact Exp $
 */

/**
 * record_log.php
 *
 * Contains recordLog function
 *
 * Author: jact <jachavar@gmail.com>
 * @since 0.3
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../classes/Record_Page_Query.php");

  /**
   * void recordLog(string $class, string, $operation, array $key, string $method = "select")
   *
   * Inserts a new record in log operations table if it is possible
   *
   * @param string $class
   * @param string $operation one between INSERT, UPDATE, DELETE
   * @param array $key primary key of the record
   * @param string $method (optional)
   * @return void
   * @access public
   * @see OPEN_DEMO
   */
  function recordLog($class, $operation, $key, $method = "select")
  {
    if (defined("OPEN_DEMO") && OPEN_DEMO)
    {
      return; // disabled in demo version
    }

    $queryQ = new $class;
    $queryQ->connect();
    if ($queryQ->isError())
    {
      Error::query($queryQ);
    }

    $numRows = call_user_func_array(array($queryQ, $method), $key);
    if ($queryQ->isError())
    {
      $queryQ->close();
      Error::query($queryQ);
    }

    if ( !$numRows )
    {
      $queryQ->close();
      return;
    }

    $data = $queryQ->fetchRow(); // obtains an array
    if ($queryQ->isError())
    {
      $queryQ->close();
      Error::fetch($queryQ);
      return;
    }

    $data = serialize($data);

    $table = $queryQ->getTableName();
    $queryQ->close();
    unset($queryQ);

    $recQ = new Record_Page_Query();
    $recQ->connect();
    if ($recQ->isError())
    {
      Error::query($recQ);
    }

    $recQ->insert($_SESSION['userId'], $_SESSION['loginSession'], $table, $operation, $data);
    if ($recQ->isError())
    {
      $recQ->close();
      Error::query($recQ);
    }

    $recQ->close();
    unset($recQ);
  }
?>
