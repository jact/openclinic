<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: setting_edit.php,v 1.1 2004/03/24 19:58:58 jact Exp $
 */

/**
 * setting_edit.php
 ********************************************************************
 * Config settings edition process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 24/03/04 20:58
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: ../admin/setting_edit_form.php?reset=Y");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "settings";
  //$restrictInDemo = true;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Setting_Query.php");
  require_once("../lib/error_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Validate data
  ////////////////////////////////////////////////////////////////////
  $set = new Setting();

  $set->setClinicName($_POST["clinic_name"]);
  $_POST["clinic_name"] = $set->getClinicName();

  $set->setClinicImageUrl($_POST["clinic_image_url"]);
  $_POST["clinic_image_url"] = $set->getClinicImageUrl();

  $set->setUseImage(isset($_POST["use_image"]));

  $set->setClinicHours($_POST["clinic_hours"]);
  $_POST["clinic_hours"] = $set->getClinicHours();

  $set->setClinicAddress($_POST["clinic_address"]);
  $_POST["clinic_address"] = $set->getClinicAddress();

  $set->setClinicPhone($_POST["clinic_phone"]);
  $_POST["clinic_phone"] = $set->getClinicPhone();

  $set->setClinicUrl($_POST["clinic_url"]);
  $_POST["clinic_url"] = $set->getClinicUrl();

  $set->setLanguage($_POST["language"]);
  $_POST["language"] = $set->getLanguage();

  $set->setSessionTimeout($_POST["session_timeout"]);
  $_POST["session_timeout"] = $set->getSessionTimeout();

  $set->setItemsPerPage($_POST["items_per_page"]);
  $_POST["items_per_page"] = $set->getItemsPerPage();

  if ( !$set->validateData() )
  {
    $pageErrors["session_timeout"] = $set->getSessionTimeoutError();
    $pageErrors["items_per_page"] = $set->getItemsPerPageError();

    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;

    header("Location: ../admin/setting_edit_form.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Update setting table row
  ////////////////////////////////////////////////////////////////////
  $setQ = new Setting_Query();
  $setQ->connect();
  if ($setQ->errorOccurred())
  {
    showQueryError($setQ);
  }

  if ( !$setQ->update($set) )
  {
    $setQ->close();
    showQueryError($setQ);
  }

  if (isset($_POST["id_theme"]))
  {
    if ( !$setQ->updateTheme($_POST["id_theme"]) )
    {
      $setQ->close();
      showQueryError($setQ);
    }
  }
  $setQ->close();
  unset($setQ);
  unset($set);

  ////////////////////////////////////////////////////////////////////
  // Destroy form values and errors
  ////////////////////////////////////////////////////////////////////
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  header("Location: ../admin/setting_edit_form.php?reset=Y&updated=Y");
?>
