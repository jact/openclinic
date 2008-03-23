<?php
/**
 * patient_view.php
 *
 * View patient data screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_view.php,v 1.35 2008/03/23 12:00:17 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "social";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_DOCTOR);

  require_once("../model/Patient.php");
  require_once("../model/Query/Staff.php");

  /**
   * Retrieving var (PGS)
   */
  $idPatient = Check::postGetSessionInt('id_patient');

  $patient = new Patient($idPatient);
  $patName = $patient->getName();
  if ($patName == '')
  {
    FlashMsg::add(_("That patient does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Update session variables
   */
  require_once("../lib/LastViewedPatient.php");
  LastViewedPatient::add($patient->getIdPatient(), $patName);

  /**
   * Show page
   */
  $title = $patName; //_("Social Data");
  require_once("../layout/header.php");

  /**
   * Breadcrumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_patient");
  unset($links);

  echo HTML::section(2, _("Social Data"));

  $relatedLinks = "";
  if ($_SESSION['auth']['is_administrative'])
  {
    $relatedLinks .= HTML::link(_("Edit Social Data"), '../medical/patient_edit_form.php',
      array('id_patient' => $idPatient)
    );
    $relatedLinks .= ' | ';
    $relatedLinks .= HTML::link(_("Delete Patient"), '../medical/patient_del_confirm.php',
      array('id_patient' => $idPatient)
    );
  }
  echo HTML::para($relatedLinks);

  $relatedLinks = HTML::link(_("View Relatives"), '../medical/relative_list.php',
    array('id_patient' => $idPatient)
  );
  $relatedLinks .= ' | ';
  $relatedLinks .= HTML::link(_("Clinic History"), '../medical/history_list.php',
    array('id_patient' => $idPatient)
  );
  $relatedLinks .= ' | ';
  $relatedLinks .= HTML::link(_("Medical Problems Report"), '../medical/problem_list.php',
    array('id_patient' => $idPatient)
  );
  $relatedLinks .= ' | ';
  $relatedLinks .= HTML::link(_("Print Medical Record"), '../medical/print_medical_record.php',
    array('id_patient' => $idPatient),
    array('class' => 'popup')
  );
  echo HTML::para($relatedLinks);

  echo HTML::rule();

  echo HTML::section(3, _("Patient"));
  echo HTML::para($patient->getSurname1() . ' ' . $patient->getSurname2() . ', ' . $patient->getFirstName());

  //echo HTML::section(3, _("Last Update Date"));
  //echo HTML::para(I18n::localDate($patient->getLastUpdateDate()));

  if ($patient->getNIF())
  {
    echo HTML::section(3, _("Tax Identification Number (TIN)"));
    echo HTML::para($patient->getNIF());
  }

  if ($patient->getAddress())
  {
    echo HTML::section(3, _("Address"));
    echo HTML::para(nl2br($patient->getAddress()));
  }

  if ($patient->getPhone())
  {
    echo HTML::section(3, _("Phone Contact"));
    echo HTML::para(nl2br($patient->getPhone()));
  }

  echo HTML::section(3, _("Sex"));
  echo HTML::para(($patient->getSex() == 'V') ? _("Male") : _("Female"));

  if ($patient->getRace())
  {
    echo HTML::section(3, _("Race"));
    echo HTML::para($patient->getRace());
  }

  if ($patient->getBirthDate() != "" && $patient->getBirthDate() != "0000-00-00")
  {
    echo HTML::section(3, _("Birth Date"));
    echo HTML::para(I18n::localDate($patient->getBirthDate()));

    echo HTML::section(3, _("Age"));
    echo HTML::para($patient->getAge());
  }

  if ($patient->getBirthPlace())
  {
    echo HTML::section(3, _("Birth Place"));
    echo HTML::para($patient->getBirthPlace());
  }

  if ($patient->getDeceaseDate() != "" && $patient->getDeceaseDate() != "0000-00-00")
  {
    echo HTML::section(3, _("Decease Date"));
    echo HTML::para(I18n::localDate($patient->getDeceaseDate()));
  }

  if ($patient->getNTS())
  {
    echo HTML::section(3, _("Sanitary Card Number (SCN)"));
    echo HTML::para($patient->getNTS());
  }

  if ($patient->getNSS())
  {
    echo HTML::section(3, _("National Health Service Number (NHSN)"));
    echo HTML::para($patient->getNSS());
  }

  if ($patient->getFamilySituation())
  {
    echo HTML::section(3, _("Family Situation"));
    echo HTML::para(nl2br($patient->getFamilySituation()));
  }

  if ($patient->getLabourSituation())
  {
    echo HTML::section(3, _("Labour Situation"));
    echo HTML::para(nl2br($patient->getLabourSituation()));
  }

  if ($patient->getEducation())
  {
    echo HTML::section(3, _("Education"));
    echo HTML::para(nl2br($patient->getEducation()));
  }

  if ($patient->getInsuranceCompany())
  {
    echo HTML::section(3, _("Insurance Company"));
    echo HTML::para($patient->getInsuranceCompany());
  }

  if ($patient->getIdMember())
  {
    $staffQ = new Query_Staff();
    if ($staffQ->select($patient->getIdMember()))
    {
      $staff = $staffQ->fetch();
      if ($staff)
      {
        echo HTML::section(3, _("Doctor you are assigned to"));
        echo HTML::para($staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName());
      }
      $staffQ->freeResult();
    }
    $staffQ->close();
    unset($staffQ);
    unset($staff);
  }

  unset($patient);

  require_once("../layout/footer.php");
?>
