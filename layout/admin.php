<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: admin.php,v 1.2 2004/04/18 14:30:07 jact Exp $
 */

/**
 * admin.php
 ********************************************************************
 * Navbar to the Admin tab
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    echo '<div class="sideBarLogin">';
    echo '<a href="../shared/logout.php"><img src="../images/logout.png" width="96" height="22" alt="logout" title="logout" /></a>';
    echo '<br />';
    echo '[ <a href="../admin/user_edit_form.php?key=' . $_SESSION["userId"] . '&amp;reset=Y&amp;all=Y" title="' . _("manage your user account") . '">' . $_SESSION["loginSession"] . "</a> ]\n";
    echo "</div>\n";
  }

  echo '<div class="linkList">';

  echo ($nav == "summary")
    ? '<span class="selected">' . _("Summary") . '</span>'
    : '<a href="../admin/index.php">' . _("Summary") . '</a>';
  echo "<span class='noPrint'> | </span>\n";

  echo ($nav == "settings")
    ? '<span class="selected">' . _("Config settings") . '</span>'
    : '<a href="../admin/setting_edit_form.php?reset=Y">' . _("Config settings") . '</a>';
  echo "<span class='noPrint'> | </span>\n";

  echo ($nav == "themes")
    ? '<span class="selected">' . _("Themes") . '</span>'
    : '<a href="../admin/theme_list.php">' . _("Themes") . '</a>';
  echo "<span class='noPrint'> | </span>\n";

  echo ($nav == "staff")
    ? '<span class="selected">' . _("Staff Members") . '</span>'
    : '<a href="../admin/staff_list.php">' . _("Staff Members") . '</a>';
  echo "<span class='noPrint'> | </span>\n";

  echo ($nav == "users")
    ? '<span class="selected">' . _("Users") . '</span>'
    : '<a href="../admin/user_list.php">' . _("Users") . '</a>';
  echo "<span class='noPrint'> | </span>\n";

  echo ($nav == "profiles")
    ? '<span class="selected">' . _("Profiles") . '</span>'
    : '<a href="../admin/profile_list.php">' . _("Profiles") . '</a>';
  echo "<span class='noPrint'> | </span>\n";

  echo ($nav == "dump")
    ? '<span class="selected">' . _("Dumps") . '</span>'
    : '<a href="../admin/dump_view_form.php">' . _("Dumps") . '</a>';
  echo "<span class='noPrint'> | </span>\n";

  echo ($nav == "logs")
    ? '<span class="selected">' . _("Log Statistics") . '</span>'
    : '<a href="../admin/log_stats.php">' . _("Log Statistics") . '</a>';
  echo "<span class='noPrint'> | </span>\n";
?>

  <a href="../doc/index.php?tab=<?php echo $tab; ?>&amp;nav=<?php echo $nav; ?>" title="<?php echo _("Opens a new window"); ?>" onclick="return popSecondary('../doc/index.php?tab=<?php echo $tab; ?>&amp;nav=<?php echo $nav; ?>')" onkeypress="return popSecondary('../doc/index.php?tab=<?php echo $tab; ?>&amp;nav=<?php echo $nav; ?>')"><?php echo _("Help"); ?></a>
</div><!-- End .linkList -->
