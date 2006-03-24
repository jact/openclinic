<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2005 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: home.php,v 1.12 2006/03/24 20:28:53 jact Exp $
 */

/**
 * home.php
 *
 * Navbar to the Home tab
 *
 * @author jact <jachavar@gmail.com>
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
      HTML::link('<img src="../images/logout.png" width="96" height="22" alt="' . _("logout") . '" title="' . _("logout") . '" />', '../shared/logout.php');
      echo '<br />';
      echo '[ ' . HTML::strLink($sessLogin, '../admin/user_edit_form.php',
        array(
          'key' => $_SESSION["userId"],
          'all' => 'Y'
        ),
        array('title' => _("manage your user account"))
      ) . " ]\n";
    }
    else
    {
      HTML::link('<img src="../images/login.png" width="96" height="22" alt="' . _("login") . '" title="' . _("login") . '" />', '../shared/login_form.php', array('ret' => '../home/index.php'));
    }
    echo "</p>\n";
    echo "<hr />\n";
  }

  echo '<ul class="linkList">';

  echo ($nav == "home")
    ? '<li class="selected">' . _("Summary") . '</li>'
    : '<li>' . HTML::strLink(_("Summary"), '../home/index.php') . '</li>';

  echo ($nav == "license")
    ? '<li class="selected">' . _("License") . '</li>'
    : '<li>' . HTML::strLink(_("License"), '../home/license.php') . '</li>';

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
