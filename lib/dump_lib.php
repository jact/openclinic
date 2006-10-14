<?php
/**
 * dump_lib.php
 *
 * Set of functions used to build dumps of tables
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: dump_lib.php,v 1.15 2006/10/14 15:23:37 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

/**
 * Functions:
 *  mixed DLIB_backquote(mixed $mixedVar, bool $doIt = true)
 *  string DLIB_sqlAddSlashes(string $text, bool $isLike = false)
 *  string DLIB_getTableDef(string $db, string $table, array &$formVar)
 *  void DLIB_getTableContent(string $db, string $table, int $limitFrom = 0, int $limitTo = 0, array &$formVar)
 *  mixed DLIB_getTableCSV(string $db, string $table, int $limitFrom = 0, int $limitTo = 0, string $sep, string $encBy, string $escBy, string $addCharacter, string $what)
 *  string DLIB_getTableXML(string $db, string $table, int $limitFrom = 0, int $limitTo = 0, string $startTable, string $endTable)
 */
if ( !defined('DLIB_INCLUDED') )
{
  define('DLIB_INCLUDED', 1);

  /**
   * mixed DLIB_backquote(mixed $mixedVar, bool $doIt = true)
   *
   * Adds backquotes on both sides of a database, table or field name.
   * Since MySQL 3.23.06 this allows to use non-alphanumeric characters in these names.
   *
   * @param mixed $mixedVar the database, table or field name to "backquote" or array of it
   * @param bool $doIt (optional) a flag to bypass this function (used by dump functions)
   * @return mixed the "backquoted" database, table or field name if the
   *               current MySQL release is >= 3.23.06, the original one else
   * @access public
   */
  function DLIB_backquote($mixedVar, $doIt = true)
  {
    if ($doIt == false)
    {
      return $mixedVar;
    }

    if (DLIB_MYSQL_INT_VERSION >= 32306 && !empty($mixedVar) && $mixedVar != '*')
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
  } // end of the 'DLIB_backquote()' function

  /**
   * string DLIB_sqlAddSlashes(string $text, bool $isLike = false)
   *
   * Add slashes before "'" and "\" characters so a value containing
   * them can be used in a sql comparison.
   *
   * @param string $text the string to slash
   * @param bool $isLike (optional) whether the string will be used in a 'LIKE' clause
   *             (it then requires two more escaped sequences) or not
   * @return string the slashed string
   * @access public
   */
  function DLIB_sqlAddSlashes($text, $isLike = false)
  {
    $text = ($isLike)
      ? str_replace('\\', '\\\\\\\\', $text)
      : str_replace('\\', '\\\\', $text);

    $text = str_replace('\'', '\\\'', $text);

    return $text;
  } // end of the 'DLIB_sqlAddSlashes()' function

  /**
   * string DLIB_getTableDef(string $db, string $table, array &$formVar)
   *
   * Returns $table's CREATE definition
   *
   * @param string $db database name
   * @param string $table table name
   * @param array $formVar
   * @return string the CREATE statement on success
   * @access public
   * @see DLIB_CRLF
   */
  function DLIB_getTableDef($db, $table, &$formVar)
  {
    $schemaCreate = '';
    if (isset($formVar['drop']))
    {
      $schemaCreate .= 'DROP TABLE IF EXISTS '
        . DLIB_backquote($table, isset($formVar['use_backquotes']) ? $formVar['use_backquotes'] : true)
        . ';' . DLIB_CRLF;
    }

    $localConn = new DbConnection();
    if ( !$localConn->connect() )
    {
      return 'Unable to connect to database'; // @todo i18n
    }

    if (DLIB_MYSQL_INT_VERSION >= 32321)
    {
      $result = $localConn->exec('SHOW TABLE STATUS FROM ' . $db . ' LIKE \'' . DLIB_sqlAddslashes($table) . '\'');
      if ($result != false && $localConn->numRows() > 0)
      {
        $tmpRes = $localConn->fetchRow(MYSQL_ASSOC);

        if (isset($tmpRes['Create_time']) && !empty($tmpRes['Create_time']))
        {
          $schemaCreate .= '# ' . _("Create Time") . ': ' . I18n::localDate($tmpRes['Create_time']) . DLIB_CRLF;
        }

        if (isset($tmpRes['Update_time']) && !empty($tmpRes['Update_time']))
        {
          $schemaCreate .= '# ' . _("Update Time") . ': ' . I18n::localDate($tmpRes['Update_time']) . DLIB_CRLF;
        }

        if (isset($tmpRes['Check_time']) && !empty($tmpRes['Check_time']))
        {
          $schemaCreate .= '# ' . _("Check Time") . ': ' . I18n::localDate($tmpRes['Check_time']) . DLIB_CRLF;
        }
        $schemaCreate .= DLIB_CRLF;
      }
      $localConn->freeResult();

      // Whether to quote table and fields names or not
      if (isset($formVar['use_backquotes']))
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
                   . ((isset($formVar['use_backquotes']) && $formVar['use_backquotes']) ? DLIB_backquote($tmpRes[0]) : $tmpRes[0])
                   . substr($tmpRes[1], $pos);
        $schemaCreate .= str_replace("\n", DLIB_CRLF, $tmpRes[1]);
      }
      //$localConn->close(); // don't remove the comment mark
      return $schemaCreate;
    } // end if MySQL >= 3.23.21

    // For MySQL < 3.23.20
    $schemaCreate .= 'CREATE TABLE ' . DLIB_backquote($table, $formVar['use_backquotes']) . ' (' . DLIB_CRLF;

    $localQuery = 'SHOW FIELDS FROM ' . DLIB_backquote($db) . '.' . DLIB_backquote($table);
    if ( !$localConn->exec($localQuery) )
    {
      return 'Unable to execute query';
    }

    while ($row = $localConn->fetchRow())
    {
      $schemaCreate .= '   ' . DLIB_backquote($row['Field'], $formVar['use_backquotes'])
                     . ' ' . strtoupper($row['Type']);
      if (isset($row['Default']) && $row['Default'] != '')
      {
        $schemaCreate .= ' DEFAULT \'' . DLIB_sqlAddSlashes($row['Default']) . '\'';
      }

      if ($row['Null'] != 'YES')
      {
        $schemaCreate .= ' NOT NULL';
      }

      if ($row['Extra'] != '')
      {
        $schemaCreate .= ' ' . $row['Extra'];
      }

      $schemaCreate .= ',' . DLIB_CRLF;
    } // end while
    $schemaCreate = ereg_replace(',' . DLIB_CRLF . '$', '', $schemaCreate);

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
        $index[$kname][] = DLIB_backquote($row['Column_name'], $formVar['use_backquotes']) . '(' . $subPart . ')';
      }
      else
      {
        $index[$kname][] = DLIB_backquote($row['Column_name'], $formVar['use_backquotes']);
      }
    } // end while
    //$localConn->close(); // don't remove the comment mark

    while (list($x, $columns) = @each($index))
    {
      $schemaCreate     .= ',' . DLIB_CRLF;
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

    $schemaCreate .= DLIB_CRLF . ')';

    return $schemaCreate;
  } // end of the 'DLIB_getTableDef()' function

  /**
   * bool DLIB_getTableContent(string $db, string $table, int $limitFrom = 0, int $limitTo = 0, array &$formVar)
   *
   * php >= 4.0.5 only : get the content of $table as a series of INSERT
   * statements.
   *
   * @param string $db the current database name
   * @param string $table the current table name
   * @param int $limitFrom (optional) the offset on this table
   * @param int $limitTo (optional) the last row to get
   * @return boolean false if error occurs
   * @access private
   * @see DLIB_getTableContent()
   * @see DLIB_CRLF
   */
  function DLIB_getTableContent($db, $table, $limitFrom = 0, $limitTo = 0, &$formVar)
  {
    // Defines the offsets to use
    ($limitTo > 0 && $limitFrom >= 0)
      ? $addQuery = ' LIMIT ' . (($limitFrom > 0) ? $limitFrom . ', ' : '') . $limitTo
      : $addQuery = '';

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
      $fieldSet[$j] = DLIB_backquote($localConn->fieldName($j), (isset($formVar['use_backquotes']) ? $formVar['use_backquotes'] : false));
      $type         = $localConn->fieldType($j);

      $fieldNum[$j] = ($type == 'tinyint' || $type == 'smallint' || $type == 'mediumint' ||
                       $type == 'int' || $type == 'bigint' ||$type == 'timestamp');
    } // end for

    // Sets the scheme
    if (isset($formVar['show_columns']))
    {
      $fields       = implode(', ', $fieldSet);
      $schemaInsert = 'INSERT INTO ' . DLIB_backquote($table, $formVar['use_backquotes'])
                     . ' (' . $fields . ') VALUES (';
    }
    else
    {
      $schemaInsert = 'INSERT INTO ' . DLIB_backquote($table, isset($formVar['use_backquotes']) ? $formVar['use_backquotes'] : false)
                     . ' VALUES (';
    }

    $search     = array("\x00", "\x0a", "\x0d", "\x1a"); //\x08\\x09, not required
    $replace    = array('\0', '\n', '\r', '\Z');
    $currentRow = 0;

    @set_time_limit(OPEN_EXEC_TIME_LIMIT);

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
      if (isset($formVar['extended_inserts']))
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
      echo $insertLine . DLIB_CRLF;
    } // end while

    if (isset($formVar['extended_inserts']))
    {
      echo ';' . DLIB_CRLF;
    }
    //$localConn->close(); // don't remove the comment mark

    return true;
  } // end of the 'DLIB_getTableContent()' function

  /**
   * mixed DLIB_getTableCSV(string $db, string $table, int $limitFrom = 0, int $limitTo = 0, string $sep, string $encBy, string $escBy, string $addCharacter, string $what)
   *
   * Outputs the content of a table in CSV format
   *
   * @param string $db the database name
   * @param string $table the table name
   * @param int $limitFrom (optional) the offset on this table
   * @param int $limitTo (optional) the last row to get
   * @param string $sep the field separator character
   * @param string $encBy the optional "enclosed by" character
   * @param string $escBy the optional "escaped by" character
   * @param string $addCharacter
   * @param string $what whether to obtain an excel compatible csv format or a simple csv one
   * @return mixed false if error occurs, string if ok
   * @access public
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

    @set_time_limit(OPEN_EXEC_TIME_LIMIT);

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
   * string DLIB_getTableXML(string $db, string $table, int $limitFrom = 0, int $limitTo = 0, string $startTable, string $endTable)
   *
   * Outputs the content of a table in XML format
   *
   * @param string $db the database name
   * @param string $table the table name
   * @param int $limitFrom (optional) the offset on this table
   * @param int $limitTo (optional) the last row to get
   * @param string $startTable the start string of the table
   * @param string $endTable the end string of the table
   * @return string the XML data structure on success
   * @access public
   * @see DLIB_CRLF
   */
  function DLIB_getTableXML($db, $table, $limitFrom = 0, $limitTo = 0, $startTable, $endTable)
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

    $buffer = '  <!-- ' . $startTable . $table . ' -->' . DLIB_CRLF;
    while ($record = $localConn->fetchRow(MYSQL_ASSOC))
    {
      $buffer .= '    <' . $table . '>' . DLIB_CRLF;
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
                  . '</' . $fields[$i] . '>' . DLIB_CRLF;
        }
        else
        {
          $buffer .= '      <' . $fields[$i]
                  . ' type="' . $types[$i] . '"'
                  . ($nulls[$i] ? ' null="' . $nulls[$i] . '"' : '')
                  . ($keys[$i] ? ' key="' . $keys[$i] . '"' : '')
                  . ($defaults[$i] ? ' default="' . $defaults[$i] . '"' : '')
                  . ($extras[$i] ? ' extra="' . $extras[$i] . '"' : '')
                  . ' />' . DLIB_CRLF;
        }
      }
      $buffer .= '    </' . $table . '>' . DLIB_CRLF;
    }
    //$localConn->close(); // don't remove the comment mark
    $buffer .= '  <!-- ' . $endTable . $table . ' -->' . DLIB_CRLF;

    return $buffer;
  } // end of the 'DLIB_getTableXML()' function
} // $__DUMP_LIB__
?>
