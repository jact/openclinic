<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: setting_edit_form.php,v 1.21 2006/03/26 14:47:23 jact Exp $
 */

/**
 * setting_edit_form.php
 *
 * Edition screen of config settings
 *
 * @author jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "settings";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError

  /**
   * Checking for $formError to read data from database
   */
  if ( !isset($formError) )
  {
    include_once("../classes/Setting_Query.php");

    $setQ = new Setting_Query();
    $setQ->connect();

    $setQ->select();

    $set = $setQ->fetch();
    if ($set)
    {
      $formVar["clinic_name"] = $set->getClinicName();
      $formVar["clinic_image_url"] = $set->getClinicImageUrl();
      $formVar["use_image"] = ($set->isUseImageSet() ? "checked" : "");
      $formVar["clinic_hours"] = $set->getClinicHours();
      $formVar["clinic_address"] = $set->getClinicAddress();
      $formVar["clinic_phone"] = $set->getClinicPhone();
      $formVar["clinic_url"] = $set->getClinicUrl();
      $formVar["language"] = $set->getLanguage();
      $formVar["id_theme"] = $set->getIdTheme();
      $formVar["session_timeout"] = $set->getSessionTimeout();
      $formVar["items_per_page"] = $set->getItemsPerPage();
    }
    else
    {
      Error::fetch($setQ, false);
    }
    $setQ->freeResult();
    $setQ->close();
    unset($setQ);
    unset($set);
  }

  /**
   * Show page
   */
  $title = _("Config settings");
  $focusFormField = "clinic_name"; // to avoid JavaScript mistakes in demo version
  require_once("../shared/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon configIcon");
  unset($links);

  require_once("../shared/form_errors_msg.php");

  /**
   * Display update message if coming from setting_edit with a successful update.
   */
  if (isset($_GET["updated"]))
  {
    HTML::message(_("Data has been updated."), OPEN_MSG_INFO);
  }

  /**
   * Edit form
   */
  echo '<form method="post" action="../admin/setting_edit.php">' . "\n";
  require_once("../admin/setting_fields.php");
  echo "</form>\n";

  HTML::message('* ' . _("Note: The fields with * are required."));
  HTML::message('** ' . _("Note: If zero, searchs return all results without pagination."));

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  require_once("../shared/footer.php");
?>
