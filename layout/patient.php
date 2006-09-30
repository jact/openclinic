<?php
/**
 * patient.php
 *
 * Subnavbar to the Medical Records tab
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient.php,v 1.10 2006/09/30 17:25:17 jact Exp $
 * @author    jact <jachavar@gmail.com>
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

  $array = null;
  foreach ($linkList as $key => $value)
  {
    if ($nav == $key)
    {
      $array[] = array($value[0], array('class' => 'selected'));
    }
    else
    {
      $array[] = HTML::strLink($value[0], $value[1]);
    }
  }
  unset($linkList);

  $array[] = ($nav == "print")
    ? array(_("Print Medical Record"), array('class' => 'selected'))
    : HTML::strLink(_("Print Medical Record"), '../medical/print_medical_record.php',
        array('key' => $idPatient),
        array('onclick' => "return popSecondary('../medical/print_medical_record.php?key=" . $idPatient . "')")
      );

  HTML::itemList($array, array('class' => 'subnavbar'));
?>
