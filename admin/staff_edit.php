<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_edit.php,v 1.4 2004/07/10 15:09:27 jact Exp $
 */

/**
 * staff_edit.php
 ********************************************************************
 * Staff member edition process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "staff";
  //$restrictInDemo = true;
  $errorLocation = "../admin/staff_edit_form.php";
  $returnLocation = "../admin/staff_list.php";

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Staff_Query.php");
  require_once("../lib/error_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Validate data
  ////////////////////////////////////////////////////////////////////
  $staff = new Staff();

  $staff->setIdMember($_POST["id_member"]);

  require_once("../admin/staff_validate_post.php");

  ////////////////////////////////////////////////////////////////////
  // Update staff member
  ////////////////////////////////////////////////////////////////////
  $staffQ = new Staff_Query();
  $staffQ->connect();
  if ($staffQ->isError())
  {
    showQueryError($staffQ);
  }

  if ($staffQ->existLogin($staff->getLogin(), $staff->getIdMember()))
  {
    $loginUsed = true;
  }
  else
  {
    $staffQ->update($staff);
    if ($staffQ->isError())
    {
      $staffQ->close();
      showQueryError($staffQ);
    }
  }
  $staffQ->close();
  unset($staffQ);

  ////////////////////////////////////////////////////////////////////
  // Destroy form values and errors
  ////////////////////////////////////////////////////////////////////
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  ////////////////////////////////////////////////////////////////////
  // Redirect to theme list to avoid reload problem
  ////////////////////////////////////////////////////////////////////
  if (isset($loginUsed) && $loginUsed)
  {
    $info = urlencode($staff->getLogin());
    $getStr = "?login=Y&info=" . $info;
  }
  else
  {
    $info = urlencode($staff->getFirstName() . " " . $staff->getSurname1() . " " . $staff->getSurname2());
    $getStr = "?updated=Y&info=" . $info;
  }
  unset($staff);
  header("Location: " . $returnLocation . $getStr);
?>
