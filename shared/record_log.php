<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: record_log.php,v 1.6 2004/10/17 14:57:35 jact Exp $
 */

/**
 * record_log.php
 ********************************************************************
 * Contains recordLog function
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * @since 0.3
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['PATH_TRANSLATED'])
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../classes/Record_Query.php");

  /**
   * void recordLog(string $table, string, $operation, array $key)
   ********************************************************************
   * Inserts a new record in log operations table if it is possible
   ********************************************************************
   * @param string $table
   * @param string $operation one between INSERT, UPDATE, DELETE
   * @param array $key primary key of the record
   * @return void
   * @access public
   */
  function recordLog($table, $operation, $key)
  {
    if (defined("OPEN_DEMO") && OPEN_DEMO)
    {
      return; // disabled in demo version
    }

    $queryQ = new Query();
    $queryQ->connect();
    if ($queryQ->isError())
    {
      showQueryError($queryQ);
    }

    $tableKey = $queryQ->getPrimaryKey($table);
    if ($queryQ->isError())
    {
      $queryQ->close();
      showQueryError($queryQ);
    }

    $data = $queryQ->getRowData($tableKey, $key, $table);
    if ($queryQ->isError())
    {
      $queryQ->close();
      showQueryError($queryQ);
    }
    //$queryQ->close(); // don't remove comment mark (fails in relative_new.php)
    unset($queryQ);

    $recQ = new Record_Query();
    $recQ->connect();
    if ($recQ->isError())
    {
      showQueryError($recQ);
    }

    $recQ->insert($_SESSION['userId'], $_SESSION['loginSession'], $table, $operation, $data);
    if ($recQ->isError())
    {
      $recQ->close();
      showQueryError($recQ);
    }

    if ($operation != "DELETE") // because log process is before deleting process
    //if ($idKey2 == 0) // attention!!! if (count($key) > 1)
    {
      $recQ->close();
    }
    unset($recQ);
  }
?>
