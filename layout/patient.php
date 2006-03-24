<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient.php,v 1.8 2006/03/24 20:28:54 jact Exp $
 */

/**
 * patient.php
 *
 * Subnavbar to the Medical Records tab
 *
 * @author jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  $linkList = array(
    "social" => array(_("Social Data"), "../medical/patient_view.php?key=" . $idPatient),
    //"preventive" => array(_("Datos Preventivos"), ""), // I don't know how implement it
    "history" => array(_("Clinic History"), "../medical/history_list.php?key=" . $idPatient),
    "problems" => array(_("Medical Problems Report"), "../medical/problem_list.php?key=" . $idPatient)
  );

  echo '<ul class="subnavbar">';

  foreach ($linkList as $key => $value)
  {
    echo '<li' . (($nav == $key) ? ' class="selected">' . $value[0] : '>' . HTML::strLink($value[0], $value[1])) . "</li>\n";
  }
  unset($linkList);

  echo ($nav == "print")
    ? '<li class="selected">' . _("Print Medical Record") . '</li>'
    : '<li>' . HTML::strLink(_("Print Medical Record"), '../medical/print_medical_record.php',
        array('key' => $idPatient),
        array('onclick' => "return popSecondary('../medical/print_medical_record.php?key=" . $idPatient . "')")
      ) . '</li>';

  echo "</ul>\n"; // end .subnavbar
?>
