<?php
/**
 * home.php
 *
 * Navigation links to the Home tab
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: home.php,v 1.21 2013/01/13 16:27:44 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../layout/component.php");

  $array = null;
  $array[] = HTML::link(_("Summary"), '../home/index.php', null,
    $nav == 'summary' ? array('class' => 'selected') : null
  );

  $array[] = HTML::link(_("License"), '../home/license.php', null,
    $nav == 'license' ? array('class' => 'selected') : null
  );

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    $sessLogin = isset($_SESSION['auth']['login_session']) ? $_SESSION['auth']['login_session'] : '';
    if ( !empty($sessLogin) && !isset($_SESSION['auth']['invalid_token']) )
    {
      $array[] = HTML::link(_("Logout"), '../auth/logout.php');
    }
    else
    {
      $array[] = HTML::link(_("Log in"), '../auth/login_form.php', null,
        $nav == 'login' ? array('class' => 'selected') : null
      ); // @fixme login
    }
  }

  /*$array[] = HTML::link(_("Help"), '../doc/index.php',
    array(
      'tab' => $tab,
      'nav' => $nav
    ),
    array(
      'title' => _("Opens a new window"),
      'class' => 'popup'
    )
  );*/

  echo navigation($array);
  unset($array);
?>
