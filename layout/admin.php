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
 * @version   CVS: $Id: admin.php,v 1.15 2006/10/14 11:16:14 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../layout/component.php");
  echo authInfo();

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

  $array = null;
  foreach ($linkList as $key => $value)
  {
    if ($nav == $key)
    {
      $array[] = array($value[0], array('class' => 'selected'));
    }
    else
    {
      $array[] = HTML::strLink($value[0], $value[1]);
    }
  }
  unset($linkList);

  $array[] = HTML::strLink(_("Help"), '../doc/index.php',
    array(
      'tab' => $tab,
      'nav' => $nav
    ),
    array(
      'title' => _("Opens a new window"),
      'onclick' => "return popSecondary('../doc/index.php?tab=" . $tab . '&nav=' . $nav . "')"
    )
  );

  HTML::itemList($array, array('class' => 'linkList'));
?>
