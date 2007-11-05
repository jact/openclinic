<?php
/**
 * setting_edit_form.php
 *
 * Edition screen of config settings
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: setting_edit_form.php,v 1.31 2007/11/05 14:28:24 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "settings";

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");

  /**
   * Checking for $formError to read data from database
   */
  if ( !isset($formError) )
  {
    include_once("../model/Query/Setting.php");

    $setQ = new Query_Setting();
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
  require_once("../layout/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon configIcon");
  unset($links);

  Form::errorMsg();

  /**
   * Edit form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../admin/setting_edit.php'));
  require_once("../admin/setting_fields.php");
  HTML::end('form');

  Msg::hint('* ' . _("Note: The fields with * are required."));
  Msg::hint('** ' . _("Note: If zero, searchs return all results without pagination."));

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  require_once("../layout/footer.php");
?>
