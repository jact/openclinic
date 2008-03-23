<?php
/**
 * index.php
 *
 * Summary page of the Medical Records tab
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: index.php,v 1.16 2008/03/23 12:00:17 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "summary";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_DOCTOR);

  require_once("../lib/LastViewedPatient.php");

  /**
   * Show page
   */
  $title = _("Medical Records");
  require_once("../layout/header.php");

  echo HTML::section(1, $title, array('class' => 'icon icon_medical'));
  echo HTML::para(_("Use the following functions located in the left hand navigation area to manage your medical records."));

  echo HTML::section(2, HTML::link(_("Search Patient"), '../medical/patient_search_form.php'),
    array('class' => 'icon icon_search')
  );
  echo HTML::para(_("Search and view patients. Once a patient is selected you can:"));

  $array = array(
    _("manage social data"),
    _("manage clinic history"),
    _("manage problems report"),
    _("print medical record")
  );
  echo HTML::itemList($array);

  $viewedPatient = LastViewedPatient::get();
  if ($viewedPatient)
  {
    echo HTML::rule();

    echo HTML::section(2, _("Last Viewed Patients"), array('class' => 'icon icon_patient'));

    $array = array();
    foreach ($viewedPatient as $key => $value)
    {
      $array[] = HTML::link($value, '../medical/patient_view.php', array('id_patient' => $key));
    }
    echo HTML::itemList($array);
  }

  if ($_SESSION['auth']['is_administrative'])
  {
    echo HTML::rule();

    echo HTML::section(2, HTML::link(_("New Patient"), '../medical/patient_new_form.php'),
      array('class' => 'icon icon_patient')
    );
    echo HTML::para(_("Build a new patient information in medical records system."));
  } // end if

  require_once("../layout/footer.php");
?>
