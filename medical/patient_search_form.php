<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_search_form.php,v 1.9 2005/08/22 15:11:49 jact Exp $
 */

/**
 * patient_search_form.php
 *
 * Search patient screen
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "searchform";
  $onlyDoctor = true;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/Form.php");

  /**
   * Show page
   */
  $title = _("Search Patient");
  $focusFormField = "search_text"; // to avoid JavaScript mistakes in demo version
  require_once("../shared/header.php");

  $headerWording2 = _("Search Patient by Medical Problem");
  $returnLocation = "../medical/index.php";

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon searchIcon");
  unset($links);

  /**
   * Display deletion message if coming from del with a successful delete.
   */
  if (isset($_GET["deleted"]) && isset($_GET["info"]))
  {
    HTML::message(sprintf(_("Patient, %s, has been deleted."), urldecode($_GET["info"])), OPEN_MSG_INFO);
  }

  /**
   * Patient search form
   */
  echo '<form method="post" action="../medical/patient_search.php">' . "\n";
  require_once("../medical/patient_search_fields.php");
  echo "</form>\n";

  echo "<hr />\n";

  /**
   * Problem search form
   */
  echo '<form method="post" action="../medical/problem_search.php">' . "\n";
  require_once("../medical/problem_search_fields.php");
  echo "</form>\n";

  HTML::message('* ' . _("Note: Empty search to see all results."));

  require_once("../shared/footer.php");
?>
