<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: navigation_links.php,v 1.3 2004/08/09 11:35:55 jact Exp $
 */

/**
 * navigation_links.php
 ********************************************************************
 * Contains showNavLinks function
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * TODO: change name (bread crumb)
 * TODO: include htmlNavLinks() function (or htmlBreadCrumb)
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

  /**
   * void showNavLinks(array &$links, string $image = "")
   ********************************************************************
   * Draws a header with navigation links.
   * TODO: <hn class="something">sfjdfjsf</hn> without img tag (all in css, background-image)
   ********************************************************************
   * @param array (associative - strings) $links texts and links to show in header
   * @param string $image (optional) logo to show in header
   * @return void
   * @access public
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
