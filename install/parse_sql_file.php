<?php
/**
 * parse_sql_file.php
 *
 * Contains the function parseSQLFile() and the array with OpenClinic table names
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: parse_sql_file.php,v 1.21 2007/11/02 20:41:23 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../model/Query.php");
  require_once("../lib/Msg.php");

/**
 * Functions:
 *  bool parseSQLFile(string $file, string $table, bool $drop = true)
 */

/**
 * table array (15 elements)
 */
$tables = array(
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

/**
 * bool parseSQLFile(string $file, string $table, bool $drop = true)
 *
 * Parses a SQL file
 *
 * @param string $file name of the file to parse
 * @param string $table name of the table
 * @param bool $drop if true, execute a DROP TABLE sentence
 * @return bool false if an error occurs
 * @access public
 */
function parseSQLFile($file, $table, $drop = true)
{
  $installQ = new Query();
  $installQ->captureError(true);
  if ($installQ->isError())
  {
    Error::query($installQ, false);
    return false;
  }

  if ($drop)
  {
    $sql = "DROP TABLE IF EXISTS " . $table;
    @$result = $installQ->exec($sql);
    if ($installQ->isError())
    {
      Error::query($installQ, false);
      $installQ->clearErrors();
    }
    else
    {
      flush();
    }
  }

  /**
   * reading through sql file executing SQL only when ";" is encountered and if is out of brackets
   */
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
      if ($installQ->isError() && $installQ->getDbErrno() != 1060) // duplicate column
      {
        HTML::para(sprintf(_("Process sql [%s]"), $sqlSentence));
        $installQ->close();
        Error::query($installQ, false);
        Msg::error(sprintf(_("Error: %s"), $installQ->getDbError()));
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
