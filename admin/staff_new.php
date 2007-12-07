<?php
/**
 * staff_new.php
 *
 * Staff member addition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: staff_new.php,v 1.20 2007/12/07 16:50:50 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once("../config/environment.php");
  require_once("../lib/Check.php");

  /**
   * Controlling vars
   */
  $errorLocation = "../admin/staff_new_form.php?type=" . Check::safeText($_GET['type']);
  $returnLocation = "../admin/staff_list.php";

  /**
   * Checking for post vars. Go back to $errorLocation if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: " . $errorLocation);
    exit();
  }

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR);

  /**
   * Validate data
   */
  require_once("../model/Query/Staff.php");
  $staff = new Staff();

  require_once("../admin/staff_validate_post.php");

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  /**
   * Insert new staff member
   */
  $staffQ = new Query_Staff();
  if ($staffQ->existLogin($staff->getLogin()))
  {
    FlashMsg::add(sprintf(_("Login, %s, already exists. The changes have no effect."), $staff->getLogin()),
      OPEN_MSG_WARNING
    );
  }
  else
  {
    $staffQ->insert($staff);
    $info = $staff->getFirstName() . " " . $staff->getSurname1() . " " . $staff->getSurname2();
    FlashMsg::add(sprintf(_("Staff member, %s, has been added."), $info));
  }
  $staffQ->close();
  unset($staffQ);
  unset($staff);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  header("Location: " . $returnLocation);
?>
