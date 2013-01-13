<?php
/**
 * Dump.php
 *
 * Contains the class Dump
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Dump.php,v 1.5 2013/01/13 14:22:58 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

/**
 * Constants:
 *  DUMP_MYSQL_INT_VERSION (int)    - eg: 32339 instead of 3.23.39
 *  DUMP_MYSQL_VERSION     (string) - eg: 3.23.39
 *  DUMP_USR_OS            (string) - the platform (OS) of the user
 *  DUMP_USR_BROWSER_AGENT (string) - the browser of the user
 *  DUMP_USR_BROWSER_VER   (double) - the version of the browser
 *  DUMP_CRLF              (string) - CR LF sequence
 *  @todo $isWindows = (DIRECTORY_SEPARATOR == '\\');
 */

/**
 * DUMP_MYSQL_INT_VERSION, DUMP_MYSQL_VERSION
 */
if ( !defined('DUMP_MYSQL_INT_VERSION') )
{
  if (defined('OPEN_HOST'))
  {
    $auxConn = new DbConnection();
    $result = $auxConn->connect();
    if ($result != false)
    {
      $result = $auxConn->exec("SELECT VERSION() AS version;");
      if ($result != false && $auxConn->numRows() > 0)
      {
        $row   = $auxConn->fetchRow(MYSQL_ASSOC);
        define('DUMP_MYSQL_VERSION', $row['version']);
        $match = explode('.', $row['version']);
      }
      else
      {
        $result = $auxConn->exec("SHOW VARIABLES LIKE 'version';");
        if ($result != false && $auxConn->numRows() > 0)
        {
          $row   = $auxConn->fetchRow(MYSQL_NUM);
          define('DUMP_MYSQL_VERSION', $row[1]);
          $match = explode('.', $row[1]);
        }
      }
    }
    $auxConn->close();
    unset($auxConn);
  } // end server id is defined case

  if ( !isset($match) || !isset($match[0]) )
  {
    $match[0] = 3;
  }
  if ( !isset($match[1]) )
  {
    $match[1] = 21;
  }
  if ( !isset($match[2]) )
  {
    $match[2] = 0;
  }

  define('DUMP_MYSQL_INT_VERSION', (int)sprintf('%d%02d%02d', $match[0], $match[1], intval($match[2])));
  unset($match);
}

/**
 * DUMP_USR_OS, DUMP_USR_BROWSER_VER, DUMP_USR_BROWSER_AGENT
 * Determines platform (OS), browser and version of the user
 * Based on a phpBuilder article:
 * @see http://www.phpbuilder.net/columns/tim20000821.php
 */
if ( !defined('DUMP_USR_OS') )
{
  if ( !empty($_SERVER['HTTP_USER_AGENT']) )
  {
    $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
  }
  elseif ( !isset($HTTP_USER_AGENT) )
  {
    $HTTP_USER_AGENT = '';
  }

  // 1. Platform
  if (strstr($HTTP_USER_AGENT, 'Win'))
  {
    define('DUMP_USR_OS', 'Win');
  }
  elseif (strstr($HTTP_USER_AGENT, 'Mac'))
  {
    define('DUMP_USR_OS', 'Mac');
  }
  elseif (strstr($HTTP_USER_AGENT, 'Linux'))
  {
    define('DUMP_USR_OS', 'Linux');
  }
  elseif (strstr($HTTP_USER_AGENT, 'Unix'))
  {
    define('DUMP_USR_OS', 'Unix');
  }
  elseif (strstr($HTTP_USER_AGENT, 'OS/2'))
  {
    define('DUMP_USR_OS', 'OS/2');
  }
  else
  {
    define('DUMP_USR_OS', 'Other');
  }

  // 2. browser and version
  if (preg_match('/Opera(\/| )([0-9].[0-9]{1,2})/', $HTTP_USER_AGENT, $logVersion))
  {
    define('DUMP_USR_BROWSER_VER', $logVersion[2]);
    define('DUMP_USR_BROWSER_AGENT', 'OPERA');
  }
  elseif (preg_match('/MSIE ([0-9].[0-9]{1,2})/', $HTTP_USER_AGENT, $logVersion))
  {
    define('DUMP_USR_BROWSER_VER', $logVersion[1]);
    define('DUMP_USR_BROWSER_AGENT', 'IE');
  }
  elseif (preg_match('/OmniWeb\/([0-9].[0-9]{1,2})/', $HTTP_USER_AGENT, $logVersion))
  {
    define('DUMP_USR_BROWSER_VER', $logVersion[1]);
    define('DUMP_USR_BROWSER_AGENT', 'OMNIWEB');
  }
  elseif (preg_match('/Mozilla\/([0-9].[0-9]{1,2})/', $HTTP_USER_AGENT, $logVersion))
  {
    define('DUMP_USR_BROWSER_VER', $logVersion[1]);
    define('DUMP_USR_BROWSER_AGENT', 'MOZILLA');
  }
  elseif (preg_match('/Konqueror\/([0-9].[0-9]{1,2})/', $HTTP_USER_AGENT, $logVersion))
  {
    define('DUMP_USR_BROWSER_VER', $logVersion[1]);
    define('DUMP_USR_BROWSER_AGENT', 'KONQUEROR');
  }
  else
  {
    define('DUMP_USR_BROWSER_VER', 0);
    define('DUMP_USR_BROWSER_AGENT', 'OTHER');
  }
}

if (defined("DUMP_USR_OS") && DUMP_USR_OS == 'Win')
{
  define("DUMP_CRLF", "\r\n");
}
// Mac case
elseif (defined("DUMP_USR_OS") && DUMP_USR_OS == 'Mac')
{
  define("DUMP_CRLF", "\r");
}
// Others
else
{
  define("DUMP_CRLF", "\n");
}

/**
 * Dump set of functions used to build dumps of tables
 *
 * Methods:
 *  mixed backQuote(mixed $mixedVar, bool $doIt = true)
 *  string addSlashes(string $text, bool $isLike = false)
 *  mixed SQLDefinition(string $db, string $table, array $options = null)
 *  mixed SQLData(string $db, string $table, array $options = null)
 *  mixed CSVData(string $db, string $table, array $options = null)
 *  mixed XMLData(string $db, string $table, array $options = null)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class Dump
{
  /**
   * mixed backQuote(mixed $mixedVar, bool $doIt = true)
   *
   * Adds backquotes on both sides of a database, table or field name.
   * Since MySQL 3.23.06 this allows to use non-alphanumeric characters in these names.
   *
   * @param mixed $mixedVar the database, table or field name to "backquote" or array of it
   * @param bool $doIt (optional) a flag to bypass this function (used by dump functions)
   * @return mixed the "backquoted" database, table or field name if the
   *               current MySQL release is >= 3.23.06, the original one else
   * @access public
   * @static
   */
  public static function backQuote($mixedVar, $doIt = true)
  {
    if ($doIt == false || DUMP_MYSQL_INT_VERSION < 32306 || empty($mixedVar) || $mixedVar == '*')
    {
      return $mixedVar;
    }

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

  /**
   * string addSlashes(string $text, bool $isLike = false)
   *
   * Add slashes before "'" and "\" characters so a value containing
   * them can be used in a sql comparison.
   *
   * @param string $text the string to slash
   * @param bool $isLike (optional) whether the string will be used in a 'LIKE' clause
   *             (it then requires two more escaped sequences) or not
   * @return string the slashed string
   * @access public
   * @static
   */
  public static function addSlashes($text, $isLike = false)
  {
    $text = ($isLike)
      ? str_replace('\\', '\\\\\\\\', $text)
      : str_replace('\\', '\\\\', $text);

    $text = str_replace('\'', '\\\'', $text);

    return $text;
  }

  /**
   * mixed SQLDefinition(string $db, string $table, array $options = null)
   *
   * Returns $table's CREATE definition
   *
   * @param string $db database name
   * @param string $table table name
   * @param array $options (optional) (drop, use_backquotes)
   * @return mixed false if error occurs, string with CREATE statement if OK
   * @access public
   * @static
   * @see DUMP_CRLF, DUMP_MYSQL_INT_VERSION
   */
  public static function SQLDefinition($db, $table, $options = null)
  {
    $schemaCreate = '';
    $useBackquote = (isset($options['use_backquotes']) ? $options['use_backquotes'] : false);
    if (isset($options['drop']) && $options['drop'])
    {
      $schemaCreate .= 'DROP TABLE IF EXISTS ' . self::backQuote($table, $useBackquote) . ';' . DUMP_CRLF;
    }

    $localConn = new DbConnection();
    if ( !$localConn->connect() )
    {
      return false;
    }

    if (DUMP_MYSQL_INT_VERSION >= 32321)
    {
      $result = $localConn->exec('SHOW TABLE STATUS FROM ' . $db . ' LIKE "' . self::addSlashes($table) . '"');
      if ($result != false && $localConn->numRows() > 0)
      {
        $tmpRes = $localConn->fetchRow(MYSQL_ASSOC);

        if (isset($tmpRes['Create_time']) && !empty($tmpRes['Create_time']))
        {
          $schemaCreate .= '# ' . _("Create Time") . ': ' . I18n::localDate($tmpRes['Create_time']) . DUMP_CRLF;
        }

        if (isset($tmpRes['Update_time']) && !empty($tmpRes['Update_time']))
        {
          $schemaCreate .= '# ' . _("Update Time") . ': ' . I18n::localDate($tmpRes['Update_time']) . DUMP_CRLF;
        }

        if (isset($tmpRes['Check_time']) && !empty($tmpRes['Check_time']))
        {
          $schemaCreate .= '# ' . _("Check Time") . ': ' . I18n::localDate($tmpRes['Check_time']) . DUMP_CRLF;
        }
        $schemaCreate .= DUMP_CRLF;
      }
      $localConn->freeResult();

      // Whether to quote table and fields names or not
      $localConn->exec('SET SQL_QUOTE_SHOW_CREATE=' . ($useBackquote ? 1 : 0));

      $localQuery = 'SHOW CREATE TABLE ' . self::backQuote($db) . '.' . self::backQuote($table);
      $result = $localConn->exec($localQuery);
      if ($result != false && $localConn->numRows() > 0)
      {
        $tmpRes    = $localConn->fetchRow(MYSQL_NUM);
        $pos       = strpos($tmpRes[1], ' (');
        $tmpRes[1] = substr($tmpRes[1], 0, 13) // strlen('CREATE TABLE ') = 13
                   . ($useBackquote ? self::backQuote($tmpRes[0]) : $tmpRes[0])
                   . substr($tmpRes[1], $pos);
        $schemaCreate .= str_replace("\n", DUMP_CRLF, $tmpRes[1]);
      }
      $localConn->close();

      return $schemaCreate;
    } // end if MySQL >= 3.23.21

    // For MySQL < 3.23.20
    $schemaCreate .= 'CREATE TABLE ' . self::backQuote($table, $useBackquote) . ' (' . DUMP_CRLF;

    $localQuery = 'SHOW FIELDS FROM ' . self::backQuote($db) . '.' . self::backQuote($table);
    if ( !$localConn->exec($localQuery) )
    {
      return false;
    }

    while ($row = $localConn->fetchRow())
    {
      $schemaCreate .= '   ' . self::backQuote($row['Field'], $useBackquote) . ' ' . strtoupper($row['Type']);
      if (isset($row['Default']) && !empty($row['Default']) )
      {
        $schemaCreate .= ' DEFAULT "' . self::addSlashes($row['Default']) . '"';
      }

      if ($row['Null'] != 'YES')
      {
        $schemaCreate .= ' NOT NULL';
      }

      if ($row['Extra'] != '')
      {
        $schemaCreate .= ' ' . $row['Extra'];
      }

      $schemaCreate .= ',' . DUMP_CRLF;
    } // end while
    $schemaCreate = preg_replace('/,' . DUMP_CRLF . '$/', '', $schemaCreate);

    $localQuery = 'SHOW KEYS FROM ' . self::backQuote($db) . '.' . self::backQuote($table);
    if ( !$localConn->exec($localQuery) )
    {
      return false;
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
        $index[$kname][] = self::backQuote($row['Column_name'], $useBackquote) . '(' . $subPart . ')';
      }
      else
      {
        $index[$kname][] = self::backQuote($row['Column_name'], $useBackquote);
      }
    } // end while
    $localConn->close();

    while (list($x, $columns) = @each($index))
    {
      $schemaCreate     .= ',' . DUMP_CRLF;
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

    $schemaCreate .= DUMP_CRLF . ')';

    return $schemaCreate;
  }

  /**
   * mixed SQLData(string $db, string $table, array $options = null)
   *
   * PHP >= 4.0.5 only : get the content of $table as a series of INSERT statements
   *
   * @param string $db the current database name
   * @param string $table the current table name
   * @param array $options (optional) (use_backquotes, show_columns, extended_inserts, from, to)
   * @return mixed false if error occurs, string if OK
   * @access public
   * @static
   * @see DUMP_CRLF
   */
  public static function SQLData($db, $table, $options = null)
  {
    $useBackquote = (isset($options['use_backquotes']) ? $options['use_backquotes'] : false);

    // Defines the offsets to use
    ((isset($options['to']) && $options['to'] > 0) && (isset($options['from']) && $options['from'] >= 0))
      ? $limitClause = ' LIMIT ' . (($options['from'] > 0) ? $options['from'] . ', ' : '') . $options['to']
      : $limitClause = '';

    $localConn = new DbConnection();
    if ( !$localConn->connect() )
    {
      return false;
    }

    $localQuery = 'SELECT * FROM ' . self::backQuote($db) . '.' . self::backQuote($table) . $limitClause;
    if ( !$localConn->exec($localQuery) )
    {
      return false;
    }

    $numFields = $localConn->numFields();
    $numRows   = $localConn->numRows();

    // Checks whether the field is an integer or not
    for ($j = 0; $j < $numFields; $j++)
    {
      $fieldSet[$j] = self::backQuote($localConn->fieldName($j), $useBackquote);
      $type         = $localConn->fieldType($j);

      $fieldNum[$j] = ($type == 'tinyint' || $type == 'smallint' || $type == 'mediumint' ||
                       $type == 'int' || $type == 'bigint' ||$type == 'timestamp');
    } // end for

    // Sets the scheme
    $schemaInsert = 'INSERT INTO ' . self::backQuote($table, $useBackquote);
    if (isset($options['show_columns']) && $options['show_columns'])
    {
      $fields       = implode(', ', $fieldSet);
      $schemaInsert .= ' (' . $fields . ')';
    }
    $schemaInsert .= ' VALUES (';

    $search     = array("\x00", "\x0a", "\x0d", "\x1a"); //\x08\\x09, not required
    $replace    = array('\0', '\n', '\r', '\Z');
    $currentRow = 0;

    set_time_limit(OPEN_EXEC_TIME_LIMIT);

    $buffer = '';
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
            $values[] = "'" . str_replace($search, $replace, self::addSlashes($row[$j])) . "'";
          }
        }
        else
        {
          $values[] = "''";
        } // end if
      } // end for

      // Extended inserts case
      if (isset($options['extended_inserts']) && $options['extended_inserts'])
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
      $buffer .= $insertLine . DUMP_CRLF;
    } // end while
    $localConn->close();

    if (isset($options['extended_inserts']) && $options['extended_inserts'])
    {
      $buffer .= ';' . DUMP_CRLF;
    }

    return $buffer;
  }

  /**
   * mixed CSVData(string $db, string $table, array $options = null)
   *
   * Returns the content of a table in CSV format
   *
   * @param string $db the database name
   * @param string $table the table name
   * @param string $what whether to obtain an excel compatible csv format or a simple csv one
   * @param array $options (optional) (from, to, what = {excel, csv})
   * @return mixed false if error occurs, string if ok
   * @access public
   * @static
   * @see DUMP_CRLF
   */
  public static function CSVData($db, $table, $options = null)
  {
    $what = isset($options['what']) ? $options['what'] : 'excel';

    // Handles the EOL character
    $crlf = DUMP_CRLF;
    if ($what == 'excel')
    {
      $crlf = "\015\012";
    }
    else
    {
      if (get_magic_quotes_gpc())
      {
        $crlf = stripslashes($crlf);
      }
      $crlf = str_replace('\\r', "\015", $crlf);
      $crlf = str_replace('\\n', "\012", $crlf);
      $crlf = str_replace('\\t', "\011", $crlf);
    } // end if

    // Handles the "separator" and the optional "enclosed by" characters
    $sep = '';
    if ($what == 'excel')
    {
      $sep = ',';
    }
    else
    {
      if (get_magic_quotes_gpc())
      {
        $sep = stripslashes($sep);
      }
      $sep = str_replace('\\t', "\011", $sep);
    }

    $encBy = '';
    if ($what == 'excel')
    {
      $encBy = '"';
    }
    elseif (get_magic_quotes_gpc())
    {
      $encBy = stripslashes($encBy);
    }

    $escBy = '';
    if ($what == 'excel' || (empty($escBy) && $encBy != ''))
    {
      // double the "enclosed by" character
      $escBy = $encBy;
    }
    elseif (get_magic_quotes_gpc())
    {
      $escBy = stripslashes($escBy);
    }

    // Defines the offsets to use
    ((isset($options['to']) && $options['to'] > 0) && (isset($options['from']) && $options['from'] >= 0))
      ? $limitClause = ' LIMIT ' . (($options['from'] > 0) ? $options['from'] . ', ' : '') . $options['to']
      : $limitClause = '';

    $localConn = new DbConnection();
    if ( !$localConn->connect() )
    {
      return false;
    }

    // Gets the data from the database
    $localQuery = 'SELECT * FROM ' . self::backQuote($db) . '.' . self::backQuote($table) . $limitClause;
    if ( !$localConn->exec($localQuery) )
    {
      return false;
    }
    if ($localConn->numRows() == 0)
    {
      return '';
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
      ? $fnames .= $localConn->fieldName($i) . $crlf
      : $fnames .= $encBy . str_replace($encBy, $escBy . $encBy, $localConn->fieldName($i)) . $encBy . $crlf;

    $buffer = trim($fnames) . $crlf;

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
            $row[$j] = preg_replace("/\015(\012)?/", "\012", $row[$j]);
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
      $buffer .= trim($dataTable) . $crlf;
      ++$i;
    } // end while
    $buffer .= $crlf;
    $localConn->close();

    return $buffer;
  }

  /**
   * mixed XMLData(string $db, string $table, array $options = null)
   *
   * Returns the content of a table in XML format (Propel model more or less)
   *
   * @param string $db database name
   * @param string $table table name
   * @param array $options (optional) (from, to, start_table, end_table)
   * @return mixed false if error occurs, string if ok
   * @access public
   * @static
   * @see DUMP_CRLF
   */
  public static function XMLData($db, $table, $options = null)
  {
    $localConn = new DbConnection();
    if ( !$localConn->connect() )
    {
      return false;
    }

    $localQuery = 'SHOW COLUMNS FROM ' . self::backQuote($table) . ' FROM ' . self::backQuote($db);
    if ( !$localConn->exec($localQuery) )
    {
      return false;
    }

    for ($i = 0; $row = $localConn->fetchRow(MYSQL_ASSOC); $i++)
    {
      $fields[$i] = $row['Field'];
      $types[$i] = $row['Type'];
      $nulls[$i] = (strtoupper($row['Null']) == 'YES') ? 'true' : 'false';
      $keys[$i] = $row['Key'];
      $defaults[$i] = $row['Default'];
      $extras[$i] = $row['Extra'];
    }
    $numFields = count($fields);

    // Defines the offsets to use
    $limitClause = ((isset($options['to']) && $options['to'] > 0) && (isset($options['from']) && $options['from'] >= 0))
      ? ' LIMIT ' . (($options['from'] > 0) ? $options['from'] . ', ' : '') . $options['to']
      : '';

    $localQuery = 'SELECT * FROM ' . self::backQuote($db) . '.' . self::backQuote($table) . $limitClause;
    if ( !$localConn->exec($localQuery) )
    {
      return false;
    }
    if ($localConn->numRows() == 0)
    {
      return '';
    }

    $buffer = '  <table name="' . $table . '">' . DUMP_CRLF;
    while ($record = $localConn->fetchRow(MYSQL_ASSOC))
    {
      $buffer .= '    <row>' . DUMP_CRLF;
      for ($i = 0; $i < $numFields; $i++)
      {
        $element = ' name="' . $fields[$i] . '"'
                 . ' type="' . $types[$i] . '"'
                 . ($nulls[$i] ? ' null="' . $nulls[$i] . '"' : '')
                 . ($keys[$i] ? ' key="' . $keys[$i] . '"' : '')
                 . ($defaults[$i] ? ' default="' . $defaults[$i] . '"' : '')
                 . ($extras[$i] ? ' extra="' . $extras[$i] . '"' : '');
        if ( !is_null($record[$fields[$i]]) )
        {
          $buffer .= '      <column' . $element
                  . '>' . htmlspecialchars($record[$fields[$i]])
                  . '</column>' . DUMP_CRLF;
        }
        else
        {
          $buffer .= '      <column' . $element . ' />' . DUMP_CRLF;
        }
      }
      $buffer .= '    </row>' . DUMP_CRLF;
    }
    $localConn->close();
    $buffer .= '  </table>' . DUMP_CRLF;

    return $buffer;
  }
} // end class
?>
