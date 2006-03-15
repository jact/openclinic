<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_edit.php,v 1.9 2006/03/15 20:13:54 jact Exp $
 */

/**
 * staff_edit.php
 *
 * Staff member edition process
 *
 * Author: jact <jachavar@gmail.com>
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

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Staff_Query.php");

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
    $loginUsed = true;
  }
  else
  {
    $staffQ->update($staff);
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
    $returnLocation .= "?updated=Y&info=" . $info;
  }
  unset($staff);
  header("Location: " . $returnLocation);
?>
