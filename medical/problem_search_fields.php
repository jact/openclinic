<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_search_fields.php,v 1.14 2006/03/26 15:20:49 jact Exp $
 */

/**
 * problem_search_fields.php
 *
 * Fields of medical problem's search
 *
 * @author jact <jachavar@gmail.com>
 * @since 0.4
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  $thead = array(
    $headerWording2 => array('colspan' => 2)
  );

  $tbody = array();

  $row = Form::strLabel("search_type_problem", _("Field") . ': ');

  $array = null;
  $array[OPEN_SEARCH_WORDING] = _("Wording");
  $array[OPEN_SEARCH_SUBJECTIVE] = _("Subjective");
  $array[OPEN_SEARCH_OBJECTIVE] = _("Objective");
  $array[OPEN_SEARCH_APPRECIATION] = _("Appreciation");
  $array[OPEN_SEARCH_ACTIONPLAN] = _("Action Plan");
  $array[OPEN_SEARCH_PRESCRIPTION] = _("Prescription");

  $row .= Form::strSelect("search_type_problem", $array, OPEN_SEARCH_WORDING);
  unset($array);

  $tbody[] = array($row);

  $row = '* ' . Form::strText("search_text_problem", 40, "", array('maxlength' => 120));
  $row .= Form::strButton("submit_problem", _("Search"));

  $tbody[] = array($row);

  $row = Form::strLabel("logical_problem", _("Logical") . ': ');

  $array = null;
  $array[OPEN_OR] = "OR";
  $array[OPEN_NOT] = "NOT";
  $array[OPEN_AND] = "AND"; // it makes sense in fields with two or more words

  $row .= Form::strSelect("logical_problem", $array, OPEN_OR);
  unset($array);

  $row .= OPEN_SEPARATOR;
  $row .= Form::strLabel("limit_problem", _("Limit") . ': ');

  $array = null;
  $array["0"] = _("All");
  $array["10"] = 10;
  $array["20"] = 20;
  $array["50"] = 50;
  $array["100"] = 100;

  $row .= Form::strSelect("limit_problem", $array);
  unset($array);

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $options = array(
    0 => array('align' => 'center'),
    1 => array('align' => 'center'),
    'r0' => array('colspan' => 2),
    'r1' => array('colspan' => 2),
    'shaded' => false,
    'align' => 'center'
  );

  HTML::table($thead, $tbody, null, $options);
?>
