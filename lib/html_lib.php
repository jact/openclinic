<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: html_lib.php,v 1.5 2004/08/01 08:52:34 jact Exp $
 */

/**
 * html_lib.php
 ********************************************************************
 * Set of html tags functions
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
 *  string htmlTable(array &$head, array &$body, array $foot = null, $options = null, string $caption = "")
 *  void showTable(array &$head, array &$body, array $foot = null, $options = null, string $caption = "")
 *  string htmlMessage(string $text, int $type = OPEN_MSG_WARNING)
 *  void showMessage(string $text, int $type = OPEN_MSG_WARNING)
 */

/**
 * string htmlTable(array &$head, array &$body, array $foot = null, $options = null, string $caption = "")
 ********************************************************************
 * Returns html table
 * Options example:
 *   $options = array(
 *     'align' => 'center', // table align
 *     'shaded' => false, // even odd difference style
 *     'tfoot' => array('align' => 'right'), // tfoot align
 *     8 => array('align' => 'center', 'nowrap' => 1), // col number of tbody align (starts in zero)
 *     9 => array('align' => 'right'),
 *    'r1' => array('colspan' => 2), // row number (starts in zero)
 *   );
 ********************************************************************
 * @param array &$head headers of table columns
 * @param array &$body tabular data
 * @param array $foot (optional) table footer
 * @param array $options (optional) options of table and columns
 * @param string $caption (optional)
 * @return string html table
 * @access public
 */
function htmlTable(&$head, &$body, $foot = null, $options = null, $caption = "")
{
  $html = "";
  if (count($head) == 0 && count($body) == 0)
  {
    return $html; // no data, no table
  }

  if ((isset($options['align']) && $options['align'] == "center"))
  {
    $html .= '<div class="center">' . "\n";
  }
  $html .= "<table>\n";

  if ( !empty($caption) )
  {
    $html .= '<caption>' . trim($caption) . "</caption>\n";
  }

  if (count($head) > 0)
  {
    $html .= "<thead>\n";
    $html .= "<tr>\n";
    foreach ($head as $key => $value)
    {
      $html .= '<th';
      if (gettype($value) == "array")
      {
        foreach ($value as $k => $v)
        {
          $html .= ' ' . $k . '="' . $v . '"';
        }
      }
      $html .= '>';
      $html .= (gettype($value) == "array") ? $key : $value;
      $html .= "</th>\n";
    }
    $html .= "</tr>\n";
    $html .= "</thead>\n";
  }

  if (count($body) > 0)
  {
    $rowClass = "odd";
    $html .= "<tbody>\n";
    $j = 0;
    foreach ($body as $row)
    {
      if ( !isset($options['shaded']) || (isset($options['shaded']) && $options['shaded']))
      {
        $html .= '<tr class="' . $rowClass . '">' . "\n";
      }
      else
      {
        $html .= "<tr>\n";
      }

      $i = 0;
      foreach ($row as $data)
      {
        $html .= '<td';

        if (isset($options['r' . $j]['colspan']) && $options['r' . $j]['colspan'] > 0)
        {
          $html .= ' colspan="' . $options['r' . $j]['colspan'] . '"';
        }

        $class = array();
        if (isset($options[$i]['align']) && $options[$i]['align'] == 'center')
        {
          $class[] = "center";
        }
        elseif (isset($options[$i]['align']) && $options[$i]['align'] == 'right')
        {
          $class[] = "right";
        }

        if (isset($options[$i]['nowrap']) && $options[$i]['nowrap'])
        {
          $class[] = "noWrap";
        }

        if (count($class) > 0)
        {
          $html .= ' class="' . implode(" ", $class) . '"';
        }

        $html .= '>';
        $html .= $data;
        $html .= "</td>\n";
        $i++;
      }
      $html .= "</tr>\n";
      $j++;
      // swap row color
      $rowClass = ($rowClass == "odd") ? "even" : "odd";
    }
    $html .= "</tbody>\n";
  }

  if (count($foot) > 0)
  {
    $html .= "<tfoot>\n";
    foreach ($foot as $row)
    {
      $html .= "<tr>\n";
      $html .= '<td';
      if (count($body[0]) > 1)
      {
        $html .= ' colspan="' . count($body[0]) . '"';
      }
      if (isset($options['tfoot']['align']) && $options['tfoot']['align'] == 'left')
      {
        $html .= ' class="left"';
      }
      elseif (isset($options['tfoot']['align']) && $options['tfoot']['align'] == 'right')
      {
        $html .= ' class="right"';
      }
      else
      {
        $html .= ' class="center"';
      }
      $html .= '>';
      $html .= $row;
      $html .= "</td>\n";
      $html .= "</tr>\n";
    }
    $html .= "</tfoot>\n";
  }

  $html .= "</table>\n";
  if ((isset($options['align']) && $options['align'] == "center"))
  {
    $html .= "</div>\n";
  }

  unset($head);
  unset($body);

  return $html;
}

/**
 * void showTable(array &$head, array &$body, array $foot = null, $options = null, string $caption = "")
 ********************************************************************
 * Draws html table
 ********************************************************************
 * @param array &$head headers of table columns
 * @param array &$body tabular data
 * @param array $foot (optional) table footer
 * @param array $options (optional) options of table and columns
 * @param string $caption (optional)
 * @return void
 * @access public
 */
function showTable(&$head, &$body, $foot = null, $options = null, $caption = "")
{
  echo htmlTable($head, $body, $foot, $options, $caption);
}

/**
 * string htmlMessage(string $text, int $type = OPEN_MSG_WARNING)
 ********************************************************************
 * Returns an html paragraph with a message
 ********************************************************************
 * @param string $text message
 * @param int $type (optional) possible values: OPEN_MSG_ERROR, OPEN_MSG_WARNING (default), OPEN_MSG_INFO
 * @return string html message
 * @access public
 */
function htmlMessage($text, $type = OPEN_MSG_WARNING)
{
  if (empty($text))
  {
    return; // no message
  }

  switch ($type)
  {
    case OPEN_MSG_ERROR:
      $class = "error";
      break;

    case OPEN_MSG_INFO:
      $class = "message";
      break;

    default:
      $class = "advice";
      break;
  }

  $html = '<p class="' . $class . '">';
  $html .= $text;
  $html .= "</p>\n";

  return $html;
}

/**
 * void showMessage(string $text, int $type = OPEN_MSG_WARNING)
 ********************************************************************
 * Draws an html paragraph with a message
 ********************************************************************
 * @param string $text message
 * @param int $type (optional) possible values: OPEN_MSG_ERROR, OPEN_MSG_WARNING (default), OPEN_MSG_INFO
 * @return void
 * @access public
 */
function showMessage($text, $type = OPEN_MSG_WARNING)
{
  echo htmlMessage($text, $type);
}
?>