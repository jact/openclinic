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
 * @version   CVS: $Id: home.php,v 1.16 2006/10/14 11:16:28 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../layout/component.php");
  echo authInfo();

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
