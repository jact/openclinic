<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient.php,v 1.7 2005/08/03 17:40:29 jact Exp $
 */

/**
 * patient.php
 *
 * Subnavbar to the Medical Records tab
 *
 * Author: jact <jachavar@gmail.com>
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
    echo '<li' . (($nav == $key) ? ' class="selected">' . $value[0] : '><a href="' . $value[1] . '">' . $value[0] . '</a>') . "</li>\n";
  }
  unset($linkList);

  echo ($nav == "print")
    ? '<li class="selected">' . _("Print Medical Record") . '</li>'
    : '<li><a href="../medical/print_medical_record.php?key=' . $idPatient . '" onclick="return popSecondary(\'../medical/print_medical_record.php?key=' . $idPatient . '\')" onkeypress="return popSecondary(\'../medical/print_medical_record.php?key=' . $idPatient . '\')">' . _("Print Medical Record") . '</a></li>';

  echo "</ul>\n"; // end .subnavbar
?>
