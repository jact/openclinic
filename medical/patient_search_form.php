<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_search_form.php,v 1.4 2004/07/10 16:44:57 jact Exp $
 */

/**
 * patient_search_form.php
 ********************************************************************
 * Search patient screen
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "searchform";
  $onlyDoctor = true;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");

  // after login_check inclusion to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "search_text";

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Search Patient");
  require_once("../shared/header.php");

  $headerWording2 = _("Search Patient by Medical Problem");
  $returnLocation = "../medical/index.php";

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "search.png");
  unset($links);

  ////////////////////////////////////////////////////////////////////
  // Display deletion message if coming from del with a successful delete.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["deleted"]) && isset($_GET["info"]))
  {
    echo '<p>' . sprintf(_("Patient, %s, has been deleted."), urldecode($_GET["info"])) . "</p>\n";
  }
?>

<form method="post" action="../medical/patient_search.php">
<?php require_once("../medical/patient_search_fields.php"); ?>
</form>

<form method="post" action="../medical/problem_search.php">
<?php require_once("../medical/problem_search_fields.php"); ?>
</form>

<?php
  echo '<p class="advice center">* ' . _("Note: Empty search to see all results.") . "</p>\n";

  require_once("../shared/footer.php");
?>
