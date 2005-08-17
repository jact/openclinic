<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_new.php,v 1.8 2005/08/17 16:54:52 jact Exp $
 */

/**
 * staff_new.php
 *
 * Staff member addition process
 *
 * Author: jact <jachavar@gmail.com>
 */

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

  require_once("../shared/read_settings.php");
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
  if ($staffQ->isError())
  {
    Error::query($staffQ);
  }

  if ($staffQ->existLogin($staff->getLogin()))
  {
    $loginUsed = true;
  }
  else
  {
    $staffQ->insert($staff);
    if ($staffQ->isError())
    {
      $staffQ->close();
      Error::query($staffQ);
    }
  }
  $staffQ->close();
  unset($staffQ);

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

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
