<?php
/**
 * setting_fields.php
 *
 * Fields of config settings data
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: setting_fields.php,v 1.29 2013/01/07 18:06:13 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../lib/File.php");

  $body = array();

  $row = Form::label("session_timeout", _("Session Timeout") . ":", array('class' => 'required'));
  $row .= Form::text("session_timeout", $formVar["session_timeout"],
    array(
      'size' => 3,
      'error' => isset($formError["session_timeout"]) ? $formError["session_timeout"] : null
    )
  );
  $row .= _("minutes");
  $body[] = $row;

  $row = Form::label("items_per_page", _("Search Results") . ":", array('class' => 'required'));
  $row .= Form::text("items_per_page", $formVar["items_per_page"],
    array(
      'size' => 3,
      'error' => isset($formError["items_per_page"]) ? $formError["items_per_page"] : null
    )
  );
  $row .= _("items per page") . "**";
  $body[] = $row;

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    $row = Form::label("language", _("Language") . ":");
    $languageList = I18n::languageList();
    $row .= Form::select("language", $languageList, $formVar["language"]);
    $body[] = $row;
  }
  elseif (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    $row = Form::hidden("language", "en");
    $body[] = $row;
  }

  $row = Form::label("id_theme", _("Theme by default") . ":");
  $row .= Form::selectTable("theme_tbl", "id_theme", $formVar["id_theme"], "theme_name");
  $body[] = $row;

  $appFields = Form::fieldset(_("Application"), $body);

  $body = array();

  $row = Form::label("clinic_name", _("Clinic Name") . ":");
  $row .= Form::text("clinic_name",
    isset($formVar["clinic_name"]) ? $formVar["clinic_name"] : null,
    array(
      'size' => 40,
      'maxlength' => 128,
      'error' => isset($formError["clinic_name"]) ? $formError["clinic_name"] : null
    )
  );
  $body[] = $row;

  $row = Form::label("clinic_hours", _("Clinic Hours") . ":");
  $row .= Form::text("clinic_hours",
    isset($formVar["clinic_hours"]) ? $formVar["clinic_hours"] : null,
    array(
      'size' => 40,
      'maxlength' => 128,
      'error' => isset($formError["clinic_hours"]) ? $formError["clinic_hours"] : null
    )
  );
  $body[] = $row;

  $row = Form::label("clinic_address", _("Clinic Address") . ":");
  $row .= Form::textArea("clinic_address",
    isset($formVar["clinic_address"]) ? $formVar["clinic_address"] : null,
    array(
      'rows' => 3,
      'cols' => 30
    )
  );
  $body[] = $row;

  $row = Form::label("clinic_phone", _("Clinic Phone") . ":");
  $row .= Form::text("clinic_phone",
    isset($formVar["clinic_phone"]) ? $formVar["clinic_phone"] : null,
    array(
      'size' => 40,
      'error' => isset($formError["clinic_phone"]) ? $formError["clinic_phone"] : null
    )
  );
  $body[] = $row;

  $row = Form::label("clinic_url", _("Clinic URL") . ":");
  $row .= Form::text("clinic_url",
    isset($formVar["clinic_url"]) ? $formVar["clinic_url"] : null,
    array(
      'size' => 40,
      'maxlength' => 300,
      'error' => isset($formError["clinic_url"]) ? $formError["clinic_url"] : null
    )
  );
  $body[] = $row;

  $clinicFields = Form::fieldset(_("Clinic"), $body);

  $body = array($appFields, $clinicFields);

  $foot = array(
    Form::button("update", _("Update"))
    . Form::generateToken()
  );

  echo Form::fieldset($title, $body, $foot);
?>
