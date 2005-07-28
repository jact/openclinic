<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: navigation_links.php,v 1.7 2005/07/28 17:53:04 jact Exp $
 */

/**
 * navigation_links.php
 *
 * Contains showNavLinks function
 *
 * Author: jact <jachavar@gmail.com>
 * @todo change name (bread crumb)
 * @todo include htmlNavLinks() function (or htmlBreadCrumb)
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['PATH_TRANSLATED']))
  {
    header("Location: ../index.php");
    exit();
  }

  /**
   * void showNavLinks(array &$links, string $image = "")
   *
   * Draws a header with navigation links.
   *
   * @param array (associative - strings) $links texts and links to show in header
   * @param string $image (optional) logo to show in header
   * @return void
   * @access public
   * @since 0.4
   * @todo <hn class="something">title</hn> without img tag (all in css, background-image)
   * @todo change $image to $class parameter to use background images
   * @todo change name function to showBreadCrumb()
   */
  function showNavLinks(&$links, $image = "")
  {
    $rows = sizeof($links);
    if ($rows == 0)
    {
      return;
    }
    //$level = (($rows > 5) ? 5 : $rows);

    //echo '<h' . $level . '>';
    echo '<p id="breadCrumb">';

    /*if ( !empty($image) )
    {
      echo '<img src="../images/' . $image . '" width="40" height="40" alt="" /> ';
    }*/

    //$i = 1;
    $keys = array_keys($links);
    $title = array_pop($keys);
    array_pop($links);
    foreach ($links as $key => $value)
    {
      echo ($value) ? '<a href="' . $value . '">' . $key . '</a>' : $key;
      //if ($i < $rows)
      //{
        echo ' &raquo; ';
      //}
      //$i++;
    }

    //echo '</h' . $level . ">\n";
    echo "</p>\n";

    echo '<h1' . ( !empty($image) ? ' class="' . $image . '"' : '') . '>' . $title . "</h1>\n";

    unset($links);
  }
?>
