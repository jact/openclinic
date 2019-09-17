<?php
/**
 * parse_sql_file.php
 *
 * Contains the functions to parse SQL sentences and the array with OpenClinic table names
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2019 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../model/Query.php");
  require_once("../lib/Msg.php");

/**
 * Functions:
 *  array getTables(void)
 *  bool parseSqlFile(string $file, string $table = '', bool $drop = true)
 *  bool parseSql(string $text)
 */

/**
 * array getTables(void)
 */
function getTables()
{
  /**
   * table array (15 elements)
   */
  $array = array(
    "access_log_tbl",
    "connection_problem_tbl",
    "deleted_patient_tbl",
    "deleted_problem_tbl",
    "history_tbl",
    "medical_test_tbl",
    "patient_tbl",
    "problem_tbl",
    "relative_tbl",
    "record_log_tbl",
    "session_tbl",
    "setting_tbl",
    "staff_tbl",
    "theme_tbl",
    "user_tbl"
  );

  return $array;
}

/**
 * bool parseSqlFile(string $file, string $table = '', bool $drop = true)
 *
 * Parses a SQL file
 *
 * @param string $file name of the file to parse
 * @param string $table (optional) name of the table
 * @param bool $drop (optional) if true, execute a DROP TABLE sentence
 * @return bool false if an error occurs
 * @access public
 */
function parseSqlFile($file, $table = '', $drop = true)
{
  if ($drop && !empty($table))
  {
    $installQ = new Query();
    $installQ->captureError(true);

    $sql = "DROP TABLE IF EXISTS " . $table;
    $installQ->exec($sql);
    if ($installQ->isError())
    {
      AppError::query($installQ, false);
      $installQ->clearErrors();
    }
    $installQ->close();
  }

  $text = file_get_contents($file);

  return ($text === false ? false : parseSql($text));
}

/**
 * bool parseSql(string $text)
 *
 * Parses a SQL text
 *
 * @param string $text sentences to parse
 * @return bool false if an error occurs
 * @access public
 * @since 0.8
 */
function parseSql($text)
{
  $controlledErrors = array(
    1060, // duplicate column
    1091 // Check that column/key exists
  );

  $installQ = new Query();
  $installQ->captureError(true);

  /**
   * reading through SQL text executing SQL only when ";" is encountered and if is out of brackets
   */
  $count = strlen($text);
  $sqlSentence = "";
  $outBracket = true;
  for ($i = 0; $i < $count; $i++)
  {
    $char = $text[$i];

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
      $installQ->exec($sqlSentence);
      if ($installQ->isError() && !in_array($installQ->getDbErrno(), $controlledErrors))
      {
        echo HTML::para(sprintf(_("Process sql [%s]"), $sqlSentence));
        $installQ->close();
        AppError::query($installQ, false);
        echo Msg::error(sprintf(_("Error: %s"), $installQ->getDbError()));

        return false;
      }
      $sqlSentence = "";
    }
    else
    {
      $sqlSentence .= $char;
    }
  }
  $installQ->close();

  return true;
}
