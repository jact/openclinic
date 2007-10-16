<?php
/**
 * staff_edit.php
 *
 * Staff member edition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: staff_edit.php,v 1.13 2007/10/16 20:03:48 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  //$restrictInDemo = true;
  $returnLocation = "../admin/staff_list.php";

  /**
   * Checking for post vars. Go back to $returnLocation if none found.
   */
  if (count($_POST) == 0 || !is_numeric($_POST["id_member"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Staff_Query.php");

  /**
   * Validate data
   */
  $errorLocation = "../admin/staff_edit_form.php?key=" . intval($_POST["id_member"]); // controlling var
  $staff = new Staff();

  $staff->setIdMember($_POST["id_member"]);

  require_once("../admin/staff_validate_post.php");

  /**
   * Update staff member
   */
  $staffQ = new Staff_Query();
  $staffQ->connect();

  if ($staffQ->existLogin($staff->getLogin(), $staff->getIdMember()))
  {
    FlashMsg::add(sprintf(_("Login, %s, already exists. The changes have no effect."), $staff->getLogin()),
      OPEN_MSG_WARNING
    );
  }
  else
  {
    $staffQ->update($staff);
    $info = $staff->getFirstName() . " " . $staff->getSurname1() . " " . $staff->getSurname2();
    FlashMsg::add(sprintf(_("Staff member, %s, has been updated."), $info));
  }
  $staffQ->close();
  unset($staffQ);
  unset($staff);

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  header("Location: " . $returnLocation);
?>
