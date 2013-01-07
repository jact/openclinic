<?php
/**
 * staff_fields.php
 *
 * Fields of staff member data
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: staff_fields.php,v 1.22 2013/01/07 18:07:46 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  $tbody = array();

  $row = Form::label("nif", _("Tax Identification Number (TIN)") . ":");
  $row .= Form::text("nif",
    isset($formVar["nif"]) ? $formVar["nif"] : null,
    array(
      'size' => 20,
      'error' => isset($formError["nif"]) ? $formError["nif"] : null
    )
  );
  $tbody[] = $row;

  if ((isset($memberType) && $memberType == "D")
    || (isset($formVar["member_type"]) && substr($formVar["member_type"], 0, 1) == "D"))
  {
    $row = Form::label("collegiate_number", _("Collegiate Number") . ":", array('class' => 'required'));
    $row .= Form::text("collegiate_number",
      isset($formVar["collegiate_number"]) ? $formVar["collegiate_number"] : null,
      array(
        'size' => 20,
        'error' => isset($formError["collegiate_number"]) ? $formError["collegiate_number"] : null
      )
    );
    $tbody[] = $row;
  }

  $row = Form::label("first_name", _("First Name") . ":", array('class' => 'required'));
  $row .= Form::text("first_name",
    isset($formVar["first_name"]) ? $formVar["first_name"] : null,
    array(
      'size' => 25,
      'error' => isset($formError["first_name"]) ? $formError["first_name"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::label("surname1", _("Surname 1") . ":", array('class' => 'required'));
  $row .= Form::text("surname1",
    isset($formVar["surname1"]) ? $formVar["surname1"] : null,
    array(
      'size' => 30,
      'error' => isset($formError["surname1"]) ? $formError["surname1"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::label("surname2", _("Surname 2") . ":"/*, array('class' => 'required')*/);
  $row .= Form::text("surname2",
    isset($formVar["surname2"]) ? $formVar["surname2"] : null,
    array(
      'size' => 30,
      'error' => isset($formError["surname2"]) ? $formError["surname2"] : null
    )
  );
  $tbody[] = $row;

  $row = Form::label("address", _("Address") . ":");
  $row .= Form::textArea("address",
    isset($formVar["address"]) ? $formVar["address"] : null,
    array(
      'rows' => 2,
      'cols' => 30
    )
  );
  $tbody[] = $row;

  $row = Form::label("phone_contact", _("Phone Contact") . ":");
  $row .= Form::textArea("phone_contact",
    isset($formVar["phone_contact"]) ? $formVar["phone_contact"] : null,
    array(
      'rows' => 2,
      'cols' => 30
    )
  );
  $tbody[] = $row;

  $row = Form::label("login", _("Login") . ":");
  $row .= Form::text("login",
    isset($formVar["login"]) ? $formVar["login"] : null,
    array(
      'size' => 20,
      'error' => isset($formError["login"]) ? $formError["login"] : null
    )
  );
  $tbody[] = $row;

  $tfoot = array(
    Form::button("save", _("Submit"))
    . Form::generateToken()
  );

  echo Form::fieldset($title, $tbody, $tfoot);
?>
