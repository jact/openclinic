<?php
/**
 * setting_fields.php
 *
 * Fields of config settings data
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: setting_fields.php,v 1.27 2007/12/15 12:44:41 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../lib/File.php");

  $body = array();

  $row = Form::strLabel("session_timeout", _("Session Timeout") . ":", true);
  $row .= Form::strText("session_timeout", 3, $formVar["session_timeout"],
    isset($formError["session_timeout"]) ? array('error' => $formError["session_timeout"]) : null
  );
  $row .= _("minutes");
  $body[] = $row;

  $row = Form::strLabel("items_per_page", _("Search Results") . ":", true);
  $row .= Form::strText("items_per_page", 2, $formVar["items_per_page"],
    isset($formError["items_per_page"]) ? array('error' => $formError["items_per_page"]) : null
  );
  $row .= _("items per page") . "**";
  $body[] = $row;

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    $row = Form::strLabel("language", _("Language") . ":");
    $row .= Form::strSelect("language", I18n::languageList(), $formVar["language"]);
    $body[] = $row;
  }
  elseif (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    $row = Form::strHidden("language", "en");
    $body[] = $row;
  }

  $row = Form::strLabel("id_theme", _("Theme by default") . ":");
  $row .= Form::strSelectTable("theme_tbl", "id_theme", $formVar["id_theme"], "theme_name");
  $body[] = $row;

  $appFields = Form::strFieldset(_("Application"), $body);

  $body = array();

  $row = Form::strLabel("clinic_name", _("Clinic Name") . ":");
  $row .= Form::strText("clinic_name", 40,
    isset($formVar["clinic_name"]) ? $formVar["clinic_name"] : null,
    array(
      'maxlength' => 128,
      'error' => isset($formError["clinic_name"]) ? $formError["clinic_name"] : null
    )
  );
  $body[] = $row;

  $row = Form::strLabel("clinic_hours", _("Clinic Hours") . ":");
  $row .= Form::strText("clinic_hours", 40,
    isset($formVar["clinic_hours"]) ? $formVar["clinic_hours"] : null,
    array(
      'maxlength' => 128,
      'error' => isset($formError["clinic_hours"]) ? $formError["clinic_hours"] : null
    )
  );
  $body[] = $row;

  $row = Form::strLabel("clinic_address", _("Clinic Address") . ":");
  $row .= Form::strTextArea("clinic_address", 3, 30, isset($formVar["clinic_address"]) ? $formVar["clinic_address"] : null);
  $body[] = $row;

  $row = Form::strLabel("clinic_phone", _("Clinic Phone") . ":");
  $row .= Form::strText("clinic_phone", 40,
    isset($formVar["clinic_phone"]) ? $formVar["clinic_phone"] : null,
    isset($formError["clinic_phone"]) ? array('error' => $formError["clinic_phone"]) : null
  );
  $body[] = $row;

  $row = Form::strLabel("clinic_url", _("Clinic URL") . ":");
  $row .= Form::strText("clinic_url", 40,
    isset($formVar["clinic_url"]) ? $formVar["clinic_url"] : null,
    array(
      'maxlength' => 300,
      'error' => isset($formError["clinic_url"]) ? $formError["clinic_url"] : null
    )
  );
  $body[] = $row;

  $clinicFields = Form::strFieldset(_("Clinic"), $body);

  $body = array($appFields, $clinicFields);

  $foot = array(
    Form::strButton("update", _("Update"))
    . Form::generateToken()
  );

  Form::fieldset($title, $body, $foot);
?>
