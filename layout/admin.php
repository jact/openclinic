<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: admin.php,v 1.8 2005/07/18 19:15:05 jact Exp $
 */

/**
 * admin.php
 *
 * Navbar to the Admin tab
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['PATH_TRANSLATED']))
  {
    header("Location: ../index.php");
    exit();
  }

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    echo '<p class="sideBarLogin">';
    echo '<a href="../shared/logout.php"><img src="../images/logout.png" width="96" height="22" alt="logout" title="logout" /></a>';
    echo '<br />';
    echo '[ <a href="../admin/user_edit_form.php?key=' . $_SESSION["userId"] . '&amp;reset=Y&amp;all=Y" title="' . _("manage your user account") . '">' . $_SESSION["loginSession"] . "</a> ]\n";
    echo "</p>\n";
    echo "<hr />\n";
  }

  $linkList = array(
    "summary" => array(_("Summary"), "../admin/index.php"),
    "settings" => array(_("Config settings"), "../admin/setting_edit_form.php?reset=Y"),
    "themes" => array(_("Themes"), "../admin/theme_list.php"),
    "staff" => array(_("Staff Members"), "../admin/staff_list.php"),
    "users" => array(_("Users"), "../admin/user_list.php"),
    //"profiles" => array(_("Profiles"), "../admin/profile_list.php"), // for better chance
    "dump" => array(_("Dumps"), "../admin/dump_view_form.php"),
    "logs" => array(_("Log Statistics"), "../admin/log_stats.php")
  );

  echo '<ul class="linkList">';

  foreach ($linkList as $key => $value)
  {
    echo '<li' . (($nav == $key) ? ' class="selected">' . $value[0] : '><a href="' . $value[1] . '">' . $value[0] . '</a>') . "</li>\n";
  }
  unset($linkList);
?>

  <li><a href="../doc/index.php?tab=<?php echo $tab; ?>&amp;nav=<?php echo $nav; ?>" title="<?php echo _("Opens a new window"); ?>" onclick="return popSecondary('../doc/index.php?tab=<?php echo $tab; ?>&amp;nav=<?php echo $nav; ?>')" onkeypress="return popSecondary('../doc/index.php?tab=<?php echo $tab; ?>&amp;nav=<?php echo $nav; ?>')"><?php echo _("Help"); ?></a></li>
</ul><!-- End .linkList -->
