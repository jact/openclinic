<?php
/**
 * admin.php
 *
 * Navbar to the Admin tab
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: admin.php,v 1.12 2006/03/27 18:32:34 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    echo '<p class="sideBarLogin">';
    HTML::link('<img src="../images/logout.png" width="96" height="22" alt="' . _("logout") . '" title="' . _("logout") . '" />', '../shared/logout.php');
    echo '<br />';
    echo '[ ' . HTML::strLink($_SESSION["loginSession"], '../admin/user_edit_form.php',
      array(
        'key' => $_SESSION["userId"],
        'all' => 'Y'
      ),
      array('title' => _("manage your user account"))
    ) . " ]\n";
    echo "</p>\n";
    echo "<hr />\n";
  }

  $linkList = array(
    "summary" => array(_("Summary"), "../admin/index.php"),
    "settings" => array(_("Config settings"), "../admin/setting_edit_form.php"),
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
    echo '<li' . (($nav == $key) ? ' class="selected">' . $value[0] : '>' . HTML::strLink($value[0], $value[1])) . "</li>\n";
  }
  unset($linkList);

  echo '<li>';
  HTML::link(_("Help"), '../doc/index.php',
    array(
      'tab' => $tab,
      'nav' => $nav
    ),
    array(
      'title' => _("Opens a new window"),
      'onclick' => "return popSecondary('../doc/index.php?tab=" . $tab . '&amp;nav=' . $nav . "')"
    )
  );
  echo "</li>\n";

  echo "</ul><!-- End .linkList -->\n";
?>
