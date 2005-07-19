<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_del.php,v 1.9 2005/07/19 19:50:04 jact Exp $
 */

/**
 * staff_del.php
 *
 * Staff member deletion process
 *
 * Author: jact <jachavar@gmail.com>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "staff";
  //$restrictInDemo = true;
  $returnLocation = "../admin/staff_list.php";

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to staff list if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Staff_Query.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving post var
  ////////////////////////////////////////////////////////////////////
  $idMember = intval($_POST["id_member"]);

  ////////////////////////////////////////////////////////////////////
  // Delete staff member
  ////////////////////////////////////////////////////////////////////
  $staffQ = new Staff_Query();
  $staffQ->connect();
  if ($staffQ->isError())
  {
    Error::query($staffQ);
  }

  $numRows = $staffQ->select($idMember);
  if ($staffQ->isError())
  {
    $staffQ->close();
    Error::query($staffQ);
  }

  if ( !$numRows )
  {
    $staffQ->close();
    include_once("../shared/header.php");

    echo '<p>' . _("That staff member does not exist.") . "</p>\n";

    include_once("../shared/footer.php");
    exit();
  }

  $staff = $staffQ->fetch();
  if ($staffQ->isError())
  {
    $staffQ->close();
    Error::fetch($staffQ);
  }

  $staffQ->delete($staff->getIdMember(), $staff->getIdUser());
  if ($staffQ->isError())
  {
    $staffQ->close();
    Error::query($staffQ);
  }
  $staffQ->close();
  unset($staffQ);

  ////////////////////////////////////////////////////////////////////
  // Redirect to staff list to avoid reload problem
  ////////////////////////////////////////////////////////////////////
  $info = urlencode($staff->getFirstName() . " " . $staff->getSurname1() . " " . $staff->getSurname2());
  unset($staff);
  header("Location: " . $returnLocation . "?deleted=Y&info=" . $info);
?>
