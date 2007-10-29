<?php
/**
 * home.php
 *
 * Navbar to the Home tab
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: home.php,v 1.18 2007/10/29 20:06:11 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

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
      'class' => 'popup'
    )
  );

  HTML::itemList($array, array('class' => 'linkList'));
?>
