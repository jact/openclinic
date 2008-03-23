<?php
/**
 * patient_search_fields.php
 *
 * Fields of patient's search
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_search_fields.php,v 1.19 2008/03/23 12:00:17 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  $thead = array(
    $title => array('colspan' => 2)
  );

  $tbody = array();

  $row = Form::label("search_type", _("Field") . ': ');

  $array = null;
  $array[OPEN_SEARCH_SURNAME1] = _("Surname 1");
  $array[OPEN_SEARCH_SURNAME2] = _("Surname 2");
  $array[OPEN_SEARCH_FIRSTNAME] = _("First Name");
  $array[OPEN_SEARCH_NIF] = _("Tax Identification Number (TIN)");
  $array[OPEN_SEARCH_NTS] = _("Sanitary Card Number (SCN)");
  $array[OPEN_SEARCH_NSS] = _("National Health Service Number (NHSN)");
  $array[OPEN_SEARCH_BIRTHPLACE] = _("Birth Place");
  $array[OPEN_SEARCH_ADDRESS] = _("Address");
  $array[OPEN_SEARCH_PHONE] = _("Phone Contact");
  $array[OPEN_SEARCH_INSURANCE] = _("Insurance Company");
  $array[OPEN_SEARCH_COLLEGIATE] = _("Collegiate Number");

  $row .= Form::select("search_type", $array, OPEN_SEARCH_SURNAME1);
  unset($array);

  $tbody[] = array($row);

  $row = '* ' . Form::text("search_text", null, array('size' => 40, 'maxlength' => 80));
  $row .= Form::button("search_patient", _("Search"));

  $tbody[] = array($row);

  $row = Form::label("logical", _("Logical") . ': ');

  $array = null;
  $array[OPEN_OR] = "OR";
  $array[OPEN_NOT] = "NOT";
  $array[OPEN_AND] = "AND"; // it makes sense in fields with two or more words

  $row .= Form::select("logical", $array, OPEN_OR);
  unset($array);

  $row .= OPEN_SEPARATOR;

  $row .= Form::label("limit", _("Limit") . ': ');

  $array = null;
  $array["0"] = _("All");
  $array["10"] = 10;
  $array["20"] = 20;
  $array["50"] = 50;
  $array["100"] = 100;
  $row .= Form::select("limit", $array);
  unset($array);

  if (isset($tokenForm)) // defined in patient_search_form.php
  {
    $row .= $tokenForm;
  }
  else
  {
    $row .= Form::generateToken();
  }
  $row .= Form::hidden("page", 1);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $options = array(
    0 => array('align' => 'center'),
    1 => array('align' => 'center'),
    'r0' => array('colspan' => 2),
    'r1' => array('colspan' => 2),
    'shaded' => false,
    'align' => 'center'
  );

  echo HTML::table($thead, $tbody, null, $options);
?>
