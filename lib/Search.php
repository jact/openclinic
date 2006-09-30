<?php
/**
 * Search.php
 *
 * Contains the class Search
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Search.php,v 1.7 2006/09/30 17:02:02 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

/**
 * Search set of used functions in search's pages
 *
 * Methods:
 *  array explodeQuoted(string $str)
 *  void pageLinks(int $currentPage, int $pageCount)
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
   * array explodeQuoted(string $str)
   *
   * Explodes a quoted string into words
   *
   * @param string $str string to be exploded
   * @return stringArray
   * @access public
   */
  function explodeQuoted($str)
  {
    if (empty($str))
    {
      $elements[] = "";
      return $elements;
    }

    $words = explode(" ", $str);

    $inQuotes = false;
    foreach ($words as $word)
    {
      if ($inQuotes)
      {
        // add word to the last element
        $elements[sizeof($elements) - 1] .= urlencode(" " . str_replace('"', '', $word));
        if ($word[strlen($word) - 1] == "\"")
        {
          $inQuotes = false;
        }
      }
      else
      {
        // create a new element
        $elements[] = urlencode(str_replace('"', '', $word));
        if ($word[0] == "\"" && $word[strlen($word) - 1] != "\"")
        {
          $inQuotes = true;
        }
      }
    }

    return $elements;
  }

  /**
   * void pageLinks(int $currentPage, int $pageCount)
   *
   * Creates the pagination string in result sets
   *
   * @param int $currentPage
   * @param int $pageCount total pages
   * @return void
   * @see HTML::strLink()
   * @access public
   * @todo optimize code with constants
   * @todo make strPageLinks() function
   */
  function pageLinks($currentPage, $pageCount)
  {
    if ($pageCount == 1)
    {
      return;
    }

    $pageString = '';
    if ($pageCount > 10)
    {
      $initPageMax = ($pageCount > 3) ? 3 : $pageCount;

      for ($i = 1; $i < $initPageMax + 1; $i++)
      {
        $pageString .= ($i == $currentPage)
                        ? HTML::strTag('strong', $i)
                        : HTML::strLink($i, '#', null, array('onclick' => 'changePage(' . $i . ');'));
        if ($i < $initPageMax)
        {
          $pageString .= ' | ';
        }
      }

      if ($pageCount > 3)
      {
        if ($currentPage > 1 && $currentPage < $pageCount)
        {
          $pageString .= ( $currentPage > 5 ) ? ' ... ' : ' | ';

          $initPageMin = ($currentPage > 4) ? $currentPage : 5;
          $initPageMax = ($currentPage < ($pageCount - 4)) ? $currentPage : $pageCount - 4;

          for ($i = ($initPageMin - 1); $i < ($initPageMax + 2); $i++)
          {
            $pageString .= ($i == $currentPage)
                            ? HTML::strTag('strong', $i)
                            : HTML::strLink($i, '#', null, array('onclick' => 'changePage(' . $i . ');'));
            if ($i < ($initPageMax + 1))
            {
              $pageString .= ' | ';
            }
          }

          $pageString .= ($currentPage < ($pageCount - 4)) ? ' ... ' : ' | ';
        }
        else
        {
          $pageString .= ' ... ';
        }

        for ($i = $pageCount - 2; $i < $pageCount + 1; $i++)
        {
          $pageString .= ($i == $currentPage)
                          ? HTML::strTag('strong', $i)
                          : HTML::strLink($i, '#', null, array('onclick' => 'changePage(' . $i . ');'));
          if ($i < $pageCount)
          {
            $pageString .= ' | ';
          }
        }
      }
    }
    else
    {
      for ($i = 1; $i < $pageCount + 1; $i++)
      {
        $pageString .= ($i == $currentPage)
                        ? HTML::strTag('strong', $i)
                        : HTML::strLink($i, '#', null, array('onclick' => 'changePage(' . $i . ');'));
        if ($i < $pageCount)
        {
          $pageString .= ' | ';
        }
      }
    }

    if ($currentPage > 1)
    {
      $pageString = ' ' . HTML::strLink(html_entity_decode('&laquo;') . _("prev"), '#', null, array('onclick' => 'changePage(' . ($currentPage - 1) . ');')) . ' | ' . $pageString;
    }

    if ($currentPage < $pageCount)
    {
      $pageString .= ' | ' . HTML::strLink(_("next") . html_entity_decode('&raquo;'), '#', null, array('onclick' => 'changePage(' . ($currentPage + 1) . ');'));
    }

    HTML::para(_("Result Pages") . ': ' . $pageString, array('class' => 'pageLinks'));
  }

  /**
   * void changePageJS(void)
   *
   * Inserts in a page a function in JavaScript to change page
   *
   * @return void
   * @access public
   */
  function changePageJS()
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
