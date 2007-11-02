<?php
/**
 * patient_view.php
 *
 * View patient data screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_view.php,v 1.30 2007/11/02 20:42:10 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "social";
  $onlyDoctor = true;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/PatientInfo.php");
  require_once("../model/Query/Staff.php");

  /**
   * Retrieving var (PGS)
   */
  $idPatient = Check::postGetSessionInt('id_patient');

  $patient = new PatientInfo($idPatient);
  $patName = $patient->getName();
  $patient = $patient->getObject();
  if ($patient == null)
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
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  HTML::section(2, _("Social Data"));

  $relatedLinks = "";
  if ($hasMedicalAdminAuth)
  {
    $relatedLinks .= HTML::strLink(_("Edit Social Data"), '../medical/patient_edit_form.php',
      array('id_patient' => $idPatient)
    );
    $relatedLinks .= ' | ';
    $relatedLinks .= HTML::strLink(_("Delete Patient"), '../medical/patient_del_confirm.php',
      array('id_patient' => $idPatient)
    );
  }
  HTML::para($relatedLinks);

  $relatedLinks = HTML::strLink(_("View Relatives"), '../medical/relative_list.php',
    array('id_patient' => $idPatient)
  );
  $relatedLinks .= ' | ';
  $relatedLinks .= HTML::strLink(_("Clinic History"), '../medical/history_list.php',
    array('id_patient' => $idPatient)
  );
  $relatedLinks .= ' | ';
  $relatedLinks .= HTML::strLink(_("Medical Records"), '../medical/problem_list.php',
    array('id_patient' => $idPatient)
  );
  $relatedLinks .= ' | ';
  $relatedLinks .= HTML::strLink(_("Print Medical Record"), '../medical/print_medical_record.php',
    array('id_patient' => $idPatient),
    array('class' => 'popup')
  );
  HTML::para($relatedLinks);

  HTML::rule();

  HTML::section(3, _("Patient"));
  HTML::para($patient->getSurname1() . ' ' . $patient->getSurname2() . ', ' . $patient->getFirstName());

  //HTML::section(3, _("Last Update Date"));
  //HTML::para(I18n::localDate($patient->getLastUpdateDate()));

  if ($patient->getNIF())
  {
    HTML::section(3, _("Tax Identification Number (TIN)"));
    HTML::para($patient->getNIF());
  }

  if ($patient->getAddress())
  {
    HTML::section(3, _("Address"));
    HTML::para(nl2br($patient->getAddress()));
  }

  if ($patient->getPhone())
  {
    HTML::section(3, _("Phone Contact"));
    HTML::para(nl2br($patient->getPhone()));
  }

  HTML::section(3, _("Sex"));
  HTML::para(($patient->getSex() == 'V') ? _("Male") : _("Female"));

  if ($patient->getRace())
  {
    HTML::section(3, _("Race"));
    HTML::para($patient->getRace());
  }

  if ($patient->getBirthDate() != "" && $patient->getBirthDate() != "0000-00-00")
  {
    HTML::section(3, _("Birth Date"));
    HTML::para(I18n::localDate($patient->getBirthDate()));

    HTML::section(3, _("Age"));
    HTML::para($patient->getAge());
  }

  if ($patient->getBirthPlace())
  {
    HTML::section(3, _("Birth Place"));
    HTML::para($patient->getBirthPlace());
  }

  if ($patient->getDeceaseDate() != "" && $patient->getDeceaseDate() != "0000-00-00")
  {
    HTML::section(3, _("Decease Date"));
    HTML::para(I18n::localDate($patient->getDeceaseDate()));
  }

  if ($patient->getNTS())
  {
    HTML::section(3, _("Sanitary Card Number (SCN)"));
    HTML::para($patient->getNTS());
  }

  if ($patient->getNSS())
  {
    HTML::section(3, _("National Health Service Number (NHSN)"));
    HTML::para($patient->getNSS());
  }

  if ($patient->getFamilySituation())
  {
    HTML::section(3, _("Family Situation"));
    HTML::para(nl2br($patient->getFamilySituation()));
  }

  if ($patient->getLabourSituation())
  {
    HTML::section(3, _("Labour Situation"));
    HTML::para(nl2br($patient->getLabourSituation()));
  }

  if ($patient->getEducation())
  {
    HTML::section(3, _("Education"));
    HTML::para(nl2br($patient->getEducation()));
  }

  if ($patient->getInsuranceCompany())
  {
    HTML::section(3, _("Insurance Company"));
    HTML::para($patient->getInsuranceCompany());
  }

  if ($patient->getIdMember())
  {
    $staffQ = new Query_Staff();
    if ($staffQ->select($patient->getIdMember()))
    {
      $staff = $staffQ->fetch();
      if ($staff)
      {
        HTML::section(3, _("Doctor you are assigned to"));
        HTML::para($staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName());
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
