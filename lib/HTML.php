<?php
/**
 * HTML.php
 *
 * Contains the class HTML
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: HTML.php,v 1.18 2008/03/23 11:53:44 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @todo rename file to Html.php
 */

if ( !defined("PHP_EOL") ) // PHP >= 4.3.10
{
  define("PHP_EOL", "\n");
}

/**
 * Messages constants
 */
define("OPEN_MSG_DEBUG",   0);
define("OPEN_MSG_HINT",    1);
define("OPEN_MSG_INFO",    2);
define("OPEN_MSG_WARNING", 3);
define("OPEN_MSG_ERROR",   4);

/**
 * Script path
 */
define("OPEN_SCRIPT_PATH", '../js/');

/**
 * HTML set of html tags functions
 *
 * Methods:
 *  string xmlEntities(string $text, int $quoteStyle = ENT_QUOTES)
 *  string start(string $tag, array $attribs = null, bool $closed = false)
 *  string end(string $tag)
 *  string tag(string $tag, string $text, array $attribs = null)
 *  string table(array &$head, array &$body, array $foot = null, array $options = null, string $caption = "")
 *  string message(string $text, int $type = OPEN_MSG_WARNING, bool $block = true)
 *  string breadcrumb(array &$links, string $class = "")
 *  string link(string $text, string $url, array $arg = null, array $attribs = null)
 *  string section(int $level, string $text, array $attribs = null)
 *  string para(string $text, array $attribs = null)
 *  string rule(array $attribs = null)
 *  string itemList(array &$items, array $attribs = null, bool $ordered = false)
 *  string image(string $src, string $alt, array $attribs = null)
 *  string insertScript(string $name)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class HTML
{
  /**
   * string xmlEntities(string $text, int $quoteStyle = ENT_QUOTES)
   *
   * Encode only the entities of a string not already encoded
   * From the PHP Manual user notes: tmp1000 at fastmail dot deleteme dot fm (23-Oct-2004 06:07)
   *
   * @param string $text
   * @param int $quoteStyle (optional)
   * @return string
   * @access public
   * @static
   * @since 0.8
   */
  function xmlEntities($text, $quoteStyle = ENT_QUOTES)
  {
    static $_trans;
    if ( !isset($_trans) )
    {
      $_trans = get_html_translation_table(HTML_ENTITIES, $quoteStyle);
      foreach ($_trans as $_key => $_value)
      {
        $_trans[$_key] = '&#' . ord($_key) . ';';
      }
      // don't translate the '&' in case it is part of &xxx;
      $_trans[chr(38)] = '&';
    }

    // after the initial translation, _do_ map standalone '&' into '&amp;' Duane (09-Jan-2005 01:34)
    return preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[x0-9a-f]{2,6};)/", "&amp;", strtr($text, $_trans));
    //return $text; // debug
  }

  /**
   * string start(string $tag, array $attribs = null, bool $closed = false)
   *
   * Returns an HTML start tag
   *
   * @param string $tag HTML tag
   * @param array $attribs (optional)
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
   * @static
   * @since 0.8
   */
  function start($tag, $attribs = null, $closed = false)
  {
    $_html = '<' . $tag;
    if (is_array($attribs))
    {
      foreach ($attribs as $_key => $_value)
      {
        if ($_key == 'error') // Form::*
        {
          continue;
        }

        $_html .= ' ' . $_key . '="' . HTML::xmlEntities(($_value === true) ? $_key : $_value) . '"';
      }
    }
    $_html .= ($closed ? ' />' . PHP_EOL : '>');

    return $_html;
  }

  /**
   * string end(string $tag)
   *
   * Returns an HTML end tag
   *
   * @param string $tag HTML tag
   * @return string HTML end tag
   * @access public
   * @static
   * @since 0.8
   */
  function end($tag)
  {
    $_html = '</' . $tag . '>' . PHP_EOL;

    return $_html;
  }

  /**
   * string tag(string $tag, string $text, array $attribs = null)
   *
   * Returns an HTML tag with text content
   *
   * @param string $tag HTML tag
   * @param string $text
   * @param array $attribs (optional)
   * @return string HTML tag with text content
   * @access public
   * @static
   * @since 0.8
   */
  function tag($tag, $text, $attribs = null)
  {
    $_rawText = strip_tags($text);
    if ($_rawText == $text)
    {
      $text = HTML::xmlEntities($text);
    }

    $_html = HTML::start($tag, isset($attribs) ? $attribs : null);
    $_html .= $text;
    $_html .= '</' . $tag . '>'; //HTML::end($tag);

    return $_html;
  }

  /**
   * string table(array &$head, array &$body, array $foot = null, array $options = null, string $caption = "")
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
   * @static
   */
  function table(&$head, &$body, $foot = null, $options = null, $caption = "")
  {
    $_html = '';
    if (count($head) == 0 && count($body) == 0)
    {
      return $_html; // no data, no table
    }

    if ((isset($options['align']) && $options['align'] == "center"))
    {
      $_html .= HTML::start('div', array('class' => 'center')) . PHP_EOL;
    }
    $_html .= HTML::start('table') . PHP_EOL;

    if ( !empty($caption) )
    {
      $_html .= HTML::tag('caption', $caption) . PHP_EOL;
    }

    if (count($head) > 0)
    {
      $_html .= HTML::start('thead') . PHP_EOL;
      $_html .= HTML::start('tr') . PHP_EOL;
      foreach ($head as $_key => $_value)
      {
        $_html .= HTML::tag('th',
          gettype($_value) == "array" ? $_key : $_value,
          gettype($_value) == "array" ? $_value : null
        ) . PHP_EOL;
      }
      $_html .= HTML::end('tr');
      $_html .= HTML::end('thead');
    }

    $_maxCol = 1;
    foreach ($body as $_row)
    {
      if (count($_row) > $_maxCol)
      {
        $_maxCol = count($_row);
      }
    }

    if (count($foot) > 0)
    {
      $_html .= HTML::start('tfoot') . PHP_EOL;
      foreach ($foot as $_row)
      {
        $_html .= HTML::start('tr') . PHP_EOL;

        $_cellOptions = null;
        if ($_maxCol > 1)
        {
          $_cellOptions['colspan'] = $_maxCol;
        }
        if (isset($options['tfoot']['align']) && $options['tfoot']['align'] == 'left')
        {
          $_cellOptions['class'] = 'left';
        }
        elseif (isset($options['tfoot']['align']) && $options['tfoot']['align'] == 'right')
        {
          $_cellOptions['class'] = 'right';
        }
        else
        {
          $_cellOptions['class'] = 'center';
        }
        $_html .= HTML::tag('td', $_row, $_cellOptions);

        $_html .= HTML::end('tr');
      }
      $_html .= HTML::end('tfoot');
    }

    if (count($body) > 0)
    {
      $_rowClass = "odd";
      $_html .= HTML::start('tbody') . PHP_EOL;
      $_numRow = 0;
      foreach ($body as $_row)
      {
        $_cellOptions = null;
        if ( !isset($options['shaded']) || (isset($options['shaded']) && $options['shaded']))
        {
          $_cellOptions['class'] = $_rowClass;
        }
        $_html .= HTML::start('tr', $_cellOptions);

        $_numCol = 0;
        foreach ($_row as $_data)
        {
          $_cellOptions = null;

          if (isset($options['r' . $_numRow]['colspan']) && $options['r' . $_numRow]['colspan'] > 0)
          {
            $_cellOptions['colspan'] = $options['r' . $_numRow]['colspan'];
          }

          $_class = array();
          if (isset($options[$_numCol]['align']) && $options[$_numCol]['align'] == 'center')
          {
            $_class[] = "center";
          }
          elseif (isset($options[$_numCol]['align']) && $options[$_numCol]['align'] == 'right')
          {
            $_class[] = "right";
          }

          if (isset($options[$_numCol]['nowrap']) && $options[$_numCol]['nowrap'])
          {
            $_class[] = "nowrap";
          }

          if (count($_class) > 0)
          {
            $_cellOptions['class'] = implode(" ", $_class);
          }

          $_html .= HTML::tag('td', $_data, $_cellOptions);
          $_numCol++;
        }
        $_html .= HTML::end('tr');
        $_numRow++;

        // swap row color
        $_rowClass = ($_rowClass == "odd") ? "even" : "odd";
      }
      $_html .= HTML::end('tbody');
    }

    $_html .= HTML::end('table');
    if ((isset($options['align']) && $options['align'] == "center"))
    {
      $_html .= HTML::end('div');
    }

    unset($head);
    unset($body);

    return $_html;
  }

  /**
   * string message(string $text, int $type = OPEN_MSG_WARNING, bool $block = true)
   *
   * Returns an html paragraph with a message
   *
   * @param string $text message
   * @param int $type (optional) possible values: OPEN_MSG_ERROR, OPEN_MSG_WARNING (default), OPEN_MSG_INFO
   * @param bool $block (optional) if false, inline tag (span), block tag otherwise (p)
   * @return string html message
   * @access public
   * @static
   */
  function message($text, $type = OPEN_MSG_WARNING, $block = true)
  {
    static $_list = array(
      OPEN_MSG_DEBUG   => 'debug',
      OPEN_MSG_HINT    => 'hint',
      OPEN_MSG_INFO    => 'info',
      OPEN_MSG_WARNING => 'warning',
      OPEN_MSG_ERROR   => 'error'
    );

    if (empty($text))
    {
      return; // no message
    }

    $_class = (isset($_list[$type]) ? $_list[$type] : 'warning');
    $_html = HTML::tag($block ? 'p' : 'span', $text, array('class' => $_class)) . PHP_EOL;

    return $_html;
  }

  /**
   * string breadcrumb(array &$links, string $class = "")
   *
   * Returns a breadcrumb and a title page.
   *
   * @param array (associative - strings) $links texts and links to show in header
   * @param string $class (optional) to put a background-image
   * @return string bread crumb and title page
   * @access public
   * @static
   * @see HTML::link
   * @see HTML::section
   * @since 0.8
   */
  function breadcrumb(&$links, $class = "")
  {
    if ( !count($links) )
    {
      return;
    }

    $_html = HTML::start('p', array('id' => 'breadcrumb'));

    $_keys = array_keys($links);
    $_title = array_pop($_keys);
    array_pop($links);
    foreach ($links as $_key => $_value)
    {
      $_html .= ($_value) ? HTML::link($_key, $_value) : $_key;
      $_html .= ' &raquo; ';
    }

    $_html .= HTML::end('p');

    $_html .= HTML::section(1, $_title, !empty($class) ? array('class' => $class) : null);

    unset($links);

    return $_html;
  }

  /**
   * string link(string $text, string $url, array $arg = null, array $attribs = null)
   *
   * Returns an HTML anchor link.
   *
   * @param string $text
   * @param string $url
   * @param array $arg (optional) arguments of $url
   * @param array $attribs (optional)
   * @return string HTML anchor link
   * @access public
   * @static
   * @since 0.8
   */
  function link($text, $url, $arg = null, $attribs = null)
  {
    $_query = '';
    if (is_array($arg))
    {
      foreach ($arg as $_key => $_value)
      {
        $_query[] = urlencode($_key) . '=' . urlencode($_value);
      }
      $_query = '?' . implode('&', $_query);
    }
    $attribs['href'] = $url . $_query;

    $_html = HTML::tag('a', $text, $attribs);

    return $_html;
  }

  /**
   * string section(int $level, string $text, array $attribs = null)
   *
   * Returns an HTML section
   *
   * @param int $level (1..6)
   * @param string $text
   * @param array $attribs (optional)
   * @return string HTML section
   * @access public
   * @static
   * @since 0.8
   */
  function section($level, $text, $attribs = null)
  {
    $level = ($level > 0 && $level < 7) ? intval($level) : 1;
    $_html = HTML::tag('h' . $level, $text, isset($attribs) ? $attribs : null) . PHP_EOL;

    return $_html;
  }

  /**
   * string para(string $text, array $attribs = null)
   *
   * Returns an HTML paragraph
   *
   * @param string $text
   * @param array $attribs (optional)
   * @return string HTML paragraph
   * @access public
   * @static
   * @since 0.8
   */
  function para($text, $attribs = null)
  {
    $_html = HTML::tag('p', $text, isset($attribs) ? $attribs : null) . PHP_EOL;

    return $_html;
  }

  /**
   * string rule(array $attribs = null)
   *
   * Returns an HTML horizontal rule
   *
   * @param array $attribs (optional)
   * @return string HTML horizontal rule
   * @access public
   * @static
   * @since 0.8
   */
  function rule($attribs = null)
  {
    return HTML::start('hr', isset($attribs) ? $attribs : null, true);
  }

  /**
   * string itemList(array &$items, array $attribs = null, bool $ordered = false)
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
   * @param array $attribs (optional)
   * @param bool $ordered (optional) ordered list or not
   * @return string HTML ordered or unordered list
   * @access public
   * @static
   * @since 0.8
   * @todo nested lists
   */
  function itemList(&$items, $attribs = null, $ordered = false)
  {
    if ( !is_array($items) )
    {
      return;
    }

    $_tag = ($ordered ? 'ol' : 'ul');
    $_html = HTML::start($_tag, isset($attribs) ? $attribs : null)/* . PHP_EOL*/;
    foreach ($items as $_item)
    {
      $_content = '';
      $_options = null;
      if (is_array($_item))
      {
        $_content = $_item[0];
        $_options = $_item[1];
      }
      else
      {
        $_content = $_item;
      }

      $_html .= HTML::tag('li', $_content, $_options)/* . PHP_EOL*/;
    }
    $_html .= HTML::end($_tag);

    return $_html;
  }

  /**
   * string image(string $src, string $alt, array $attribs = null)
   *
   * Returns img html tag
   *
   * @param string $src image filename
   * @param string $alt alternative text
   * @param array $attribs (optional) JavaScript event handlers, class attribute, etc
   *  example:
   *    $attribs = array(
   *      'title' => 'title text',
   *      'height' => 31, // px
   *      'width' => 88, // px
   *      'onclick' => '...'
   *    );
   * @return string img html tag
   * @access public
   * @static
   */
  function image($src, $alt, $attribs = null)
  {
    $attribs['src']   = $src;
    $attribs['alt']   = $alt;
    $attribs['title'] = isset($attribs['title']) ? $attribs['title'] : $alt;

    $_html = HTML::start('img', $attribs, true);

    return $_html;
  }

  /**
   * string insertScript(string $name)
   *
   * Returns an HTML script tag (if is not yet included)
   *
   * @param string $name (only filename, without path)
   * @return string HTML
   * @access public
   * @static
   * @see OPEN_SCRIPT_PATH
   * @since 0.8
   */
  function insertScript($name)
  {
    static $_list = array();
    $_html = '';

    if ( !in_array($name, $_list) && is_file(OPEN_SCRIPT_PATH . $name))
    {
      $_html = HTML::start('script', array('src' => OPEN_SCRIPT_PATH . $name, 'type' => 'text/javascript'));
      $_html .= HTML::end('script');
      $_list[] = $name;
    }

    return $_html;
  }
} // end class
?>
