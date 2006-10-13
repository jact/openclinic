<?php
/**
 * staff_del_confirm.php
 *
 * Confirmation screen of a staff member deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: staff_del_confirm.php,v 1.17 2006/10/13 19:49:46 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "staff";
  $returnLocation = "../admin/staff_list.php";

  /**
   * Checking for query string. Go back to $returnLocation if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || empty($_GET["sur1"]) || empty($_GET["sur2"]) || empty($_GET["first"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");

  /**
   * Retrieving get vars
   */
  $idMember = intval($_GET["key"]);
  $surname1 = Check::safeText($_GET["sur1"]);
  $surname2 = Check::safeText($_GET["sur2"]);
  $firstName = Check::safeText($_GET["first"]);

  /**
   * Show page
   */
  $title = _("Delete Staff Member");
  require_once("../layout/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Staff Members") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon staffIcon");
  unset($links);

  /**
   * Form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../admin/staff_del.php'));

  $tbody = array();

  $tbody[] = HTML::strMessage(sprintf(_("Are you sure you want to delete staff member, %s %s %s?"), $firstName, $surname1, $surname2), OPEN_MSG_WARNING, false);

  $tbody[] = Form::strHidden("id_member", $idMember);

  $tfoot = array(
    Form::strButton("delete", _("Delete"))
    . Form::strButton("return", _("Return"), "button", array('onclick' => 'parent.location=\'' . $returnLocation . '\''))
  );

  $options = array('class' => 'center');

  Form::fieldset($title, $tbody, $tfoot, $options);

  HTML::end('form');

  HTML::message('* ' . _("Note: The del function will delete the related user too (if exists)."));

  require_once("../layout/footer.php");
?>
