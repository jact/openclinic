<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: dump_lib.php,v 1.2 2004/04/18 14:25:40 jact Exp $
 */

/**
 * dump_lib.php
 ********************************************************************
 * Set of functions used to build dumps of tables
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
 *  string DLIB_htmlFormat(string $string = '', string $asFile = '')
 *  mixed DLIB_backquote(mixed $mixedVar, bool $doIt = true)
 *  string DLIB_sqlAddSlashes(string $string = '', bool $isLike = false)
 *  string DLIB_getTableDef(string $db, string $table, string $crlf, array &$postVars)
 *  bool DLIB_getTableContentFast(string $db, string $table, string $addQuery = '', string $crlf, array &$postVars)
 *  bool DLIB_getTableContentOld(string $db, string $table, string $addQuery = '', string $crlf, array &$postVars)
 *  void DLIB_getTableContent(string $db, string $table, int $limitFrom = 0, int $limitTo = 0, string $crlf, array &$postVars)
 *  mixed DLIB_getTableCSV(string $db, string $table, int $limitFrom = 0, int $limitTo = 0, string $sep, string $encBy, string $escBy, string $addCharacter, string $what)
 *  string DLIB_getTableXML(string $db, string $table, int $limitFrom = 0, int $limitTo = 0, string $crlf, string $startTable, string $endTable)
 *  string DLIB_whichCrlf(void)
 *  string DLIB_localisedDate(int $timestamp = -1)
 */
if ( !defined('DUMP_LIB_INCLUDED') )
{
  define('DUMP_LIB_INCLUDED', 1);

  /**
   * string DLIB_htmlFormat(string $string = '', string $asFile = '')
   ********************************************************************
   * Uses the 'htmlspecialchars()' php function on databases, tables
   * and fields name if the dump has to be displayed on screen.
   ********************************************************************
   * @param string $string the string to format
   * @param string $asFile
   * @return string the formatted string
   * @access private
   */
  function DLIB_htmlFormat($string = '', $asFile = '')
  {
    return(empty($asFile) ? htmlspecialchars(urldecode($string)) : urldecode($string));
  } // end 'DLIB_htmlFormat()' function

  /**
   * mixed DLIB_backquote(mixed $mixedVar, bool $doIt = true)
   ********************************************************************
   * Adds backquotes on both sides of a database, table or field name.
   * Since MySQL 3.23.06 this allows to use non-alphanumeric characters
   * in these names.
   ********************************************************************
   * @param   mixed    the database, table or field name to "backquote" or
   *                   array of it
   * @param   boolean  a flag to bypass this function (used by dump
   *                   functions)
   * @return  mixed    the "backquoted" database, table or field name if the
   *                   current MySQL release is >= 3.23.06, the original
   *                   one else
   * @access  public
   */
  function DLIB_backquote($mixedVar, $doIt = true)
  {
    if ($doIt && DLIB_MYSQL_INT_VERSION >= 32306 && !empty($mixedVar) && $mixedVar != '*')
    {
      if (is_array($mixedVar))
      {
        $result = array();
        reset($mixedVar);
        while (list($key, $val) = each($mixedVar))
        {
          $result[$key] = '`' . $val . '`';
        }
        return $result;
      }
      else
      {
        return '`' . $mixedVar . '`';
      }
    }
    else
    {
      return $mixedVar;
    }
  } // end of the 'DLIB_backquote()' function

  /**
   * string DLIB_sqlAddSlashes(string $string = '', bool $isLike = false)
   ********************************************************************
   * Add slashes before "'" and "\" characters so a value containing
   * them can be used in a sql comparison.
   ********************************************************************
   * @param   string  the string to slash
   * @param   boolean whether the string will be used in a 'LIKE' clause
   *                  (it then requires two more escaped sequences) or not
   * @return  string  the slashed string
   * @access  public
   */
  function DLIB_sqlAddSlashes($string = '', $isLike = false)
  {
    ($isLike)
      ? $string = str_replace('\\', '\\\\\\\\', $string)
      : $string = str_replace('\\', '\\\\', $string);

    $string = str_replace('\'', '\\\'', $string);

    return $string;
  } // end of the 'DLIB_sqlAddSlashes()' function

  /**
   * string DLIB_getTableDef(string $db, string $table, string $crlf, array &$postVars)
   ********************************************************************
   * Returns $table's CREATE definition
   ********************************************************************
   * @param   string   the database name
   * @param   string   the table name
   * @param   string   the end of line sequence
   * @param   string   the url to go back in case of error
   * @return  string   the CREATE statement on success
   * @see     DLIB_htmlFormat()
   * @access  public
   */
  function DLIB_getTableDef($db, $table, $crlf, &$postVars)
  {
    $schemaCreate = '';
    if (isset($postVars['drop']))
    {
      $schemaCreate .= 'DROP TABLE IF EXISTS ' . DLIB_backquote(DLIB_htmlFormat($table, $postVars['as_file']), $postVars['use_backquotes']) . ';' . $crlf;
    }

    $localConn = new DbConnection();
    if ( !$localConn->connect() )
    {
      return 'Unable to connect to database';
    }

    if (DLIB_MYSQL_INT_VERSION >= 32321)
    {
      $result = $localConn->exec('SHOW TABLE STATUS FROM ' . $db . ' LIKE \'' . DLIB_sqlAddslashes($table) . '\'');
      if ($result != false && $localConn->numRows() > 0)
      {
        $tmpRes = $localConn->fetchRow(MYSQL_ASSOC);

        if (isset($tmpRes['Create_time']) && !empty($tmpRes['Create_time']))
        {
          $schemaCreate .= '# ' . _("Create Time") . ': ' . DLIB_localisedDate(strtotime($tmpRes['Create_time'])) . $crlf;
        }

        if (isset($tmpRes['Update_time']) && !empty($tmpRes['Update_time']))
        {
          $schemaCreate .= '# ' . _("Update Time") . ': ' . DLIB_localisedDate(strtotime($tmpRes['Update_time'])) . $crlf;
        }

        if (isset($tmpRes['Check_time']) && !empty($tmpRes['Check_time']))
        {
          $schemaCreate .= '# ' . _("Check Time") . ': ' . DLIB_localisedDate(strtotime($tmpRes['Check_time'])) . $crlf;
        }
        $schemaCreate .= $crlf;
      }
      $localConn->freeResult();

      // Whether to quote table and fields names or not
      if (isset($postVars['use_backquotes']))
      {
        $localConn->exec('SET SQL_QUOTE_SHOW_CREATE = 1');
      }
      else
      {
        $localConn->exec('SET SQL_QUOTE_SHOW_CREATE = 0');
      }

      $localQuery = 'SHOW CREATE TABLE ' . DLIB_backquote($db) . '.' . DLIB_backquote($table);
      $result = $localConn->exec($localQuery);
      if ($result != false && $localConn->numRows() > 0)
      {
        $tmpRes    = $localConn->fetchRow(MYSQL_NUM);
        $pos       = strpos($tmpRes[1], ' (');
        $tmpRes[1] = substr($tmpRes[1], 0, 13)
                   . (($postVars['use_backquotes']) ? DLIB_backquote($tmpRes[0]) : $tmpRes[0])
                   . substr($tmpRes[1], $pos);
        $schemaCreate .= str_replace("\n", $crlf, DLIB_htmlFormat($tmpRes[1], $postVars['as_file']));
      }
      //$localConn->close(); // don't remove the comment mark
      return $schemaCreate;
    } // end if MySQL >= 3.23.21

    // For MySQL < 3.23.20
    $schemaCreate .= 'CREATE TABLE ' . DLIB_htmlFormat(DLIB_backquote($table, $postVars['use_backquotes']), $postVars['as_file']) . ' (' . $crlf;

    $localQuery = 'SHOW FIELDS FROM ' . DLIB_backquote($db) . '.' . DLIB_backquote($table);
    if ( !$localConn->exec($localQuery) )
    {
      return 'Unable to execute query';
    }

    while ($row = $localConn->fetchRow())
    {
      $schemaCreate .= '   ' . DLIB_htmlFormat(DLIB_backquote($row['Field'], $postVars['use_backquotes']), $postVars['as_file']) . ' ' . $row['Type'];
      if (isset($row['Default']) && $row['Default'] != '')
      {
        $schemaCreate .= ' DEFAULT \'' . DLIB_htmlFormat(DLIB_sqlAddSlashes($row['Default']), $postVars['as_file']) . '\'';
      }

      if ($row['Null'] != 'YES')
      {
        $schemaCreate .= ' NOT NULL';
      }

      if ($row['Extra'] != '')
      {
        $schemaCreate .= ' ' . $row['Extra'];
      }

      $schemaCreate .= ',' . $crlf;
    } // end while
    $schemaCreate = ereg_replace(',' . $crlf . '$', '', $schemaCreate);

    $localQuery = 'SHOW KEYS FROM ' . DLIB_backquote($db) . '.' . DLIB_backquote($table);
    if ( !$localConn->exec($localQuery) )
    {
      return 'Unable to execute query';
    }

    while ($row = $localConn->fetchRow())
    {
      $kname   = $row['Key_name'];
      $comment = (isset($row['Comment'])) ? $row['Comment'] : '';
      $subPart = (isset($row['Sub_part'])) ? $row['Sub_part'] : '';

      if ($kname != 'PRIMARY' && $row['Non_unique'] == 0)
      {
        $kname = "UNIQUE|$kname";
      }

      if ($comment == 'FULLTEXT')
      {
        $kname = 'FULLTEXT|$kname';
      }

      if ( !isset($index[$kname]) )
      {
        $index[$kname] = array();
      }

      if ($subPart > 1)
      {
        $index[$kname][] = DLIB_htmlFormat(DLIB_backquote($row['Column_name'], $postVars['use_backquotes']), $postVars['as_file']) . '(' . $subPart . ')';
      }
      else
      {
        $index[$kname][] = DLIB_htmlFormat(DLIB_backquote($row['Column_name'], $postVars['use_backquotes']), $postVars['as_file']);
      }
    } // end while
    //$localConn->close(); // don't remove the comment mark

    while (list($x, $columns) = @each($index))
    {
      $schemaCreate     .= ',' . $crlf;
      if ($x == 'PRIMARY')
      {
        $schemaCreate .= '   PRIMARY KEY (';
      }
      elseif (substr($x, 0, 6) == 'UNIQUE')
      {
        $schemaCreate .= '   UNIQUE ' . substr($x, 7) . ' (';
      }
      elseif (substr($x, 0, 8) == 'FULLTEXT')
      {
        $schemaCreate .= '   FULLTEXT ' . substr($x, 9) . ' (';
      }
      else
      {
        $schemaCreate .= '   KEY ' . $x . ' (';
      }

      $schemaCreate   .= implode($columns, ', ') . ')';
    } // end while

    $schemaCreate .= $crlf . ')';

    return $schemaCreate;
  } // end of the 'DLIB_getTableDef()' function

  /**
   * bool DLIB_getTableContentFast(string $db, string $table, string $addQuery = '', string $crlf, array &$postVars)
   ********************************************************************
   * php >= 4.0.5 only : get the content of $table as a series of INSERT
   * statements.
   ********************************************************************
   * @param   string   the current database name
   * @param   string   the current table name
   * @param   string   the 'limit' clause to use with the sql query
   * @param   string   the CRLF character
   * @return  boolean  false if error occurs
   * @access  private
   * @see     DLIB_getTableContent()
   */
  function DLIB_getTableContentFast($db, $table, $addQuery = '', $crlf, &$postVars)
  {
    $localConn = new DbConnection();
    if ( !$localConn->connect() )
    {
      return false;
    }

    $localQuery = 'SELECT * FROM ' . DLIB_backquote($db) . '.' . DLIB_backquote($table) . $addQuery;
    if ( !$localConn->exec($localQuery) )
    {
      return false;
    }

    $numFields = $localConn->numFields();
    $numRows   = $localConn->numRows();

    // Checks whether the field is an integer or not
    for ($j = 0; $j < $numFields; $j++)
    {
      $fieldSet[$j] = DLIB_backquote($localConn->fieldName($j), $postVars['use_backquotes']);
      $type          = $localConn->fieldType($j);

      $fieldNum[$j] = ($type == 'tinyint' || $type == 'smallint' || $type == 'mediumint' ||
                       $type == 'int' || $type == 'bigint' ||$type == 'timestamp');
    } // end for

    // Sets the scheme
    if (isset($postVars['show_columns']))
    {
      $fields       = implode(', ', $fieldSet);
      $schemaInsert = 'INSERT INTO ' . DLIB_backquote(DLIB_htmlFormat($table, $postVars['as_file']), $postVars['use_backquotes'])
                     . ' (' . DLIB_htmlFormat($fields, $postVars['as_file']) . ') VALUES (';
    }
    else
    {
      $schemaInsert = 'INSERT INTO ' . DLIB_backquote(DLIB_htmlFormat($table, $postVars['as_file']), $postVars['use_backquotes'])
                     . ' VALUES (';
    }

    $search     = array("\x00", "\x0a", "\x0d", "\x1a"); //\x08\\x09, not required
    $replace    = array('\0', '\n', '\r', '\Z');
    $currentRow = 0;

    @set_time_limit(EXEC_TIME_LIMIT);

    while ($row = $localConn->fetchRow(MYSQL_NUM))
    {
      $currentRow++;
      for ($j = 0; $j < $numFields; $j++)
      {
        if ( !isset($row[$j]) )
        {
          $values[] = 'NULL';
        }
        elseif ($row[$j] == '0' || $row[$j] != '')
        {
          // a number
          if ($fieldNum[$j])
          {
            $values[] = $row[$j];
          }
          // a string
          else
          {
            $values[] = "'" . str_replace($search, $replace, DLIB_sqlAddSlashes($row[$j])) . "'";
          }
        }
        else
        {
          $values[] = "''";
        } // end if
      } // end for

      // Extended inserts case
      if (isset($postVars['extended_inserts']))
      {
        if ($currentRow == 1)
        {
          $insertLine = $schemaInsert . implode(', ', $values) . ')';
        }
        else
        {
          $insertLine = ',(' . implode(', ', $values) . ')';
        }
      }
      // Other inserts case
      else
      {
        $insertLine = $schemaInsert . implode(', ', $values) . ');';
      }
      unset($values);

      // Show sentence
      echo DLIB_htmlFormat($insertLine . $crlf, $postVars['as_file']);
    } // end while

    if (isset($postVars['extended_inserts']))
    {
      echo DLIB_htmlFormat(';' . $crlf, $postVars['as_file']);
    }
    //$localConn->close(); // don't remove the comment mark

    return true;
  } // end of the 'DLIB_getTableContentFast()' function

  /**
   * bool DLIB_getTableContentOld(string $db, string $table, string $addQuery = '', string $crlf, array &$postVars)
   ********************************************************************
   * php < 4.0.5 only: get the content of $table as a series of INSERT
   * statements.
   ********************************************************************
   * @param   string   the current database name
   * @param   string   the current table name
   * @param   string   the 'limit' clause to use with the sql query
   * @param   string   the CRLF character
   * @return  boolean  false if error occurs
   * @access  private
   * @see     DLIB_getTableContent()
   */
  function DLIB_getTableContentOld($db, $table, $addQuery = '', $crlf, &$postVars)
  {
    $localConn = new DbConnection();
    if ( !$localConn->connect() )
    {
      return false;
    }

    $localQuery = 'SELECT * FROM ' . DLIB_backquote($db) . '.' . DLIB_backquote($table) . $addQuery;
    if ( !$localConn->exec($localQuery) )
    {
      return false;
    }

    $currentRow = 0;
    $numFields = $localConn->numFields();
    $numRows   = $localConn->numRows();

    @set_time_limit(EXEC_TIME_LIMIT); // HaRa

    while ($row = $localConn->fetchRow(MYSQL_NUM))
    {
      $currentRow++;
      $tableList = '(';
      for ($j = 0; $j < $numFields; $j++)
      {
        $tableList .= DLIB_backquote($localConn->fieldName($j), $postVars['use_backquotes']) . ', ';
      }
      $tableList     = substr($tableList, 0, -2);
      $tableList     .= ')';

      if (isset($postVars['extended_inserts']) && $currentRow > 1)
      {
        $schemaInsert = '(';
      }
      else
      {
        if (isset($postVars['show_columns']))
        {
          $schemaInsert = 'INSERT INTO ' . DLIB_backquote(DLIB_htmlFormat($table, $postVars['as_file']), $postVars['use_backquotes'])
                         . ' ' . DLIB_htmlFormat($tableList) . ' VALUES (';
        }
        else
        {
          $schemaInsert = 'INSERT INTO ' . DLIB_backquote(DLIB_htmlFormat($table, $postVars['as_file']), $postVars['use_backquotes'])
                         . ' VALUES (';
        }
      }

      for ($j = 0; $j < $numFields; $j++)
      {
        if ( !isset($row[$j]) )
        {
          $schemaInsert .= ' NULL, ';
        }
        elseif ($row[$j] == '0' || $row[$j] != '')
        {
          $type = $localConn->fieldType($j);

          // a number
          if ($type == 'tinyint' || $type == 'smallint' || $type == 'mediumint'
             || $type == 'int' || $type == 'bigint'  ||$type == 'timestamp')
          {
            $schemaInsert .= $row[$j] . ', ';
          }
          // a string
          else
          {
            $dummy  = '';
            $srcstr = $row[$j];
            for ($xx = 0; $xx < strlen($srcstr); $xx++)
            {
              $yy = strlen($dummy);
              if ($srcstr[$xx] == '\\')   $dummy .= '\\\\';
              if ($srcstr[$xx] == '\'')   $dummy .= '\\\'';
              //if ($srcstr[$xx] == '"')    $dummy .= '\\"';
              if ($srcstr[$xx] == "\x00") $dummy .= '\0';
              if ($srcstr[$xx] == "\x0a") $dummy .= '\n';
              if ($srcstr[$xx] == "\x0d") $dummy .= '\r';
              //if ($srcstr[$xx] == "\x08") $dummy .= '\b';
              //if ($srcstr[$xx] == "\t")   $dummy .= '\t';
              if ($srcstr[$xx] == "\x1a") $dummy .= '\Z';
              if (strlen($dummy) == $yy)  $dummy .= $srcstr[$xx];
            }
            $schemaInsert .= "'" . $dummy . "', ";
          }
        }
        else
        {
          $schemaInsert .= "'', ";
        } // end if
      } // end for
      $schemaInsert = ereg_replace(', $', '', $schemaInsert);
      $schemaInsert .= ')';

      // Show sentence
      echo DLIB_htmlFormat(trim($schemaInsert) . ";" . $crlf, $postVars['as_file']);
    } // end while
    //$localConn->close(); // don't remove the comment mark

    return true;
  } // end of the 'DLIB_getTableContentOld()' function

  /**
   * void DLIB_getTableContent(string $db, string $table, int $limitFrom = 0, int $limitTo = 0, string $crlf, array &$postVars)
   ********************************************************************
   * Dispatches between the versions of 'getTableContent' to use
   * depending on the php version
   ********************************************************************
   * @param   string   the current database name
   * @param   string   the current table name
   * @param   integer  the offset on this table
   * @param   integer  the last row to get
   * @param   string   the CRLF character
   * @param   array
   * @access  public
   * @see     DLIB_getTableContentFast(), DLIB_getTableContentOld()
   * @author  staybyte
   */
  function DLIB_getTableContent($db, $table, $limitFrom = 0, $limitTo = 0, $crlf, &$postVars)
  {
    // Defines the offsets to use
    ($limitTo > 0 && $limitFrom >= 0)
      ? $addQuery = ' LIMIT ' . (($limitFrom > 0) ? $limitFrom . ', ' : '') . $limitTo
      : $addQuery = '';

    // Call the working function depending on the php version
    (DLIB_PHP_INT_VERSION >= 40005)
      ? DLIB_getTableContentFast($db, $table, $addQuery, $crlf, $postVars)
      : DLIB_getTableContentOld($db, $table, $addQuery, $crlf, $postVars);
  } // end of the 'DLIB_getTableContent()' function

  /**
   * mixed DLIB_getTableCSV(string $db, string $table, int $limitFrom = 0, int $limitTo = 0, string $sep, string $encBy, string $escBy, string $addCharacter, string $what)
   ********************************************************************
   * Outputs the content of a table in CSV format
   ********************************************************************
   * @param   string   the database name
   * @param   string   the table name
   * @param   integer  the offset on this table
   * @param   integer  the last row to get
   * @param   string   the field separator character
   * @param   string   the optional "enclosed by" character
   * @param   string   the optional "escaped by" character
   * @param   string   whether to obtain an excel compatible csv format or a
   *                   simple csv one
   * @return  mixed false if error occurs, string if ok
   * @access  public
   */
  function DLIB_getTableCSV($db, $table, $limitFrom = 0, $limitTo = 0, $sep, $encBy, $escBy, $addCharacter, $what)
  {
    // Handles the "separator" and the optional "enclosed by" characters
    if ($what == 'excel')
    {
      $sep = ',';
    }
    elseif ( !isset($sep) )
    {
      $sep = '';
    }
    else
    {
      if (get_magic_quotes_gpc())
      {
        $sep = stripslashes($sep);
      }
      $sep = str_replace('\\t', "\011", $sep);
    }

    if ($what == 'excel')
    {
      $encBy = '"';
    }
    elseif ( !isset($encBy) )
    {
      $encBy = '';
    }
    elseif (get_magic_quotes_gpc())
    {
      $encBy = stripslashes($encBy);
    }

    if ($what == 'excel' || (empty($escBy) && $encBy != ''))
    {
      // double the "enclosed by" character
      $escBy = $encBy;
    }
    elseif ( !isset($escBy) )
    {
      $escBy = '';
    }
    elseif (get_magic_quotes_gpc())
    {
      $escBy = stripslashes($escBy);
    }

    // Defines the offsets to use
    ($limitTo > 0 && $limitFrom >= 0)
      ? $addQuery = ' LIMIT ' . (($limitFrom > 0) ? $limitFrom . ', ' : '') . $limitTo
      : $addQuery = '';

    $localConn = new DbConnection();
    if ( !$localConn->connect() )
    {
      return false;
    }

    // Gets the data from the database
    $localQuery = 'SELECT * FROM ' . DLIB_backquote($db) . '.' . DLIB_backquote($table) . $addQuery;
    if ( !$localConn->exec($localQuery) )
    {
      return false;
    }

    if ($localConn->numRows() == 0)
    {
      return false;
    }

    $numFields = $localConn->numFields();

    $fnames = '';
    for ($i=0; $i < $numFields - 1; $i++)
    {
      ($encBy == '')
        ? $fnames .= $localConn->fieldName($i) . $sep
        : $fnames .= $encBy . str_replace($encBy, $escBy . $encBy, $localConn->fieldName($i)) . $encBy . $sep;
    }
    ($encBy == '')
      ? $fnames .= $localConn->fieldName($i) . $addCharacter
      : $fnames .= $encBy . str_replace($encBy, $escBy . $encBy, $localConn->fieldName($i)) . $encBy . $addCharacter;

    $buffer = '';
    $buffer = trim($fnames) . $addCharacter;

    @set_time_limit(EXEC_TIME_LIMIT);

    // Format the data
    $i = 0;
    while ($row = $localConn->fetchRow(MYSQL_NUM))
    {
      $dataTable = '';
      for ($j = 0; $j < $numFields; $j++)
      {
        if ( !isset($row[$j]) )
        {
          $dataTable .= 'NULL';
        }
        elseif ($row[$j] == '0' || $row[$j] != '')
        {
          // always enclose fields
          if ($what == 'excel')
          {
            $row[$j] = ereg_replace("\015(\012)?", "\012", $row[$j]);
          }

          ($encBy == '')
            ? $dataTable .= $row[$j]
            : $dataTable .= $encBy . str_replace($encBy, $escBy . $encBy, $row[$j]) . $encBy;
        }
        else
        {
          $dataTable .= '';
        }

        if ($j < $numFields - 1)
        {
          $dataTable .= $sep;
        }
      } // end for
      $buffer .= trim($dataTable) . $addCharacter;
      ++$i;
    } // end while
    $buffer .= $addCharacter;
    //$localConn->close(); // don't remove the comment mark

    return $buffer;
  } // end of the 'DLIB_getTableCSV()' function

  /**
   * string DLIB_getTableXML(string $db, string $table, int $limitFrom = 0, int $limitTo = 0, string $crlf, string $startTable, string $endTable)
   ********************************************************************
   * Outputs the content of a table in XML format
   ********************************************************************
   * @param   string   the database name
   * @param   string   the table name
   * @param   integer  the offset on this table
   * @param   integer  the last row to get
   * @param   string   the end of line sequence
   * @param   string   the start string of the table
   * @param   string   the end string of the table
   * @return  string   the XML data structure on success
   * @access  public
   */
  function DLIB_getTableXML($db, $table, $limitFrom = 0, $limitTo = 0, $crlf, $startTable, $endTable)
  {
    $localConn = new DbConnection();
    if ( !$localConn->connect() )
    {
      return 'Unable to connect to database';
    }

    $localQuery = 'SHOW COLUMNS FROM ' . DLIB_backquote($table);
    $localQuery .= ' FROM ' . DLIB_backquote($db);
    if ( !$localConn->exec($localQuery) )
    {
      return 'Unable to execute query';
    }

    for ($i = 0; $row = $localConn->fetchRow(MYSQL_ASSOC); $i++)
    {
      $fields[$i] = $row['Field'];
      $types[$i] = $row['Type'];
      $nulls[$i] = $row['Null'];
      $keys[$i] = $row['Key'];
      $defaults[$i] = $row['Default'];
      $extras[$i] = $row['Extra'];
    }
    $numFields = sizeof($fields);

    // Defines the offsets to use
    if ($limitTo > 0 && $limitFrom >= 0)
    {
      $addQuery = ' LIMIT ' . (($limitFrom > 0) ? $limitFrom . ', ' : '') . $limitTo;
    }
    else
    {
      $addQuery = '';
    }

    $localQuery = 'SELECT * FROM ' . DLIB_backquote($db) . '.' . DLIB_backquote($table) . $addQuery;
    if ( !$localConn->exec($localQuery) )
    {
      return 'Unable to execute query';
    }
    if ($localConn->numRows() == 0)
    {
      return '';
    }

    $buffer = '  <!-- ' . $startTable . $table . ' -->' . $crlf;
    while ($record = $localConn->fetchRow(MYSQL_ASSOC))
    {
      $buffer .= '    <' . $table . '>' . $crlf;
      for ($i = 0; $i < $numFields; $i++)
      {
        if ( !is_null($record[$fields[$i]]) )
        {
          $buffer .= '      <' . $fields[$i]
                  . ' type="' . $types[$i] . '"'
                  . ($nulls[$i] ? ' null="' . $nulls[$i] . '"' : '')
                  . ($keys[$i] ? ' key="' . $keys[$i] . '"' : '')
                  . ($defaults[$i] ? ' default="' . $defaults[$i] . '"' : '')
                  . ($extras[$i] ? ' extra="' . $extras[$i] . '"' : '')
                  . '>' . htmlspecialchars($record[$fields[$i]])
                  . '</' . $fields[$i] . '>' . $crlf;
        }
        else
        {
          $buffer .= '      <' . $fields[$i]
                  . ' type="' . $types[$i] . '"'
                  . ($nulls[$i] ? ' null="' . $nulls[$i] . '"' : '')
                  . ($keys[$i] ? ' key="' . $keys[$i] . '"' : '')
                  . ($defaults[$i] ? ' default="' . $defaults[$i] . '"' : '')
                  . ($extras[$i] ? ' extra="' . $extras[$i] . '"' : '')
                  . ' />' . $crlf;
        }
      }
      $buffer .= '    </' . $table . '>' . $crlf;
    }
    //$localConn->close(); // don't remove the comment mark
    $buffer .= '  <!-- ' . $endTable . $table . ' -->' . $crlf;

    return $buffer;
  } // end of the 'DLIB_getTableXML()' function

  /**
   * string DLIB_whichCrlf(void)
   ********************************************************************
   * Defines the <CR><LF> value depending on the user OS.
   ********************************************************************
   * @return string the <CR><LF> value to use
   * @access public
   */
  function DLIB_whichCrlf()
  {
    // The 'DLIB_USR_OS' constant is defined in "dump_defines.php"
    // Win case
    if (DLIB_USR_OS == 'Win')
    {
      $theCrlf = "\r\n";
    }
    // Mac case
    elseif (DLIB_USR_OS == 'Mac')
    {
      $theCrlf = "\r";
    }
    // Others
    else
    {
      $theCrlf = "\n";
    }

    return $theCrlf;
  } // end of the 'DLIB_whichCrlf()' function

  /**
   * string DLIB_localisedDate(int $timestamp = -1)
   ********************************************************************
   * Writes localised date
   ********************************************************************
   * @param   int      the current timestamp
   * @return  string   the formatted date
   * @access  public
   */
  function DLIB_localisedDate($timestamp = -1)
  {
    //$dayOfWeek = array('Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab');
    //$month = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
    $dateFmt = '%d-%m-%Y %H:%M:%S';

    if ($timestamp == -1)
    {
      $timestamp = time();
    }

    //$date = strftime($dateFmt, $timestamp);
    //$date = ereg_replace('%[aA]', $dayOfWeek[(int)strftime('%w', $timestamp)], $dateFmt);
    //$date = ereg_replace('%[bB]', $month[(int)strftime('%m', $timestamp) - 1], $date);

    return strftime($dateFmt, $timestamp);
  } // end of the 'DLIB_localisedDate()' function
} // $__DUMP_LIB__
?>
