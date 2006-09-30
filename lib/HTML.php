<?php
/**
 * HTML.php
 *
 * Contains the class HTML
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: HTML.php,v 1.10 2006/09/30 17:38:20 jact Exp $
 * @author    jact <jachavar@gmail.com>
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
 * Methods:
 *  string strStart(string $tag, array $options = null, bool $closed = false)
 *  void start(string $tag, array $options = null, bool $closed = false)
 *  string strEnd(string $tag)
 *  void end(string $tag)
 *  string strTag(string $tag, string $text, array $options = null)
 *  void tag(string $tag, string $text, array $options = null)
 *  string strTable(array &$head, array &$body, array $foot = null, array $options = null, string $caption = "")
 *  void table(array &$head, array &$body, array $foot = null, array $options = null, string $caption = "")
 *  string strMessage(string $text, int $type = OPEN_MSG_WARNING, bool $block = true)
 *  void message(string $text, int $type = OPEN_MSG_WARNING, bool $block = true)
 *  string strBreadCrumb(array &$links, string $class = "")
 *  void breadCrumb(array &$links, string $class = "")
 *  string strLink(string $text, string $url, array $arg = null, array $addendum = null)
 *  void link(string $text, string $url, array $arg = null, array $addendum = null)
 *  string strSection(int $level, string $text, array $addendum = null)
 *  void section(int $level, string $text, array $addendum = null)
 *  string strPara(string $text, array $addendum = null)
 *  void para(string $text, array $addendum = null)
 *  string strRule(array $addendum = null)
 *  void rule(array $addendum = null)
 *  string strItemList(array &$items, array $addendum = null, bool $ordered = false)
 *  void itemList(array &$items, array $addendum = null, bool $ordered = false)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class HTML
{
  /**
   * string strStart(string $tag, array $options = null, bool $closed = false)
   *
   * Returns an HTML start tag
   *
   * @param string $tag HTML tag
   * @param array $options (optional)
   *  example:
   *    $options = array(
   *      'id' => 'address',
   *      'name' => 'address',
   *      'type' => 'text',
   *      'readonly' => true,
   *      'disabled' => true,
   *      'class' => 'required',
   *      'onclick' => '...'
   *    );
   * @param bool $closed (optional) closed or not?
   * @return string HTML start tag
   * @access public
   * @since 0.8
   */
  function strStart($tag, $options = null, $closed = false)
  {
    $html = '<' . $tag;
    if (is_array($options))
    {
      foreach ($options as $key => $value)
      {
        if ($key == 'error') // Form::*
        {
          continue;
        }

        //$html .= ' ' . $key . '="' . htmlspecialchars(($value === true) ? $key : $value) . '"';
        $html .= ' ' . $key . '="' . htmlentities(($value === true) ? $key : $value) . '"';
      }
    }
    $html .= ($closed ? " />\n" : '>');

    return $html;
  }

  /**
   * void start(string $tag, array $options = null, bool $closed = false)
   *
   * Draws an HTML start tag
   *
   * @param string $tag HTML tag
   * @param array $options (optional)
   * @param bool $closed (optional) closed or not?
   * @return void
   * @access public
   * @since 0.8
   */
  function start($tag, $options = null, $closed = false)
  {
    echo HTML::strStart($tag, isset($options) ? $options : null, $closed);
  }

  /**
   * string strEnd(string $tag)
   *
   * Returns an HTML end tag
   *
   * @param string $tag HTML tag
   * @return string HTML end tag
   * @access public
   * @since 0.8
   */
  function strEnd($tag)
  {
    $html = '</' . $tag . ">\n";

    return $html;
  }

  /**
   * void end(string $tag)
   *
   * Draws an HTML end tag
   *
   * @param string $tag HTML tag
   * @return void
   * @access public
   * @since 0.8
   */
  function end($tag)
  {
    echo HTML::strEnd($tag);
  }

  /**
   * string strTag(string $tag, string $text, array $options = null)
   *
   * Returns an HTML tag with text content
   *
   * @param string $tag HTML tag
   * @param string $text
   * @param array $options (optional)
   * @return string HTML tag with text content
   * @access public
   * @since 0.8
   */
  function strTag($tag, $text, $options = null)
  {
    $rawText = strip_tags($text); // @fixme poner en función aparte?
    if ($rawText == $text)
    {
      $text = htmlentities($text); // mirar WordPress para cómo lo hacen allí
    }

    $html = HTML::strStart($tag, isset($options) ? $options : null);
    $html .= $text;
    $html .= '</' . $tag . '>'; //HTML::strEnd($tag);

    return $html;
  }

  /**
   * void tag(string $tag, string $text, array $options = null)
   *
   * Draws an HTML tag with text content
   *
   * @param string $tag HTML tag
   * @param string $text
   * @param array $options (optional)
   * @return void
   * @access public
   * @since 0.8
   */
  function tag($tag, $text, $options = null)
  {
    echo HTML::strTag($tag, $text, isset($options) ? $options : null);
  }

  /**
   * string strTable(array &$head, array &$body, array $foot = null, array $options = null, string $caption = "")
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
      $html .= HTML::strStart('div', array('class' => 'center')) . "\n";
    }
    $html .= HTML::strStart('table') . "\n";

    if ( !empty($caption) )
    {
      $html .= HTML::strTag('caption', $caption) . "\n";
    }

    if (count($head) > 0)
    {
      $html .= HTML::strStart('thead') . "\n";
      $html .= HTML::strStart('tr') . "\n";
      foreach ($head as $key => $value)
      {
        $html .= HTML::strTag('th',
          gettype($value) == "array" ? $key : $value,
          gettype($value) == "array" ? $value : null
        ) . "\n";
      }
      $html .= HTML::strEnd('tr');
      $html .= HTML::strEnd('thead');
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
      $html .= HTML::strStart('tfoot') . "\n";
      foreach ($foot as $row)
      {
        $html .= HTML::strStart('tr') . "\n";

        $cellOptions = null;
        if ($maxCol > 1)
        {
          $cellOptions['colspan'] = $maxCol;
        }
        if (isset($options['tfoot']['align']) && $options['tfoot']['align'] == 'left')
        {
          $cellOptions['class'] = 'left';
        }
        elseif (isset($options['tfoot']['align']) && $options['tfoot']['align'] == 'right')
        {
          $cellOptions['class'] = 'right';
        }
        else
        {
          $cellOptions['class'] = 'center';
        }
        $html .= HTML::strTag('td', $row, $cellOptions);

        $html .= HTML::strEnd('tr');
      }
      $html .= HTML::strEnd('tfoot');
    }

    if (count($body) > 0)
    {
      $rowClass = "odd";
      $html .= HTML::strStart('tbody') . "\n";
      $numRow = 0;
      foreach ($body as $row)
      {
        $cellOptions = null;
        if ( !isset($options['shaded']) || (isset($options['shaded']) && $options['shaded']))
        {
          $cellOptions['class'] = $rowClass;
        }
        $html .= HTML::strStart('tr', $cellOptions);

        $numCol = 0;
        foreach ($row as $data)
        {
          $cellOptions = null;

          if (isset($options['r' . $numRow]['colspan']) && $options['r' . $numRow]['colspan'] > 0)
          {
            $cellOptions['colspan'] = $options['r' . $numRow]['colspan'];
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
            $cellOptions['class'] = implode(" ", $class);
          }

          $html .= HTML::strTag('td', $data, $cellOptions);
          $numCol++;
        }
        $html .= HTML::strEnd('tr');
        $numRow++;
        // swap row color
        $rowClass = ($rowClass == "odd") ? "even" : "odd";
      }
      $html .= HTML::strEnd('tbody');
    }

    $html .= HTML::strEnd('table');
    if ((isset($options['align']) && $options['align'] == "center"))
    {
      $html .= HTML::strEnd('div');
    }

    unset($head);
    unset($body);

    return $html;
  }

  /**
   * void table(array &$head, array &$body, array $foot = null, array $options = null, string $caption = "")
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
    echo HTML::strTable($head, $body,
      isset($foot) ? $foot : null,
      isset($options) ? $options : null,
      isset($caption) ? $caption : null
    );
  }

  /**
   * string strMessage(string $text, int $type = OPEN_MSG_WARNING, bool $block = true)
   *
   * Returns an html paragraph with a message
   *
   * @param string $text message
   * @param int $type (optional) possible values: OPEN_MSG_ERROR, OPEN_MSG_WARNING (default), OPEN_MSG_INFO
   * @param bool $block (optional) if false, inline tag (span), block tag otherwise (p)
   * @return string html message
   * @access public
   */
  function strMessage($text, $type = OPEN_MSG_WARNING, $block = true)
  {
    if (empty($text))
    {
      return; // no message
    }

    $class = "advice";
    switch ($type)
    {
      case OPEN_MSG_ERROR:
        $class = "error";
        break;

      case OPEN_MSG_INFO:
        $class = "message";
        break;
    }

    $html = HTML::strTag($block ? 'p' : 'span', $text, array('class' => $class)) . "\n";

    return $html;
  }

  /**
   * void message(string $text, int $type = OPEN_MSG_WARNING, bool $block = true)
   *
   * Draws an html paragraph with a message
   *
   * @param string $text message
   * @param int $type (optional) possible values: OPEN_MSG_ERROR, OPEN_MSG_WARNING (default), OPEN_MSG_INFO
   * @param bool $block (optional) if false, inline tag (span), block tag otherwise (p)
   * @return void
   * @access public
   */
  function message($text, $type = OPEN_MSG_WARNING, $block = true)
  {
    echo HTML::strMessage($text, isset($type) ? $type : OPEN_MSG_WARNING, isset($block) ? $block : true);
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
   * @see strLink
   * @since 0.8
   */
  function strBreadCrumb(&$links, $class = "")
  {
    if ( !count($links) )
    {
      return;
    }

    $html = HTML::strStart('p', array('id' => 'breadCrumb'));

    $keys = array_keys($links);
    $title = array_pop($keys);
    array_pop($links);
    foreach ($links as $key => $value)
    {
      $html .= ($value) ? HTML::strLink($key, $value) : $key;
      $html .= ' &raquo; ';
    }

    $html .= HTML::strEnd('p');

    $html .= HTML::strSection(1, $title, !empty($class) ? array('class' => $class) : null);

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

  /**
   * string strLink(string $text, string $url, array $arg = null, array $addendum = null)
   *
   * Returns an HTML anchor link.
   *
   * @param string $text
   * @param string $url
   * @param array $arg (optional) arguments of $url
   * @param array $addendum (optional)
   * @return string HTML anchor link
   * @access public
   * @since 0.8
   */
  function strLink($text, $url, $arg = null, $addendum = null)
  {
    $query = "";
    if (is_array($arg))
    {
      $query .= '?';
      foreach ($arg as $key => $value)
      {
        $query .= urlencode($key) . '=' . urlencode($value) . '&';
      }
      $query = rtrim($query, '&'); // remove last '&'
    }
    $addendum['href'] = $url . $query;

    $html = HTML::strTag('a', $text, $addendum);

    return $html;
  }

  /**
   * void link(string $text, string $url, array $arg = null, array $addendum = null)
   *
   * Draws an HTML anchor link.
   *
   * @param string $text
   * @param string $url
   * @param array $arg (optional) arguments of $url
   * @param array $addendum (optional)
   * @return void
   * @access public
   * @since 0.8
   */
  function link($text, $url, $arg = null, $addendum = null)
  {
    echo HTML::strLink($text, $url, is_array($arg) ? $arg : null, isset($addendum) ? $addendum : null);
  }

  /**
   * string strSection(int $level, string $text, array $addendum = null)
   *
   * Returns an HTML section
   *
   * @param int $level (1..6)
   * @param string $text
   * @param array $addendum (optional)
   * @return string HTML section
   * @access public
   * @since 0.8
   */
  function strSection($level, $text, $addendum = null)
  {
    $level = ($level > 0 && $level < 7) ? intval($level) : 1;
    $html = HTML::strTag('h' . $level, $text, isset($addendum) ? $addendum : null) . "\n";

    return $html;
  }

  /**
   * void section(int $level, string $text, array $addendum = null)
   *
   * Draws an HTML section
   *
   * @param int $level (1..6)
   * @param string $text
   * @param array $addendum (optional)
   * @return void
   * @access public
   * @since 0.8
   */
  function section($level, $text, $addendum = null)
  {
    echo HTML::strSection($level, $text, isset($addendum) ? $addendum : null);
  }

  /**
   * string strPara(string $text, array $addendum = null)
   *
   * Returns an HTML paragraph
   *
   * @param string $text
   * @param array $addendum (optional)
   * @return string HTML paragraph
   * @access public
   * @since 0.8
   */
  function strPara($text, $addendum = null)
  {
    $html = HTML::strTag('p', $text, isset($addendum) ? $addendum : null) . "\n";

    return $html;
  }

  /**
   * void para(string $text, array $addendum = null)
   *
   * Draws an HTML paragraph
   *
   * @param string $text
   * @param array $addendum (optional)
   * @return void
   * @access public
   * @since 0.8
   */
  function para($text, $addendum = null)
  {
    echo HTML::strPara($text, isset($addendum) ? $addendum : null);
  }

  /**
   * string strRule(array $addendum = null)
   *
   * Returns an HTML horizontal rule
   *
   * @param array $addendum (optional)
   * @return string HTML horizontal rule
   * @access public
   * @since 0.8
   */
  function strRule($addendum = null)
  {
    return HTML::strStart('hr', isset($addendum) ? $addendum : null, true);
  }

  /**
   * void rule(array $addendum = null)
   *
   * Draws an HTML horizontal rule
   *
   * @param array $addendum (optional)
   * @return void
   * @access public
   * @since 0.8
   */
  function rule($addendum = null)
  {
    echo HTML::strRule(isset($addendum) ? $addendum : null);
  }

  /**
   * string strItemList(array &$items, array $addendum = null, bool $ordered = false)
   *
   * Returns an HTML ordered or unordered list
   *
   * @param array $items
   *  example:
   *    $items = array(
   *      0 => 'item text',
   *      1 => array(
   *        0 => 'item text',
   *        1 => array(
   *          'id' => 'current',
   *          'class' => 'selected'
   *        ),
   *      ),
   *      2 => 'item text',
   *      3 => 'item text'
   *    );
   * @param array $addendum (optional)
   * @param bool $ordered (optional) ordered list or not
   * @return string HTML ordered or unordered list
   * @access public
   * @since 0.8
   */
  function strItemList(&$items, $addendum = null, $ordered = false)
  {
    if ( !is_array($items) )
    {
      return;
    }

    $tag = ($ordered ? 'ol' : 'ul');
    $html = HTML::strStart($tag, isset($addendum) ? $addendum : null) . "\n";
    foreach ($items as $item)
    {
      $content = '';
      $options = null;
      if (is_array($item))
      {
        $content = $item[0];
        $options = $item[1];
      }
      else
      {
        $content = $item;
      }

      $html .= HTML::strTag('li', $content, $options) . "\n";
    }
    $html .= HTML::strEnd($tag);

    return $html;
  }

  /**
   * void itemList(array &$items, array $addendum = null, bool $ordered = false)
   *
   * Draws an HTML ordered or unordered list
   *
   * @param array $items
   * @param array $addendum (optional)
   * @param bool $ordered (optional) ordered list or not
   * @return void
   * @access public
   * @since 0.8
   */
  function itemList(&$items, $addendum = null, $ordered = false)
  {
    echo HTML::strItemList($items, isset($addendum) ? $addendum : null, $ordered);
  }
} // end class
?>
