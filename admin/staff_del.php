<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_del.php,v 1.3 2004/04/24 16:45:46 jact Exp $
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
  if ($staffQ->errorOccurred())
  {
    showQueryError($staffQ);
  }

  $numRows = $staffQ->select($idMember);
  if ($staffQ->errorOccurred())
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

  $staff = $staffQ->fetchStaff();
  if ( !$staffQ->delete($staff->getIdMember(), $staff->getIdUser()) )
  {
    $staffQ->close();
    showQueryError($staffQ);
  }
  $staffQ->close();
  unset($staffQ);

  ////////////////////////////////////////////////////////////////////
  // Show success page
  ////////////////////////////////////////////////////////////////////
  $title = _("Delete Staff Member");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Staff Members") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "staff.png");
  unset($links);

  echo '<p>' . sprintf(_("Staff member, %s %s %s, has been deleted."), $staff->getFirstName(), $staff->getSurname1(), $staff->getSurname2()) . "</p>\n";
  unset($staff);

  echo '<p><a href="' . $returnLocation . '">' . _("Return to staff list") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
