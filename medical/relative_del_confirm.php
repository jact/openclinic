<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: relative_del_confirm.php,v 1.10 2005/08/15 15:11:29 jact Exp $
 */

/**
 * relative_del_confirm.php
 *
 * Confirmation screen of a relation between patients deletion process
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || !is_numeric($_GET["rel"]) || empty($_GET["name"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "social";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");

  /**
   * Retrieving get vars
   */
  $idPatient = intval($_GET["key"]);
  $idRelative = intval($_GET["rel"]);
  $relName = Check::safeText($_GET["name"]);

  /**
   * Show page
   */
  $title = _("Delete Relative from list");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  $returnLocation = "../medical/relative_list.php?key=" . $idPatient; // controlling var

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Social Data") => "../medical/patient_view.php?key=" . $idPatient,
    _("View Relatives") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  showPatientHeader($idPatient);

  /**
   * Form
   */
  echo '<form method="post" action="../medical/relative_del.php">' . "\n";
  echo '<fieldset class="center">';
  echo '<legend>' . $title . "</legend>\n";

  HTML::message(sprintf(_("Are you sure you want to delete relative, %s, from list?"), $relName));

  echo '<p class="formButton">';
  Form::hidden("id_patient", "id_patient", $idPatient);
  Form::hidden("id_relative", "id_relative", $idRelative);
  Form::hidden("name", "name", $relName);

  Form::button("delete", "delete", _("Delete"));
  Form::button("return", "return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"');
  echo "</p>\n";

  echo "</fieldset>\n</form>\n";

  require_once("../shared/footer.php");
?>
