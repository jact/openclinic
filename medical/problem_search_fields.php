<?php
/**
 * problem_search_fields.php
 *
 * Fields of medical problem's search
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_search_fields.php,v 1.19 2008/03/23 12:00:17 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.4
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  $thead = array(
    $headerWording2 => array('colspan' => 2)
  );

  $tbody = array();

  $row = Form::label("search_type_problem", _("Field") . ': ');

  $array = null;
  $array[OPEN_SEARCH_WORDING] = _("Wording");
  $array[OPEN_SEARCH_SUBJECTIVE] = _("Subjective");
  $array[OPEN_SEARCH_OBJECTIVE] = _("Objective");
  $array[OPEN_SEARCH_APPRECIATION] = _("Appreciation");
  $array[OPEN_SEARCH_ACTIONPLAN] = _("Action Plan");
  $array[OPEN_SEARCH_PRESCRIPTION] = _("Prescription");

  $row .= Form::select("search_type_problem", $array, OPEN_SEARCH_WORDING);
  unset($array);

  $tbody[] = array($row);

  $row = '* ' . Form::text("search_text_problem", null, array('size' => 40, 'maxlength' => 120));
  $row .= Form::button("search_problem", _("Search"));

  $tbody[] = array($row);

  $row = Form::label("logical_problem", _("Logical") . ': ');

  $array = null;
  $array[OPEN_OR] = "OR";
  $array[OPEN_NOT] = "NOT";
  $array[OPEN_AND] = "AND"; // it makes sense in fields with two or more words

  $row .= Form::select("logical_problem", $array, OPEN_OR);
  unset($array);

  $row .= OPEN_SEPARATOR;
  $row .= Form::label("limit_problem", _("Limit") . ': ');

  $array = null;
  $array["0"] = _("All");
  $array["10"] = 10;
  $array["20"] = 20;
  $array["50"] = 50;
  $array["100"] = 100;

  $row .= Form::select("limit_problem", $array);
  unset($array);

  $row .= str_replace('id="token_form"', 'id="token_form_2"', $tokenForm); // defined in patient_search_form.php
  $row .= Form::hidden("page", 1, array('id' => 'page_problem'));

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
