<?php
/**
 * staff_new.php
 *
 * Staff member addition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: staff_new.php,v 1.13 2006/09/30 17:35:09 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once("../shared/read_settings.php");
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

  require_once("../shared/login_check.php");
  require_once("../classes/Staff_Query.php");

  /**
   * Validate data
   */
  $staff = new Staff();

  require_once("../admin/staff_validate_post.php");

  /**
   * Insert new staff member
   */
  $staffQ = new Staff_Query();
  $staffQ->connect();

  if ($staffQ->existLogin($staff->getLogin()))
  {
    $loginUsed = true;
  }
  else
  {
    $staffQ->insert($staff);
  }
  $staffQ->close();
  unset($staffQ);

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  if (isset($loginUsed) && $loginUsed)
  {
    $info = urlencode($staff->getLogin());
    $returnLocation .= "?login=Y&info=" . $info;
  }
  else
  {
    $info = urlencode($staff->getFirstName() . " " . $staff->getSurname1() . " " . $staff->getSurname2());
    $returnLocation .= "?added=Y&info=" . $info;
  }
  unset($staff);
  header("Location: " . $returnLocation);
?>
