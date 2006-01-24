<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_del_confirm.php,v 1.12 2006/01/24 20:21:17 jact Exp $
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
   * Confirm form
   */
  echo '<form method="post" action="../medical/problem_del.php">' . "\n";

  $tbody = array();

  $tbody[] = HTML::strMessage(sprintf(_("Are you sure you want to delete medical problem, %s, from list?"), $wording), OPEN_MSG_WARNING, false);

  $row = Form::strHidden("id_problem", "id_problem", $idProblem);
  $row .= Form::strHidden("id_patient", "id_patient", $idPatient);
  $row .= Form::strHidden("wording", "wording", $wording);
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("delete", "delete", _("Delete"))
    . Form::strButton("return", "return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array('class' => 'center');

  Form::fieldset($title, $tbody, $tfoot, $options);

  echo "</form>\n";

  require_once("../shared/footer.php");
?>
