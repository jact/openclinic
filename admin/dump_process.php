<?php
/**
 * dump_process.php
 *
 * Contains database dump process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: dump_process.php,v 1.21 2008/03/23 11:58:56 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "dump";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR);

  require_once("../lib/Form.php");

  Form::compareToken('../admin/dump_view_form.php');

  require_once("../lib/Dump.php");
  require_once("../lib/Check.php");

  /**
   * Increase time limit for script execution and initializes some variables
   */
  @set_time_limit(OPEN_EXEC_TIME_LIMIT);
  $dumpBuffer = "";
  $_POST = Check::safeArray($_POST);

  /**
   * Send headers depending on whether the user choosen to download a dump file or not
   */
  // No download
  if (empty($_POST['as_file']))
  {
    /**
     * Show page
     */
    $title = _("Dump result");
    include_once("../layout/header.php");

    /**
     * Breadcrumb
     */
    $links = array(
      _("Admin") => "../admin/index.php",
      _("Dumps") => "../admin/dump_view_form.php",
      $title => ""
    );
    echo HTML::breadcrumb($links, "icon icon_dump");
    unset($links);

    echo HTML::start('pre', array('width' => 80, 'class' => 'sqlcode'));
  }
  else // Download
  {
    // Defines filename and extension, and also mime types
    if (count($_POST['table_select']) != 1)
    {
      $filename = OPEN_DATABASE;
    }
    else
    {
      $filename = $_POST['table_select'][0];
    }
    $filename .= "-" . OPEN_VERSION . date("_Y-m-d_H-i-s");

    if ($_POST['what'] == 'csv' || $_POST['what'] == 'excel')
    {
      $ext      = 'csv';
      $mimeType = 'text/x-csv';
    }
    elseif ($_POST['what'] == 'xml')
    {
      $ext      = 'xml';
      $mimeType = 'text/xml';
    }
    else
    {
      $ext      = 'sql';
      // 'application/octet-stream' is the registered IANA type but
      // MSIE and Opera seems to prefer 'application/octetstream'
      $mimeType = (DUMP_USR_BROWSER_AGENT == 'IE' || DUMP_USR_BROWSER_AGENT == 'OPERA')
                ? 'application/octetstream'
                : 'application/octet-stream';
    }

    // Send headers
    header('Content-Type: ' . $mimeType);

    // IE need specific headers
    if (DUMP_USR_BROWSER_AGENT == 'IE')
    {
      header('Content-Disposition: inline; filename="' . $filename . '.' . $ext . '"');
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');
    }
    else
    {
      header('Content-Disposition: attachment; filename="' . $filename . '.' . $ext . '"');
      header('Expires: 0');
      header('Pragma: no-cache');
    }
  } // end download

  /**
   * Builds the dump
   */
  // Gets the number of tables if a dump of a database has been required
  if ( !isset($_POST['table_select']) || count($_POST['table_select']) != 1 )
  {
    $auxConn = new DbConnection();
    $auxConn->connect();
    $result = $auxConn->listTables();
    $numTables = ($result) ? $auxConn->numRows() : 0;
    $single = false;
  }
  else
  {
    $numTables = 1;
    $single = true;
  }

  // No table -> error message
  if ($numTables == 0)
  {
    echo '# ' . _("No tables found in database.");
  }
  // At least one table -> do the work
  else
  {
    // No csv or xml format -> add some comments at the top
    if ($_POST['what'] != 'csv' && $_POST['what'] != 'excel' && $_POST['what'] != 'xml')
    {
      switch ($_POST['what'])
      {
        case "data":
          $auxStr = _("Structure and data");
          break;

        case "structure":
          $auxStr = _("Structure only");
          break;

        case "dataonly":
          $auxStr = _("Data only");
          break;
      }

      $dumpBuffer .= '# OpenClinic MySQL-Dump' . DUMP_CRLF
                   . '# version ' . OPEN_VERSION . DUMP_CRLF
                   . '# http://openclinic.sourceforge.net/' . DUMP_CRLF
                   . '#' . DUMP_CRLF
                   . '# ' . _("Type") . ": " . $auxStr . DUMP_CRLF
                   . '# ' . _("Host") . ": " . OPEN_HOST . DUMP_CRLF
                   . '# ' . _("Generation Time") . ": " . I18n::localDate() . DUMP_CRLF
                   . '# ' . _("Server Version") . ': ' . DUMP_MYSQL_VERSION . DUMP_CRLF
                   . '# ' . _("PHP Version") . ": " . phpversion() . DUMP_CRLF
                   . '# ' . _("Database") . ": " . OPEN_DATABASE . DUMP_CRLF;

      if (isset($_POST['table_select']))
      {
        $tableSelect = implode($_POST['table_select'], ', ');
        $tmpSelect = implode($_POST['table_select'], OPEN_SEPARATOR);
        $tmpSelect = OPEN_SEPARATOR . $tmpSelect . OPEN_SEPARATOR;
      }
      else
      {
        $tableSelect = _("All Tables");
      }
      $dumpBuffer .= '# ' . sprintf(_("Table Summary: %s"), $tableSelect) . DUMP_CRLF
                   . '# ' . str_repeat('-', 45) . DUMP_CRLF;

      if (isset($_POST['create_db']))
      {
        $dumpBuffer .= DUMP_CRLF . 'CREATE DATABASE ' . OPEN_DATABASE . ';' . DUMP_CRLF;
      }

      if (isset($_POST['use_dbname']))
      {
        $dumpBuffer .= DUMP_CRLF . 'USE ' . OPEN_DATABASE . ';' . DUMP_CRLF;
      }

      for ($i = 0; $i < $numTables; $i++)
      {
        $table = ($single ? $_POST['table_select'][$i] : $auxConn->tableName($i));

        if ((isset($tmpSelect) && strpos(' ' . $tmpSelect, OPEN_SEPARATOR . $table . OPEN_SEPARATOR))
            || (!isset($tmpSelect) && !empty($table)))
        {
          $formattedTableName = (isset($_POST['use_backquotes']))
                              ? Dump::backQuote($table)
                              : '"' . $table . '"';

          // If only datas, no need to displays table name
          if ($_POST['what'] != 'dataonly')
          {
            $dumpBuffer .= DUMP_CRLF . '#' . DUMP_CRLF
                        . '# ' . sprintf(_("Table structure for table %s"), $formattedTableName)
                        . DUMP_CRLF . '#' . DUMP_CRLF . DUMP_CRLF
                        . Dump::SQLDefinition(OPEN_DATABASE, $table,
                          array(
                            'drop' => isset($_POST['drop']) ? $_POST['drop'] : null,
                            'use_backquotes' => isset($_POST['use_backquotes']) ? $_POST['use_backquotes'] : null
                          )
                        )
                        . ';' . DUMP_CRLF;
          }

          // At least data
          if (($_POST['what'] == 'data') || ($_POST['what'] == 'dataonly'))
          {
            $dumpBuffer .= DUMP_CRLF . '#' . DUMP_CRLF
                        . '# ' . sprintf(_("Dumping data for table %s"), $formattedTableName)
                        . DUMP_CRLF . '#' . DUMP_CRLF . DUMP_CRLF;

            if ($_POST['what'] == 'dataonly' && isset($_POST['add_delete']))
            {
              $dumpBuffer .= 'DELETE * FROM ' . $formattedTableName . ';' . DUMP_CRLF . DUMP_CRLF;
            }

            if ( !isset($limitFrom) || !isset($limitTo) )
            {
              $limitFrom = $limitTo = 0;
            }

            $dumpBuffer .= Dump::SQLData(OPEN_DATABASE, $table,
              array(
                'from' => $limitFrom,
                'to' => $limitTo,
                'use_backquotes' => isset($_POST['use_backquotes']) ? $_POST['use_backquotes'] : null,
                'show_columns' => isset($_POST['show_columns']) ? $_POST['show_columns'] : null,
                'extended_inserts' => isset($_POST['extended_inserts']) ? $_POST['extended_inserts'] : null
              )
            );
          } // end if
        } // end if
      } // end for

      // don't remove, it makes easier to select & copy from browser
      $dumpBuffer .= DUMP_CRLF;
    } // end 'no csv or xml' case

    // 'xml' case
    elseif ($_POST['what'] == 'xml')
    {
      // first add the xml tag
      $dumpBuffer .= '<?xml version="1.0" encoding="ISO-8859-1" ?>' . DUMP_CRLF . DUMP_CRLF;
      // some comments
      $dumpBuffer .= '<!--' . DUMP_CRLF
                  . '--' . DUMP_CRLF
                  . '-- OpenClinic XML-Dump' . DUMP_CRLF
                  . '-- version ' . OPEN_VERSION . DUMP_CRLF
                  . '-- http://openclinic.sourceforge.net/' . DUMP_CRLF
                  . '--' . DUMP_CRLF
                  . '-- ' . _("Host") . ": " . OPEN_HOST . DUMP_CRLF
                  . '-- ' . _("Generation Time") . ": " . I18n::localDate() . DUMP_CRLF
                  . '-- ' . _("Server Version") . ': ' . DUMP_MYSQL_VERSION . DUMP_CRLF
                  . '-- ' . _("PHP Version") . ": " . phpversion() . DUMP_CRLF
                  . '-- ' . _("Database") . ": " . OPEN_DATABASE . DUMP_CRLF;

      if (isset($_POST['table_select']))
      {
        $tableSelect = implode($_POST['table_select'], ', ');
        $tmpSelect = implode($_POST['table_select'], OPEN_SEPARATOR);
        $tmpSelect = OPEN_SEPARATOR . $tmpSelect . OPEN_SEPARATOR;
      }
      else
      {
        $tableSelect = _("All Tables");
      }
      $dumpBuffer .= '-- ' . sprintf(_("Table Summary: %s"), $tableSelect) . DUMP_CRLF
                  . '--' . DUMP_CRLF
                  . '-->' . DUMP_CRLF . DUMP_CRLF;

      // Now build the structure
      // TODO: Make db and table names XML compatible (designer responsability)
      $dumpBuffer .= '<database name="' . OPEN_DATABASE . '">' . DUMP_CRLF;
      if (isset($_POST['table_select']))
      {
        $tmpSelect = implode($_POST['table_select'], OPEN_SEPARATOR);
        $tmpSelect = OPEN_SEPARATOR . $tmpSelect . OPEN_SEPARATOR;
      }

      if ( !isset($limitFrom) || !isset($limitTo) )
      {
        $limitFrom = $limitTo = 0;
      }

      for ($i = 0; $i < $numTables; $i++)
      {
        $table = ($single ? $_POST['table_select'][$i] : $auxConn->tableName($i));

        if ((isset($tmpSelect) && strpos(' ' . $tmpSelect, OPEN_SEPARATOR . $table . OPEN_SEPARATOR))
            || (!isset($tmpSelect) && !empty($table)))
        {
          $dumpBuffer .= Dump::XMLData(OPEN_DATABASE, $table,
            array(
              'from' => $limitFrom,
              'to' => $limitTo
            )
          );
        } // end if
      } // end for
      $dumpBuffer .= '</database>' . DUMP_CRLF;
    } // end 'xml' case

    else // 'csv' case
    {
      if (isset($_POST['table_select']))
      {
        $tmpSelect = implode($_POST['table_select'], OPEN_SEPARATOR);
        $tmpSelect = OPEN_SEPARATOR . $tmpSelect . OPEN_SEPARATOR;
      }

      if ( !isset($limitFrom) || !isset($limitTo) )
      {
        $limitFrom = $limitTo = 0;
      }

      for ($i = 0; $i < $numTables; $i++)
      {
        $table = ($single ? $_POST['table_select'][$i] : $auxConn->tableName($i));

        if ((isset($tmpSelect) && strpos(' ' . $tmpSelect, OPEN_SEPARATOR . $table . OPEN_SEPARATOR))
            || (!isset($tmpSelect) && !empty($table)))
        {
          $dumpBuffer .= Dump::CSVData(OPEN_DATABASE, $table,
            array(
              'from' => $limitFrom,
              'to' => $limitTo,
              'what' => isset($_POST['what']) ? $_POST['what'] : null
            )
          );
        } // end if
      } // end for
    } // end 'csv' case
  } // end building the dump

  // Free memory if is necessary
  if ( !$single ) // == false
  {
    $auxConn->close();
    unset($auxConn);
  }

  /**
   * "Displays" the dump...
   */
  echo (isset($_POST['as_file']) ? $dumpBuffer : HTML::xmlEntities($dumpBuffer));

  /**
   * Close the html tags and add the footers in dump is displayed on screen
   */
  if (empty($_POST['as_file']))
  {
    echo HTML::end('pre');
    echo HTML::para(HTML::link(_("Back return"), "../admin/dump_view_form.php"));

    include_once('../layout/footer.php');
  } // end if
?>
