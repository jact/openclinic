<?php
/**
 * staff_fields.php
 *
 * Fields of staff member data
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: staff_fields.php,v 1.20 2007/10/29 20:03:54 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  $tbody = array();

  $row = Form::strLabel("nif", _("Tax Identification Number (TIN)") . ":");
  $row .= Form::strText("nif", 20, isset($formVar["nif"]) ? $formVar["nif"] : null,
    isset($formError["nif"]) ? array('error' => $formError["nif"]) : null
  );
  $tbody[] = $row;

  if ((isset($memberType) && $memberType == "D")
    || (isset($formVar["member_type"]) && substr($formVar["member_type"], 0, 1) == "D"))
  {
    $row = Form::strLabel("collegiate_number", _("Collegiate Number") . ":", true);
    $row .= Form::strText("collegiate_number", 20,
      isset($formVar["collegiate_number"]) ? $formVar["collegiate_number"] : null,
      isset($formError["collegiate_number"]) ? array('error' => $formError["collegiate_number"]) : null
    );
    $tbody[] = $row;
  }

  $row = Form::strLabel("first_name", _("First Name") . ":", true);
  $row .= Form::strText("first_name", 25, isset($formVar["first_name"]) ? $formVar["first_name"] : null,
    isset($formError["first_name"]) ? array('error' => $formError["first_name"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("surname1", _("Surname 1") . ":", true);
  $row .= Form::strText("surname1", 30, isset($formVar["surname1"]) ? $formVar["surname1"] : null,
    isset($formError["surname1"]) ? array('error' => $formError["surname1"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("surname2", _("Surname 2") . ":", true);
  $row .= Form::strText("surname2", 30, isset($formVar["surname2"]) ? $formVar["surname2"] : null,
    isset($formError["surname2"]) ? array('error' => $formError["surname2"]) : null
  );
  $tbody[] = $row;

  $row = Form::strLabel("address", _("Address") . ":");
  $row .= Form::strTextArea("address", 2, 30, isset($formVar["address"]) ? $formVar["address"] : null);
  $tbody[] = $row;

  $row = Form::strLabel("phone_contact", _("Phone Contact") . ":");
  $row .= Form::strTextArea("phone_contact", 2, 30, isset($formVar["phone_contact"]) ? $formVar["phone_contact"] : null);
  $tbody[] = $row;

  $row = Form::strLabel("login", _("Login") . ":");
  $row .= Form::strText("login", 20, isset($formVar["login"]) ? $formVar["login"] : null,
    isset($formError["login"]) ? array('error' => $formError["login"]) : null
  );
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("save", _("Submit"))
    . Form::generateToken()
  );

  Form::fieldset($title, $tbody, $tfoot);
?>
