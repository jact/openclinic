<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: setting_edit_form.php,v 1.11 2005/07/19 19:50:03 jact Exp $
 */

/**
 * setting_edit_form.php
 *
 * Edition screen of config settings
 *
 * Author: jact <jachavar@gmail.com>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "settings";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  // after login_check inclusion to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "clinic_name";

  ////////////////////////////////////////////////////////////////////
  // Checking for query string flag to read data from database.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["reset"]))
  {
    include_once("../classes/Setting_Query.php");

    $setQ = new Setting_Query();
    $setQ->connect();
    if ($setQ->isError())
    {
      Error::query($setQ);
    }

    $setQ->select();
    if ($setQ->isError())
    {
      $setQ->close();
      Error::query($setQ);
    }

    $set = $setQ->fetch();
    if ($setQ->isError())
    {
      Error::fetch($setQ, false);
    }
    else
    {
      $postVars["clinic_name"] = $set->getClinicName();
      $postVars["clinic_image_url"] = $set->getClinicImageUrl();
      $postVars["use_image"] = ($set->isUseImageSet() ? "checked" : "");
      $postVars["clinic_hours"] = $set->getClinicHours();
      $postVars["clinic_address"] = $set->getClinicAddress();
      $postVars["clinic_phone"] = $set->getClinicPhone();
      $postVars["clinic_url"] = $set->getClinicUrl();
      $postVars["language"] = $set->getLanguage();
      $postVars["id_theme"] = $set->getIdTheme();
      $postVars["session_timeout"] = $set->getSessionTimeout();
      $postVars["items_per_page"] = $set->getItemsPerPage();
    }
    $setQ->freeResult();
    $setQ->close();
    unset($setQ);
    unset($set);
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Config settings");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  showNavLinks($links, "config_clinic.png");
  unset($links);

  require_once("../shared/form_errors_msg.php");

  ////////////////////////////////////////////////////////////////////
  // Display update message if coming from setting_edit with a successful update.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["updated"]))
  {
    showMessage(_("Data has been updated."), OPEN_MSG_INFO);
  }
?>

<form method="post" action="../admin/setting_edit.php">
  <div>
<?php require_once("../admin/setting_fields.php"); ?>
  </div>
</form>

<?php
  showMessage('* ' . _("Note: The fields with * are required."));
  showMessage('** ' . _("Note: If zero, searchs return all results without pagination."));

  require_once("../shared/footer.php");
?>
