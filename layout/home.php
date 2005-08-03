<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: home.php,v 1.10 2005/08/03 17:40:29 jact Exp $
 */

/**
 * home.php
 *
 * Navbar to the Home tab
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    $sessLogin = isset($_SESSION["loginSession"]) ? $_SESSION["loginSession"] : "";
    echo '<p class="sideBarLogin">';
    if ( !empty($sessLogin) && !isset($_SESSION["invalidToken"]) )
    {
      echo '<a href="../shared/logout.php"><img src="../images/logout.png" width="96" height="22" alt="' . _("logout") . '" title="logout" /></a>';
      echo '<br />';
      echo '[ <a href="../admin/user_edit_form.php?key=' . $_SESSION["userId"] . '&amp;reset=Y&amp;all=Y" title="' . _("manage your user account") . '">' . $sessLogin . '</a> ]';
    }
    else
    {
      echo '<a href="../shared/login_form.php?ret=../home/index.php">';
      echo '<img src="../images/login.png" width="96" height="22" alt="login" title="' . _("login") . '" />';
      echo '</a>';
    }
    echo "</p>\n";
    echo "<hr />\n";
  }

  echo '<ul class="linkList">';

  echo ($nav == "home")
    ? '<li class="selected">' . _("Summary") . '</li>'
    : '<li><a href="../home/index.php">' . _("Summary") . '</a></li>';

  echo ($nav == "license")
    ? '<li class="selected">' . _("License") . '</li>'
    : '<li><a href="../home/license.php">' . _("License") . '</a></li>';
?>

  <li><a href="../doc/index.php?tab=<?php echo $tab; ?>&amp;nav=<?php echo $nav; ?>" title="<?php echo _("Opens a new window"); ?>" onclick="return popSecondary('../doc/index.php?tab=<?php echo $tab; ?>&amp;nav=<?php echo $nav; ?>')" onkeypress="return popSecondary('../doc/index.php?tab=<?php echo $tab; ?>&amp;nav=<?php echo $nav; ?>')"><?php echo _("Help"); ?></a></li>
</ul><!-- End .linkList -->
