<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: parse_sql_file.php,v 1.1 2004/03/24 19:15:44 jact Exp $
 */

/**
 * parse_sql_file.php
 ********************************************************************
 * Contains the function parseSQLFile() and the array with OpenClinic table names
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 24/03/04 20:15
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

require_once("../classes/Query.php");
require_once("../lib/error_lib.php");

/**
 * Functions:
 *  bool parseSQLFile(string $file, string $table, bool $drop = true)
 */

// table array (16 elements)
$tables = array(
  "access_log_tbl",
  "connection_problem_tbl",
  "deleted_patient_tbl",
  "deleted_problem_tbl",
  "history_tbl",
  "medical_test_tbl",
  "patient_tbl",
  "problem_tbl",
  "profile_tbl",
  "relative_tbl",
  "record_log_tbl",
  "session_tbl",
  "setting_tbl",
  "staff_tbl",
  "theme_tbl",
  "user_tbl"
);

/**
 * bool parseSQLFile(string $file, string $table, bool $drop = true)
 ********************************************************************
 * Parses a SQL file
 ********************************************************************
 * @param string $file name of the file to parse
 * @param string $table name of the table
 * @param bool $drop if true, execute a DROP TABLE sentence
 * @return bool false if an error occurs
 * @access public
 */
function parseSQLFile($file, $table, $drop = true)
{
  $installQ = new Query();
  $installQ->connect();
  if ($installQ->errorOccurred())
  {
    showQueryError($installQ, false);
    return false;
  }

  if ($drop)
  {
    $sql = "DROP TABLE " . $table;
    @$result = $installQ->exec($sql);
    if ($installQ->errorOccurred())
    {
      showQueryError($installQ, false);
      $installQ->clearErrors();
    }
    else
    {
      flush();
    }
  }

  // reading through sql file executing SQL only when ";" is encountered and if is out of brackets
  $fp = fopen($file, "r");
  $sqlSentence = "";
  $outBracket = true;

  while ( !feof($fp) )
  {
    $char = fgetc($fp);

    if ($char == "(")
    {
      $outBracket = false;
    }

    if ($char == ")")
    {
      $outBracket = true;
    }

    if ($char == ";" && $outBracket)
    {
      $result = $installQ->exec($sqlSentence);
      if ($installQ->errorOccurred())
      {
        echo 'Process sql [' . $sqlSentence . ']<br />';
        $installQ->close();
        showQueryError($installQ, false);
        echo '<p class="error">Error: ' . $installQ->getDbError() . "</p>\n";
        return false;
      }
      $sqlSentence = "";
    }
    else
    {
      $sqlSentence .= $char;
    }
  }
  fclose($fp);
  $installQ->close();
  flush();

  return true;
}
?>
