<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: dump_process.php,v 1.1 2004/02/29 16:06:22 jact Exp $
 */

/**
 * dump_process.php
 ********************************************************************
 * Contains database dump process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 29/02/04 17:06
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "dump";

  require_once("../shared/read_settings.php");
  require_once("../admin/dump_defines.php");
  require_once("../lib/dump_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Increase time limit for script execution and initializes some variables
  ////////////////////////////////////////////////////////////////////
  @set_time_limit(EXEC_TIME_LIMIT);
  $dumpBuffer = "";
  $crlf = DLIB_whichCrlf(); // defines the default <CR><LF> format

  ////////////////////////////////////////////////////////////////////
  // Send headers depending on whether the user choosen to download a dump file or not
  ////////////////////////////////////////////////////////////////////
  // No download
  if (empty($_POST['as_file']))
  {
    ////////////////////////////////////////////////////////////////////
    // Show page
    ////////////////////////////////////////////////////////////////////
    $title = _("Dump result");
    include_once("../shared/header.php");

    ////////////////////////////////////////////////////////////////////
    // Navigation links
    ////////////////////////////////////////////////////////////////////
    include_once("../shared/navigation_links.php");
    $links = array(
      _("Admin") => "../admin/index.php",
      _("Dumps") => "../admin/dump_view_form.php",
      $title => ""
    );
    showNavLinks($links, "dumps.png");
    unset($links);

    echo '<div class="sqlcode">' . "\n";
    echo '  <pre width="80">' . "\n";
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
      $mimeType = (DLIB_USR_BROWSER_AGENT == 'IE' || DLIB_USR_BROWSER_AGENT == 'OPERA')
                ? 'application/octetstream'
                : 'application/octet-stream';
    }

    // Send headers
    header('Content-Type: ' . $mimeType);

    // IE need specific headers
    if (DLIB_USR_BROWSER_AGENT == 'IE')
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

  ////////////////////////////////////////////////////////////////////
  // Builds the dump
  ////////////////////////////////////////////////////////////////////
  // Gets the number of tables if a dump of a database has been required
  if (count($_POST['table_select']) != 1)
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

      $dumpBuffer .= '# OpenClinic MySQL-Dump' . $crlf
                   . '# version ' . OPEN_VERSION . $crlf
                   . '# http://openclinic.sourceforge.net/' . $crlf
                   . '#' . $crlf
                   . '# ' . _("Type") . ": " . $auxStr . $crlf
                   . '# ' . _("Host") . ": " . OPEN_HOST . $crlf
                   . '# ' . _("Generation Time") . ": " . DLIB_localisedDate() . $crlf
                   . '# ' . _("Server Version")
                   . ': ' . substr(DLIB_MYSQL_INT_VERSION, 0, 1)
                   . '.' . substr(DLIB_MYSQL_INT_VERSION, 1, 2)
                   . '.' . substr(DLIB_MYSQL_INT_VERSION, 3) . $crlf
                   . '# ' . _("PHP Version") . ": " . phpversion() . $crlf
                   . '# ' . _("Database") . ": " . OPEN_DATABASE . $crlf;

      if (isset($_POST['use_dbname']))
      {
        $dumpBuffer .= '# ---------------------------------------------' . $crlf
                     . $crlf . 'USE ' . OPEN_DATABASE . ';' . $crlf;
      }

      if (isset($_POST['table_select']))
      {
        $tmpSelect = implode($_POST['table_select'], '|');
        $tmpSelect = '|' . $tmpSelect . '|';
      }

      $i = 0;
      while ($i < $numTables)
      {
        $table = ($single ? $_POST['table_select'][$i] : $auxConn->tableName($i));

        if ((isset($tmpSelect) && strpos(' ' . $tmpSelect, '|' . $table . '|'))
            || (!isset($tmpSelect) && !empty($table)))
        {
          $formattedTableName = (isset($_POST['use_backquotes']))
                              ? DLIB_backquote($table)
                              : '\'' . $table . '\'';

          // If only datas, no need to displays table name
          if ($_POST['what'] != 'dataonly')
          {
            $dumpBuffer .= '# ---------------------------------------------' . $crlf
                        . $crlf . '#' . $crlf
                        . '# ' . sprintf(_("Table structure for table %s"), $formattedTableName)
                        . $crlf . '#' . $crlf . $crlf
                        . DLIB_getTableDef(OPEN_DATABASE, $table, $crlf, $_POST)
                        . ';' . $crlf;
          }

          // At least data
          if (($_POST['what'] == 'data') || ($_POST['what'] == 'dataonly'))
          {
            $dumpBuffer .= $crlf . '#' . $crlf
                        . '# ' . sprintf(_("Dumping data for table %s"), $formattedTableName)
                        . $crlf . '#' . $crlf . $crlf;

            if ($_POST['what'] == 'dataonly' && isset($_POST['add_delete']))
            {
              $dumpBuffer .= 'DELETE * FROM ' . $formattedTableName . ';' . $crlf . $crlf;
            }

            if ( !isset($limitFrom) || !isset($limitTo) )
            {
              $limitFrom = $limitTo = 0;
            }

            echo $dumpBuffer;
            $dumpBuffer = '';
            DLIB_getTableContent(OPEN_DATABASE, $table, $limitFrom, $limitTo, $crlf, $_POST);
          } // end if
        } // end if
        $i++;
      } // end while

      // don't remove, it makes easier to select & copy from browser
      $dumpBuffer .= $crlf;
    } // end 'no csv or xml' case

    // 'xml' case
    elseif ($_POST['what'] == 'xml')
    {
      // first add the xml tag
      $dumpBuffer .= '<?xml version="1.0" encoding="ISO-8859-1" ?>' . $crlf . $crlf;
      // some comments
      $dumpBuffer .= '<!--' . $crlf
                  . '--' . $crlf
                  . '-- OpenClinic XML-Dump' . $crlf
                  . '-- version ' . OPEN_VERSION . $crlf
                  . '-- http://openclinic.sourceforge.net/' . $crlf
                  . '--' . $crlf
                  . '-- ' . _("Host") . ": " . OPEN_HOST . $crlf
                  . '-- ' . _("Generation Time") . ": " . DLIB_localisedDate() . $crlf
                  . '-- ' . _("Server Version")
                  . ': ' . substr(DLIB_MYSQL_INT_VERSION, 0, 1)
                  . '.' . substr(DLIB_MYSQL_INT_VERSION, 1, 2)
                  . '.' . substr(DLIB_MYSQL_INT_VERSION, 3) . $crlf
                  . '-- ' . _("PHP Version") . ": " . phpversion() . $crlf
                  . '-- ' . _("Database") . ": " . OPEN_DATABASE . $crlf
                  . '--' . $crlf
                  . '-->' . $crlf . $crlf;

      // Now build the structure
      // todo: Make db and table names XML compatible (designer responsability)
      $dumpBuffer .= '<' . OPEN_DATABASE . '>' . $crlf;
      if (isset($_POST['table_select']))
      {
        $tmpSelect = implode($_POST['table_select'], '|');
        $tmpSelect = '|' . $tmpSelect . '|';
      }

      if ( !isset($limitFrom) || !isset($limitTo) )
      {
        $limitFrom = $limitTo = 0;
      }

      $i = 0;
      while ($i < $numTables)
      {
        $table = ($single ? $_POST['table_select'][$i] : $auxConn->tableName($i));

        if ((isset($tmpSelect) && strpos(' ' . $tmpSelect, '|' . $table . '|'))
            || (!isset($tmpSelect) && !empty($table)))
        {
          $dumpBuffer .= DLIB_getTableXML(OPEN_DATABASE, $table, $limitFrom, $limitTo, $crlf, _("table") . " ", _("end of table") . " ");
        }
        $i++;
      }
      $dumpBuffer .= '</' . OPEN_DATABASE . '>' . $crlf;
    } // end 'xml' case

    else // 'csv' case
    {
      // Handles the EOL character
      if ($_POST['what'] == 'excel')
      {
        $addCharacter = "\015\012";
      }
      elseif (empty($addCharacter))
      {
        $addCharacter = $crlf;
      }
      else
      {
        if (get_magic_quotes_gpc())
        {
          $addCharacter = stripslashes($addCharacter);
        }
        $addCharacter = str_replace('\\r', "\015", $addCharacter);
        $addCharacter = str_replace('\\n', "\012", $addCharacter);
        $addCharacter = str_replace('\\t', "\011", $addCharacter);
      } // end if

      if (isset($_POST['table_select']))
      {
        $tmpSelect = implode($_POST['table_select'], '|');
        $tmpSelect = '|' . $tmpSelect . '|';
      }

      if ( !isset($limitFrom) || !isset($limitTo) )
      {
        $limitFrom = $limitTo = 0;
      }

      $i = 0;
      while ($i < $numTables)
      {
        $table = ($single ? $_POST['table_select'][$i] : $auxConn->tableName($i));

        if ((isset($tmpSelect) && strpos(' ' . $tmpSelect, '|' . $table . '|'))
            || (!isset($tmpSelect) && !empty($table)))
        {
          $dumpBuffer .= DLIB_getTableCSV(OPEN_DATABASE, $table, $limitFrom, $limitTo, $separator, $enclosed, $escaped, $addCharacter, $_POST['what']);
        }
        $i++;
      }
    } // end 'csv' case
  } // end building the dump

  // Free memory if is necessary
  if ( !$single ) // == false
  {
    $auxConn->close();
    unset($auxConn);
  }

  ////////////////////////////////////////////////////////////////////
  // "Displays" the dump...
  ////////////////////////////////////////////////////////////////////
  echo DLIB_htmlFormat($dumpBuffer, $_POST['as_file']);

  ////////////////////////////////////////////////////////////////////
  // Close the html tags and add the footers in dump is displayed on screen
  ////////////////////////////////////////////////////////////////////
  if (empty($_POST['as_file']))
  {
    echo "  </pre>\n";
    echo "</div>\n";
    echo '<p><a href="../admin/dump_view_form.php">';
    echo _("Back return");
    echo "</a></p>\n";

    include_once('../shared/footer.php');
  } // end if
?>
