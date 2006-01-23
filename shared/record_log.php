<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: record_log.php,v 1.14 2006/01/23 22:43:01 jact Exp $
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

    if ( !call_user_func_array(array($queryQ, $method), $key) )
    {
      $queryQ->close();
      return;
    }

    $data = $queryQ->fetchRow(); // obtains an array
    if ( !$data )
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

    $recQ->insert($_SESSION['userId'], $_SESSION['loginSession'], $table, $operation, $data);

    $recQ->close();
    unset($recQ);
  }
?>
