<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: search_lib.php,v 1.8 2004/10/17 14:56:40 jact Exp $
 */

/**
 * search_lib.php
 ********************************************************************
 * Used functions in search's pages
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['PATH_TRANSLATED'])
  {
    header("Location: ../index.php");
    exit();
  }

/**
 * Functions:
 *  array explodeQuoted(string $str)
 *  void showResultPages(int $currentPage, int $pageCount)
 */

/**
 * array explodeQuoted(string $str)
 ********************************************************************
 * Explodes a quoted string into words
 ********************************************************************
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
 * void showResultPages(int $currentPage, int $pageCount)
 ********************************************************************
 * Creates the pagination string in result sets
 ********************************************************************
 * @param int $currentPage
 * @param int $pageCount total pages
 * @return void
 * @access public
 * @todo optimize code with constants
 * @todo make htmlResultPages() function
 */
function showResultPages($currentPage, $pageCount)
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
                      ? '<strong>' . $i . "</strong>\n"
                      : '<a href="#" onclick="changePage(' . $i . ');">' . $i . "</a>\n";
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
                          ? '<strong>' . $i . "</strong>\n"
                          : '<a href="#" onclick="changePage(' . $i . ');">' . $i . "</a>\n";
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
                        ? '<strong>' . $i . "</strong>\n"
                        : '<a href="#" onclick="changePage(' . $i . ');">' . $i . "</a>\n";
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
                      ? '<strong>' . $i . "</strong>\n"
                      : '<a href="#" onclick="changePage(' . $i . ');">' . $i . "</a>\n";
      if ($i < $pageCount)
      {
        $pageString .= ' | ';
      }
    }
  }

  if ($currentPage > 1)
  {
    $pageString = ' <a href="#" onclick="changePage(' . ($currentPage - 1) . ');">&laquo;' . _("prev") . '</a> | ' . $pageString;
  }

  if ($currentPage < $pageCount)
  {
    $pageString .= ' | <a href="#" onclick="changePage(' . ($currentPage + 1) . ');">' . _("next") . '&raquo;</a>';
  }

  $pageString = '<p>' . _("Result Pages") . ': ' . $pageString . "</p>\n";

  echo $pageString;
}
?>
