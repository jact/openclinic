<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: record_log.php,v 1.2 2004/04/18 14:02:25 jact Exp $
 */

/**
 * record_log.php
 ********************************************************************
 * Contains recordLog function
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../classes/Record_Query.php");

  /**
   * void recordLog(string $tableName, string, $operation, int $idKey1, int $idKey2 = 0)
   ********************************************************************
   * Inserts a new record in log operations table if it is possible
   ********************************************************************
   * @param string $tableName
   * @param string $operation one between INSERT, UPDATE, DELETE
   * @param int $idKey1 principal key of the record
   * @param int $idKey2 (optional) second principal key of the record
   * @return void
   * @access public
   */
  function recordLog($tableName, $operation, $idKey1, $idKey2 = 0)
  {
    if (defined("OPEN_DEMO") && OPEN_DEMO)
    {
      return; // disabled in demo version
    }

    $recQ = new Record_Query();
    $recQ->connect();
    if ($recQ->errorOccurred())
    {
      showQueryError($recQ);
    }

    if ( !$recQ->insert($_SESSION['userId'], $_SESSION['loginSession'], $tableName, $operation, $idKey1, $idKey2) )
    {
      $recQ->close();
      showQueryError($recQ);
    }
    if ($idKey2 == 0) // attention!!!
    {
      $recQ->close();
    }
    unset($recQ);
  }
?>
