<?php
/**
 * home.php
 *
 * Navbar to the Home tab
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: home.php,v 1.15 2006/10/13 20:12:16 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    $sessLogin = isset($_SESSION["loginSession"]) ? $_SESSION["loginSession"] : "";
    if ( !empty($sessLogin) && !isset($_SESSION["invalidToken"]) )
    {
      $sideBarLogin = HTML::strLink(
          HTML::strStart('img',
            array(
              'src' => '../img/logout.png',
              'width' => 96,
              'height' => 22,
              'alt' => _("logout"),
              'title' => _("logout")
            ),
            true
          ),
          '../auth/logout.php'
        )
        . '<br />'
        . '[ '
        . HTML::strLink($sessLogin, '../admin/user_edit_form.php',
          array(
            'key' => $_SESSION["userId"],
            'all' => 'Y'
          ),
          array('title' => _("manage your user account"))
        )
        . ' ]';
    }
    else
    {
      $sideBarLogin = HTML::strLink(
        HTML::strStart('img',
          array(
            'src' => '../img/login.png',
            'width' => 96,
            'height' => 22,
            'alt' => _("login"),
            'title' => _("login")
          ),
          true
        ),
        '../auth/login_form.php',
        array('ret' => '../home/index.php')
      );
    }
    HTML::para($sideBarLogin, array('class' => 'sideBarLogin'));
    unset($sideBarLogin);
    HTML::rule();
  }

  $array = null;
  $array[] = ($nav == "home")
    ? array(_("Summary"), array('class' => 'selected'))
    : HTML::strLink(_("Summary"), '../home/index.php');

  $array[] = ($nav == "license")
    ? array(_("License"), array('class' => 'selected'))
    : HTML::strLink(_("License"), '../home/license.php');

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
