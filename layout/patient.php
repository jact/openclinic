<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient.php,v 1.2 2004/04/18 14:30:07 jact Exp $
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

  echo '<span class="subnavbar';
  echo ($nav == "social")
    ? ' selected">' . _("Social Data")
    : '"><a href="../medical/patient_view.php?key=' . $idPatient . '">' . _("Social Data") . '</a>';
  echo "</span><span class='noPrint'> | </span>\n";

  // I don't know how implement it
  /*echo '<span class="subnavbar';
  echo ($nav == "preventive")
    ? ' selected">' . "Datos Preventivos"
    : '"><a href="">' . "Datos Preventivos" . '</a>';
  echo "</span><span class='noPrint'> | </span>\n";*/

  echo '<span class="subnavbar';
  echo ($nav == "history")
    ? ' selected">' . _("Clinic History")
    : '"><a href="../medical/history_list.php?key=' . $idPatient . '">' . _("Clinic History") . '</a>';
  echo "</span><span class='noPrint'> | </span>\n";

  echo '<span class="subnavbar';
  echo ($nav == "problems")
    ? ' selected">' . _("Medical Problems Report")
    : '"><a href="../medical/problem_list.php?key=' . $idPatient . '">' . _("Medical Problems Report") . '</a>';
  echo "</span><span class='noPrint'> | </span>\n";

  echo '<span class="subnavbar';
  echo ($nav == "print")
    ? ' selected">' . _("Print Medical Record")
    : '"><a href="../medical/print_medical_record.php?key=' . $idPatient . '" onclick="return popSecondary(\'../medical/print_medical_record.php?key=' . $idPatient . '\')" onkeypress="return popSecondary(\'../medical/print_medical_record.php?key=' . $idPatient . '\')">' . _("Print Medical Record") . '</a>';
  echo "</span><span class='noPrint'> | </span>\n";
?>
