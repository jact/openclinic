<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_del.php,v 1.7 2004/07/10 15:09:27 jact Exp $
 */

/**
 * staff_del.php
 ********************************************************************
 * Staff member deletion process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
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
  require_once("../lib/error_lib.php");

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
    showQueryError($staffQ);
  }

  $numRows = $staffQ->select($idMember);
  if ($staffQ->isError())
  {
    $staffQ->close();
    showQueryError($staffQ);
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
    showFetchError($staffQ);
  }

  $staffQ->delete($staff->getIdMember(), $staff->getIdUser());
  if ($staffQ->isError())
  {
    $staffQ->close();
    showQueryError($staffQ);
  }
  $staffQ->close();
  unset($staffQ);

  ////////////////////////////////////////////////////////////////////
  // Redirect to theme list to avoid reload problem
  ////////////////////////////////////////////////////////////////////
  $info = urlencode($staff->getFirstName() . " " . $staff->getSurname1() . " " . $staff->getSurname2());
  unset($staff);
  header("Location: " . $returnLocation . "?deleted=Y&info=" . $info);
?>
