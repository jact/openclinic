<?php
/**
 * setting_edit_form.php
 *
 * Edition screen of config settings
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: setting_edit_form.php,v 1.33 2008/03/23 11:58:56 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "settings";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR);

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
  $focusFormField = "session_timeout"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");

  /**
   * Breadcrumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_config");
  unset($links);

  echo Form::errorMsg();

  /**
   * Edit form
   */
  echo HTML::start('form', array('method' => 'post', 'action' => '../admin/setting_edit.php'));
  require_once("../admin/setting_fields.php");
  echo HTML::end('form');

  echo Msg::hint('* ' . _("Note: The fields with * are required."));
  echo Msg::hint('** ' . _("Note: If zero, searchs return all results without pagination."));

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  require_once("../layout/footer.php");
?>
