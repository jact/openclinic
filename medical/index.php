<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: index.php,v 1.7 2006/03/24 20:25:43 jact Exp $
 */

/**
 * index.php
 *
 * Summary page of the Medical Records tab
 *
 * @author jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "summary";
  $onlyDoctor = true;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");

  /**
   * Show page
   */
  $title = _("Medical Records");
  require_once("../shared/header.php");

  echo '<h1 class="bigIcon medicalIcon">' . $title . "</h1>\n";
  echo '<p>' . _("Use the following functions located in the left hand navigation area to manage your medical records.") . "</p>\n";

  echo '<h2 class="icon searchIcon">' . HTML::strLink(_("Search Patient"), '../medical/patient_search_form.php') . "</h2>\n";
  echo '<p>' . _("Search and view patients. Once a patient is selected you can:") . "</p>\n";

  echo "<ul>\n";
  echo '<li>' . _("manage social data") . "</li>\n";
  echo '<li>' . _("manage clinic history") . "</li>\n";
  echo '<li>' . _("manage problems report") . "</li>\n";
  echo '<li>' . _("print medical record") . "</li>\n";
  echo "</ul>\n";

  if ($hasMedicalAdminAuth)
  {
    echo "<hr />\n";

    echo '<h2 class="icon patientIcon">' . HTML::strLink(_("New Patient"), '../medical/patient_new_form.php') . "</h2>\n";
    echo '<p>' . _("Build a new patient information in medical records system.") . "</p>\n";
  } // end if

  require_once("../shared/footer.php");
?>
