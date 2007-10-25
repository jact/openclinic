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
 * @version   CVS: $Id: staff_new.php,v 1.16 2007/10/25 21:58:08 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once("../config/environment.php");
  require_once("../lib/Check.php");

  /**
   * Controlling vars
   */
  //$restrictInDemo = true;
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

  require_once("../auth/login_check.php");
  require_once("../model/Staff_Query.php");

  /**
   * Validate data
   */
  $staff = new Staff();

  require_once("../admin/staff_validate_post.php");

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  /**
   * Insert new staff member
   */
  $staffQ = new Staff_Query();
  $staffQ->connect();

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
