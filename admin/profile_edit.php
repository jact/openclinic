<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: profile_edit.php,v 1.2 2004/04/23 20:36:50 jact Exp $
 */

/**
 * profile_edit.php
 ********************************************************************
 * Profile edition process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: ../admin/profile_list.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "profiles";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Description_Query.php");
  require_once("../lib/error_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Validate data
  ////////////////////////////////////////////////////////////////////
  $des = new Description();

  $des->setCode($_POST["id_profile"]);
  $_POST["id_profile"] = $des->getCode();

  $des->setDescription($_POST["description"]);
  $_POST["description"] = $des->getDescription();

  if ( !$des->validateData() )
  {
    $pageErrors["description"] = $des->getDescriptionError();

    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;

    header("Location: ../admin/profile_edit_form.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Update profile table row
  ////////////////////////////////////////////////////////////////////
  $desQ = new Description_Query();
  $desQ->connect();
  if ($desQ->errorOccurred())
  {
    showQueryError($desQ);
  }

  if ( !$desQ->update("profile_tbl", $des) )
  {
    $desQ->close();
    showQueryError($desQ);
  }
  $desQ->close();
  unset($desQ);

  ////////////////////////////////////////////////////////////////////
  // Destroy form values and errors
  ////////////////////////////////////////////////////////////////////
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  ////////////////////////////////////////////////////////////////////
  // Show success page
  ////////////////////////////////////////////////////////////////////
  $title = _("Edit Profile");
  require_once("../shared/header.php");

  $returnLocation = "../admin/profile_list.php";

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Profiles") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "profiles.png");
  unset($links);

  echo '<p>' . sprintf(_("Profile, %s, has been updated."), $des->getDescription()) . "</p>\n";
  unset($des);

  echo '<p><a href="' . $returnLocation . '">' . _("Return to profiles list") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
