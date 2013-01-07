<?php
/**
 * Search.php
 *
 * Contains the class Search
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Search.php,v 1.16 2013/01/07 18:37:12 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/HTML.php");

/**
 * Search set of used functions in search's pages
 *
 * Methods:
 *  array explodeQuoted(string $text)
 *  string pageLinks(int $currentPage, int $pageCount, string $url = '')
 *  void changePageJS(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class Search
{
  /**
   * array explodeQuoted(string $text)
   *
   * Explodes a quoted string into words
   *
   * @param string $text string to be exploded
   * @return stringArray
   * @access public
   * @static
   */
  public static function explodeQuoted($text)
  {
    if (empty($text))
    {
      $_elements[] = "";

      return $_elements;
    }

    $_words = explode(" ", $text);

    $_inQuotes = false;
    foreach ($_words as $_word)
    {
      if ($_inQuotes)
      {
        // add word to the last element
        $_elements[sizeof($_elements) - 1] .= urlencode(" " . str_replace('"', '', $_word));
        if ($_word[strlen($_word) - 1] == "\"")
        {
          $_inQuotes = false;
        }
      }
      else
      {
        // create a new element
        $_elements[] = urlencode(str_replace('"', '', $_word));
        if ($_word[0] == "\"" && $_word[strlen($_word) - 1] != "\"")
        {
          $_inQuotes = true;
        }
      }
    }

    return $_elements;
  }

  /**
   * string pageLinks(int $currentPage, int $pageCount, string $url = '')
   *
   * Returns the pagination string in result sets
   *
   * @param int $currentPage
   * @param int $pageCount total pages
   * @param string $url (optional) if not empty, links with href, else links with onclick
   * @return string
   * @see HTML::link()
   * @see HTML::tag()
   * @see HTML::para()
   * @access public
   * @static
   * @todo optimize code with constants
   */
  public static function pageLinks($currentPage, $pageCount, $url = '')
  {
    if ($pageCount <= 1)
    {
      return;
    }

    if (empty($url))
    {
      $_pageLink = HTML::link('%s', '#', null, array('onclick' => 'changePage(%d)'));
    }
    else
    {
      $_pageLink = HTML::link('%s', htmlspecialchars(str_replace('%', '%%', $url))
        . ((strpos($url, '?') !== false) ? '&amp;' : '?') . 'page=%d');
    }

    $_pageString = '';
    if ($pageCount > 10)
    {
      $_initPageMax = ($pageCount > 3) ? 3 : $pageCount;

      for ($i = 1; $i < ($_initPageMax + 1); $i++)
      {
        $_pageString .= ($i == $currentPage)
          ? HTML::tag('strong', $i)
          : sprintf($_pageLink, $i, $i);
        if ($i < $_initPageMax)
        {
          $_pageString .= ' | ';
        }
      }

      if ($pageCount > 3)
      {
        if ($currentPage > 1 && $currentPage < $pageCount)
        {
          $_pageString .= ($currentPage > 5) ? ' ... ' : ' | ';

          $_initPageMin = ($currentPage > 4) ? $currentPage : 5;
          $_initPageMax = ($currentPage < ($pageCount - 4)) ? $currentPage : $pageCount - 4;

          for ($i = ($_initPageMin - 1); $i < ($_initPageMax + 2); $i++)
          {
            $_pageString .= ($i == $currentPage)
              ? HTML::tag('strong', $i)
              : sprintf($_pageLink, $i, $i);
            if ($i < ($_initPageMax + 1))
            {
              $_pageString .= ' | ';
            }
          }

          $_pageString .= ($currentPage < ($pageCount - 4)) ? ' ... ' : ' | ';
        }
        else
        {
          $_pageString .= ' ... ';
        }

        for ($i = $pageCount - 2; $i < ($pageCount + 1); $i++)
        {
          $_pageString .= ($i == $currentPage)
            ? HTML::tag('strong', $i)
            : sprintf($_pageLink, $i, $i);
          if ($i < $pageCount)
          {
            $_pageString .= ' | ';
          }
        }
      }
    }
    else
    {
      for ($i = 1; $i < ($pageCount + 1); $i++)
      {
        $_pageString .= ($i == $currentPage)
          ? HTML::tag('strong', $i)
          : sprintf($_pageLink, $i, $i);
        if ($i < $pageCount)
        {
          $_pageString .= ' | ';
        }
      }
    }

    if ($currentPage > 1)
    {
      $_pageString = ' ' . sprintf($_pageLink, $currentPage - 1, '&laquo;' . _("prev")) . ' | ' . $_pageString;
    }

    if ($currentPage < $pageCount)
    {
      $_pageString .= ' | ' . sprintf($_pageLink, $currentPage + 1, _("next") . '&raquo;');
    }

    if ( !empty($url) )
    {
      $_pageString = str_replace('%%', '%', $_pageString);
    }

    return HTML::para(_("Result Pages") . ': ' . $_pageString, array('class' => 'page_links'));
  }

  /**
   * void changePageJS(void)
   *
   * Inserts in a page a function in JavaScript to change page
   *
   * @return void
   * @access public
   * @static
   */
  public static function changePageJS()
  {
    echo <<<EOT
<!-- JavaScript to post back to this page -->
<script type="text/javascript" defer="defer">
<!--/*--><![CDATA[/*<!--*/
function changePage(page)
{
  var f = document.getElementById("changePage");

  if (f == null)
  {
    return;
  }

  f.page.value = page;
  f.submit();

  return false;
}
/*]]>*///-->
</script>
EOT;
  }
} // end class
?>
