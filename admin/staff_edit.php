<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_edit.php,v 1.1 2004/03/24 19:52:08 jact Exp $
 */

/**
 * staff_edit.php
 ********************************************************************
 * Staff member edition process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 24/03/04 20:52
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
  if ($staffQ->errorOccurred())
  {
    showQueryError($staffQ);
  }

  if ($staffQ->existLogin($staff->getLogin(), $staff->getIdMember()))
  {
    $loginUsed = true;
  }
  else
  {
    if ( !$staffQ->update($staff) )
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
  // Show success page
  ////////////////////////////////////////////////////////////////////
  $title = _("Staff Members");
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

  echo (isset($loginUsed) && $loginUsed)
    ? '<p>' . sprintf(_("Login, %s, already exists. The changes have no effect."), $staff->getLogin()) . "</p>\n"
    : '<p>' . sprintf(_("Staff member, %s %s %s, has been updated."), $staff->getFirstName(), $staff->getSurname1(), $staff->getSurname2()) . "</p>\n";

  unset($staff);

  echo '<p><a href="' . $returnLocation . '">' . _("Return to staff list") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
