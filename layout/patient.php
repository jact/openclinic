<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient.php,v 1.3 2004/08/09 11:30:53 jact Exp $
 */

/**
 * patient.php
 ********************************************************************
 * Subnavbar to the Medical Records tab
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

  echo '<div class="subnavbar">';

  echo ($nav == "social")
    ? '<span class="selected">' . _("Social Data") . '</span>'
    : '<a href="../medical/patient_view.php?key=' . $idPatient . '">' . _("Social Data") . '</a>';
  echo "<span class='noPrint'> | </span>\n";

  // I don't know how implement it
  /*echo ($nav == "preventive")
    ? '<span class="selected">' . "Datos Preventivos" . '</span>'
    : '<a href="">' . "Datos Preventivos" . '</a>';
  echo "<span class='noPrint'> | </span>\n";*/

  echo ($nav == "history")
    ? '<span class="selected">' . _("Clinic History") . '</span>'
    : '<a href="../medical/history_list.php?key=' . $idPatient . '">' . _("Clinic History") . '</a>';
  echo "<span class='noPrint'> | </span>\n";

  echo ($nav == "problems")
    ? '<span class="selected">' . _("Medical Problems Report") . '</span>'
    : '<a href="../medical/problem_list.php?key=' . $idPatient . '">' . _("Medical Problems Report") . '</a>';
  echo "<span class='noPrint'> | </span>\n";

  echo ($nav == "print")
    ? '<span class="selected">' . _("Print Medical Record") . '</span>'
    : '<a href="../medical/print_medical_record.php?key=' . $idPatient . '" onclick="return popSecondary(\'../medical/print_medical_record.php?key=' . $idPatient . '\')" onkeypress="return popSecondary(\'../medical/print_medical_record.php?key=' . $idPatient . '\')">' . _("Print Medical Record") . '</a>';
  echo "<span class='noPrint'> | </span>\n";

  echo "</div>\n"; // end .subnavbar
?>
