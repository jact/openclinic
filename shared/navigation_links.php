<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: navigation_links.php,v 1.5 2004/10/17 14:57:35 jact Exp $
 */

/**
 * navigation_links.php
 ********************************************************************
 * Contains showNavLinks function
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * @todo change name (bread crumb)
 * @todo include htmlNavLinks() function (or htmlBreadCrumb)
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['PATH_TRANSLATED'])
  {
    header("Location: ../index.php");
    exit();
  }

  /**
   * void showNavLinks(array &$links, string $image = "")
   ********************************************************************
   * Draws a header with navigation links.
   ********************************************************************
   * @param array (associative - strings) $links texts and links to show in header
   * @param string $image (optional) logo to show in header
   * @return void
   * @access public
   * @since 0.4
   * @todo <hn class="something">title</hn> without img tag (all in css, background-image)
   */
  function showNavLinks(&$links, $image = "")
  {
    $rows = sizeof($links);
    if ($rows == 0)
    {
      return;
    }
    $level = (($rows > 5) ? 5 : $rows);

    echo '<h' . $level . '>';

    if ( !empty($image) )
    {
      echo '<img src="../images/' . $image . '" width="40" height="40" alt="" /> ';
    }

    $i = 1;
    foreach ($links as $key => $value)
    {
      echo ($value) ? '<a href="' . $value . '">' . $key . '</a>' : $key;
      if ($i < $rows)
      {
        echo ' &raquo; ';
      }
      $i++;
    }

    echo '</h' . $level . ">\n";

    unset($links);
  }
?>
