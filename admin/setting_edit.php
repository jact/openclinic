<?php
/**
 * setting_edit.php
 *
 * Config settings edition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: setting_edit.php,v 1.13 2007/10/25 21:58:08 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: ../admin/setting_edit_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  //$restrictInDemo = true;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/Form.php");

  Form::compareToken('../admin/setting_edit_form.php');

  require_once("../model/Setting_Query.php");

  /**
   * Validate data
   */
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
    $formError["session_timeout"] = $set->getSessionTimeoutError();
    $formError["items_per_page"] = $set->getItemsPerPageError();

    $_SESSION["formVar"] = $_POST;
    $_SESSION["formError"] = $formError;

    header("Location: ../admin/setting_edit_form.php");
    exit();
  }

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  /**
   * Update app settings
   */
  $setQ = new Setting_Query();
  $setQ->connect();

  $setQ->update($set);

  if (isset($_POST["id_theme"]))
  {
    $setQ->updateTheme($_POST["id_theme"]);
  }
  $setQ->close();
  unset($setQ);
  unset($set);

  /**
   * Redirect to destiny to avoid reload problem
   */
  FlashMsg::add(_("Data has been updated."));
  header("Location: ../admin/setting_edit_form.php");
?>
