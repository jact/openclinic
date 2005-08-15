<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: HTML.php,v 1.3 2005/08/15 16:37:03 jact Exp $
 */

/**
 * HTML.php
 *
 * Contains the class HTML
 *
 * Author: jact <jachavar@gmail.com>
 */

/**
 * Messages constants
 */
define("OPEN_MSG_INFO",    1);
define("OPEN_MSG_WARNING", 2);
define("OPEN_MSG_ERROR",   3);

/**
 * HTML set of html tags functions
 *
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 *
 * Methods:
 *  string strTable(array &$head, array &$body, array $foot = null, $options = null, string $caption = "")
 *  void table(array &$head, array &$body, array $foot = null, $options = null, string $caption = "")
 *  string strMessage(string $text, int $type = OPEN_MSG_WARNING)
 *  void message(string $text, int $type = OPEN_MSG_WARNING)
 *  string strBreadCrumb(array &$links, string $class = "")
 *  void breadCrumb(array &$links, string $class = "")
 */
class HTML
{
  /**
   * string strTable(array &$head, array &$body, array $foot = null, $options = null, string $caption = "")
   *
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
   *
   * @param array &$head headers of table columns
   * @param array &$body tabular data
   * @param array $foot (optional) table footer
   * @param array $options (optional) options of table and columns
   * @param string $caption (optional)
   * @return string html table
   * @access public
   */
  function strTable(&$head, &$body, $foot = null, $options = null, $caption = "")
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

    $maxCol = 1;
    foreach ($body as $row)
    {
      if (count($row) > $maxCol)
      {
        $maxCol = count($row);
      }
    }

    if (count($foot) > 0)
    {
      $html .= "<tfoot>\n";
      foreach ($foot as $row)
      {
        $html .= "<tr>\n";
        $html .= '<td';
        if ($maxCol > 1)
        {
          $html .= ' colspan="' . $maxCol . '"';
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

    if (count($body) > 0)
    {
      $rowClass = "odd";
      $html .= "<tbody>\n";
      $numRow = 0;
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

        $numCol = 0;
        foreach ($row as $data)
        {
          $html .= '<td';

          if (isset($options['r' . $numRow]['colspan']) && $options['r' . $numRow]['colspan'] > 0)
          {
            $html .= ' colspan="' . $options['r' . $numRow]['colspan'] . '"';
          }

          $class = array();
          if (isset($options[$numCol]['align']) && $options[$numCol]['align'] == 'center')
          {
            $class[] = "center";
          }
          elseif (isset($options[$numCol]['align']) && $options[$numCol]['align'] == 'right')
          {
            $class[] = "right";
          }

          if (isset($options[$numCol]['nowrap']) && $options[$numCol]['nowrap'])
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
          $numCol++;
        }
        $html .= "</tr>\n";
        $numRow++;
        // swap row color
        $rowClass = ($rowClass == "odd") ? "even" : "odd";
      }
      $html .= "</tbody>\n";
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
   * void table(array &$head, array &$body, array $foot = null, $options = null, string $caption = "")
   *
   * Draws html table
   *
   * @param array &$head headers of table columns
   * @param array &$body tabular data
   * @param array $foot (optional) table footer
   * @param array $options (optional) options of table and columns
   * @param string $caption (optional)
   * @return void
   * @access public
   */
  function table(&$head, &$body, $foot = null, $options = null, $caption = "")
  {
    echo HTML::strTable($head, $body, isset($foot) ? $foot : null, isset($options) ? $options : null, isset($caption) ? $caption : null);
  }

  /**
   * string strMessage(string $text, int $type = OPEN_MSG_WARNING)
   *
   * Returns an html paragraph with a message
   *
   * @param string $text message
   * @param int $type (optional) possible values: OPEN_MSG_ERROR, OPEN_MSG_WARNING (default), OPEN_MSG_INFO
   * @return string html message
   * @access public
   */
  function strMessage($text, $type = OPEN_MSG_WARNING)
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
   * void message(string $text, int $type = OPEN_MSG_WARNING)
   *
   * Draws an html paragraph with a message
   *
   * @param string $text message
   * @param int $type (optional) possible values: OPEN_MSG_ERROR, OPEN_MSG_WARNING (default), OPEN_MSG_INFO
   * @return void
   * @access public
   */
  function message($text, $type = OPEN_MSG_WARNING)
  {
    echo HTML::strMessage($text, isset($type) ? $type : OPEN_MSG_WARNING);
  }

  /**
   * string strBreadCrumb(array &$links, string $class = "")
   *
   * Returns a bread crumb and a title page.
   *
   * @param array (associative - strings) $links texts and links to show in header
   * @param string $class (optional) to put a background-image
   * @return string bread crumb and title page
   * @access public
   * @since 0.8
   */
  function strBreadCrumb(&$links, $class = "")
  {
    $rows = sizeof($links);
    if ($rows == 0)
    {
      return;
    }

    $html = '<p id="breadCrumb">';

    $keys = array_keys($links);
    $title = array_pop($keys);
    array_pop($links);
    foreach ($links as $key => $value)
    {
      $html .= ($value) ? '<a href="' . $value . '">' . $key . '</a>' : $key;
      $html .= ' &raquo; ';
    }

    $html .= "</p>\n";

    $html .= '<h1' . ( !empty($class) ? ' class="' . $class . '"' : '') . '>' . $title . "</h1>\n";

    unset($links);

    return $html;
  }

  /**
   * void breadCrumb(array &$links, string $class = "")
   *
   * Draws a bread crumb and a title page.
   *
   * @param array (associative - strings) $links texts and links to show in header
   * @param string $class (optional) to put a background-image
   * @return void
   * @access public
   * @since 0.8
   */
  function breadCrumb(&$links, $class = "")
  {
    echo HTML::strBreadCrumb($links, isset($class) ? $class : "");
  }
} // end class
?>