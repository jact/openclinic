<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_del_confirm.php,v 1.10 2005/08/15 15:11:29 jact Exp $
 */

/**
 * problem_del_confirm.php
 *
 * Confirmation screen of a medical problem deletion process
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Checking for query string. Go back to form if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || !is_numeric($_GET["pat"]) || empty($_GET["wording"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");

  /**
   * Retrieving get vars
   */
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);
  $wording = Check::safeText($_GET["wording"]);

  /**
   * Show page
   */
  $title = _("Delete Medical Problem");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  $returnLocation = "../medical/problem_list.php?key=" . $idPatient; // controlling var

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Medical Problems Report") => $returnLocation,
    _("View Medical Problem") => "../medical/problem_view.php?key=" . $idProblem . "&amp;pat=" . $idPatient,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  showPatientHeader($idPatient);

  /**
   * Form
   */
  echo '<form method="post" action="../medical/problem_del.php">' . "\n";
  echo '<fieldset class="center">';
  echo '<legend>' . $title . "</legend>\n";

  HTML::message(sprintf(_("Are you sure you want to delete medical problem, %s, from list?"), $wording));

  echo '<p class="formButton">';
  Form::hidden("id_problem", "id_problem", $idProblem);
  Form::hidden("id_patient", "id_patient", $idPatient);
  Form::hidden("wording", "wording", $wording);

  Form::button("delete", "delete", _("Delete"));
  Form::button("return", "return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"');
  echo "</p>\n";

  echo "</fieldset>\n</form>\n";

  require_once("../shared/footer.php");
?>
