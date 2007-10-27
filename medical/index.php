<?php
/**
 * index.php
 *
 * Summary page of the Medical Records tab
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: index.php,v 1.12 2007/10/27 16:12:37 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "summary";
  $onlyDoctor = true;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");

  /**
   * Show page
   */
  $title = _("Medical Records");
  require_once("../layout/header.php");

  HTML::section(1, $title, array('class' => 'bigIcon medicalIcon'));
  HTML::para(_("Use the following functions located in the left hand navigation area to manage your medical records."));

  HTML::section(2, HTML::strLink(_("Search Patient"), '../medical/patient_search_form.php'), array('class' => 'icon searchIcon'));
  HTML::para(_("Search and view patients. Once a patient is selected you can:"));

  $array = array(
    _("manage social data"),
    _("manage clinic history"),
    _("manage problems report"),
    _("print medical record")
  );
  HTML::itemList($array);

  $viewedPatient = LastViewedPatient::get();
  if ($viewedPatient)
  {
    HTML::rule();

    HTML::section(2, _("Last Viewed Patients"), array('class' => 'icon patientIcon'));

    $array = array();
    foreach ($viewedPatient as $key => $value)
    {
      $array[] = HTML::strLink($value, '../medical/patient_view.php', array('id_patient' => $key));
    }
    HTML::itemList($array);
  }

  if (isset($hasMedicalAdminAuth) && $hasMedicalAdminAuth)
  {
    HTML::rule();

    HTML::section(2, HTML::strLink(_("New Patient"), '../medical/patient_new_form.php'), array('class' => 'icon patientIcon'));
    HTML::para(_("Build a new patient information in medical records system."));
  } // end if

  require_once("../layout/footer.php");
?>
