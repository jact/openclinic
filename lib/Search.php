<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Search.php,v 1.4 2006/03/25 20:03:06 jact Exp $
 */

/**
 * Search.php
 *
 * Contains the class Search
 *
 * @author jact <jachavar@gmail.com>
 */

/**
 * Search set of used functions in search's pages
 *
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 *
 * Methods:
 *  array explodeQuoted(string $str)
 *  void pageLinks(int $currentPage, int $pageCount)
 *  void changePageJS(void)
 */
class Search
{
  /**
   * array explodeQuoted(string $str)
   *
   * Explodes a quoted string into words
   *
   * @param string $str String to be exploded
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
                        ? '<strong>' . $i . '</strong>'
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
                            ? '<strong>' . $i . '</strong>'
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
                          ? '<strong>' . $i . '</strong>'
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
                        ? '<strong>' . $i . '</strong>'
                        : HTML::strLink($i, '#', null, array('onclick' => 'changePage(' . $i . ');'));
        if ($i < $pageCount)
        {
          $pageString .= ' | ';
        }
      }
    }

    if ($currentPage > 1)
    {
      $pageString = ' ' . HTML::strLink('&laquo;' . _("prev"), '#', null, array('onclick' => 'changePage(' . ($currentPage - 1) . ');')) . ' | ' . $pageString;
    }

    if ($currentPage < $pageCount)
    {
      $pageString .= ' | ' . HTML::strLink(_("next") . '&raquo;', '#', null, array('onclick' => 'changePage(' . ($currentPage + 1) . ');'));
    }

    $pageString = '<p class="pageLinks">' . _("Result Pages") . ': ' . $pageString . "</p>\n";

    echo $pageString;
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
