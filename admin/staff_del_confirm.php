<?php
/**
 * staff_del_confirm.php
 *
 * Confirmation screen of a staff member deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: staff_del_confirm.php,v 1.22 2007/12/01 12:04:41 jact Exp $
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
  if (count($_GET) == 0 || !is_numeric($_GET["id_member"])
    || empty($_GET["surname1"]) || empty($_GET["surname2"]) || empty($_GET["first_name"]))
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
  $idMember = intval($_GET["id_member"]);
  $surname1 = Check::safeText($_GET["surname1"]);
  $surname2 = Check::safeText($_GET["surname2"]);
  $firstName = Check::safeText($_GET["first_name"]);

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
  HTML::breadCrumb($links, "icon icon_staff");
  unset($links);

  /**
   * Form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../admin/staff_del.php'));

  $tbody = array();

  $tbody[] = Msg::strWarning(sprintf(_("Are you sure you want to delete staff member, %s %s %s?"), $firstName, $surname1, $surname2));

  $tbody[] = Form::strHidden("id_member", $idMember);

  $tfoot = array(
    Form::strButton("delete", _("Delete"))
    . Form::generateToken()
  );

  $options = array('class' => 'center');

  Form::fieldset($title, $tbody, $tfoot, $options);

  HTML::end('form');

  Msg::hint('* ' . _("Note: The del function will delete the related user too (if exists)."));

  HTML::para(HTML::strLink(_("Return"), $returnLocation));

  require_once("../layout/footer.php");
?>
