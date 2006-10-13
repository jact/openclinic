<?php
/**
 * patient_view.php
 *
 * View patient data screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_view.php,v 1.24 2006/10/13 19:53:16 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || empty($_GET["key"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "social";
  $onlyDoctor = true;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Patient_Page_Query.php");
  require_once("../model/Staff_Query.php");

  /**
   * Retrieving get var
   */
  $idPatient = intval($_GET["key"]);

  /**
   * Search database for patient
   */
  $patQ = new Patient_Page_Query();
  $patQ->connect();

  if ( !$patQ->select($idPatient) )
  {
    $patQ->close();
    include_once("../layout/header.php");

    HTML::message(_("That patient does not exist."), OPEN_MSG_ERROR);

    include_once("../layout/footer.php");
    exit();
  }

  $pat = $patQ->fetch();
  if ( !$pat )
  {
    $patQ->close();
    Error::fetch($patQ);
  }
  $patQ->freeResult();
  $patQ->close();
  unset($patQ);
  $patName = $pat->getFirstName() . " " . $pat->getSurname1() . " " . $pat->getSurname2();

  /**
   * Update session variables
   */
  require_once("../medical/visited_list.php");
  addPatient($pat->getIdPatient(), $patName);

  /**
   * Show page
   */
  $title = _("Social Data");
  require_once("../layout/header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  /**
   * Display insertion message if coming from new with a successful insert.
   */
  if (isset($_GET["added"]))
  {
    HTML::message(_("Patient has been added."), OPEN_MSG_INFO);
  }

  /**
   * Display update message if coming from edit with a successful update.
   */
  if (isset($_GET["updated"]))
  {
    HTML::message(_("Patient has been updated."), OPEN_MSG_INFO);
  }

  $relatedLinks = "";
  if ($hasMedicalAdminAuth)
  {
    $relatedLinks .= HTML::strLink(_("Edit Social Data"), '../medical/patient_edit_form.php',
      array('key' => $idPatient)
    );
    $relatedLinks .= ' | ';
    $relatedLinks .= HTML::strLink(_("Delete Patient"), '../medical/patient_del_confirm.php',
      array(
        'key' => $idPatient,
        'name' => $patName
      )
    );
    $relatedLinks .= ' | ';
  }
  $relatedLinks .= HTML::strLink(_("View Relatives"), '../medical/relative_list.php', array('key' => $idPatient));
  HTML::para($relatedLinks);

  HTML::rule();

  HTML::section(3, _("Patient"));
  HTML::para($pat->getSurname1() . ' ' . $pat->getSurname2() . ', ' . $pat->getFirstName());

  //HTML::section(3, _("Last Update Date"));
  //HTML::para(I18n::localDate($pat->getLastUpdateDate()));

  if ($pat->getNIF())
  {
    HTML::section(3, _("Tax Identification Number (TIN)"));
    HTML::para($pat->getNIF());
  }

  if ($pat->getAddress())
  {
    HTML::section(3, _("Address"));
    HTML::para(nl2br($pat->getAddress()));
  }

  if ($pat->getPhone())
  {
    HTML::section(3, _("Phone Contact"));
    HTML::para(nl2br($pat->getPhone()));
  }

  HTML::section(3, _("Sex"));
  HTML::para(($pat->getSex() == 'V') ? _("Male") : _("Female"));

  if ($pat->getRace())
  {
    HTML::section(3, _("Race"));
    HTML::para($pat->getRace());
  }

  if ($pat->getBirthDate() != "" && $pat->getBirthDate() != "0000-00-00")
  {
    HTML::section(3, _("Birth Date"));
    HTML::para(I18n::localDate($pat->getBirthDate()));

    HTML::section(3, _("Age"));
    HTML::para($pat->getAge());
  }

  if ($pat->getBirthPlace())
  {
    HTML::section(3, _("Birth Place"));
    HTML::para($pat->getBirthPlace());
  }

  if ($pat->getDeceaseDate() != "" && $pat->getDeceaseDate() != "0000-00-00")
  {
    HTML::section(3, _("Decease Date"));
    HTML::para(I18n::localDate($pat->getDeceaseDate()));
  }

  if ($pat->getNTS())
  {
    HTML::section(3, _("Sanitary Card Number (SCN)"));
    HTML::para($pat->getNTS());
  }

  if ($pat->getNSS())
  {
    HTML::section(3, _("National Health Service Number (NHSN)"));
    HTML::para($pat->getNSS());
  }

  if ($pat->getFamilySituation())
  {
    HTML::section(3, _("Family Situation"));
    HTML::para(nl2br($pat->getFamilySituation()));
  }

  if ($pat->getLabourSituation())
  {
    HTML::section(3, _("Labour Situation"));
    HTML::para(nl2br($pat->getLabourSituation()));
  }

  if ($pat->getEducation())
  {
    HTML::section(3, _("Education"));
    HTML::para(nl2br($pat->getEducation()));
  }

  if ($pat->getInsuranceCompany())
  {
    HTML::section(3, _("Insurance Company"));
    HTML::para($pat->getInsuranceCompany());
  }

  if ($pat->getIdMember())
  {
    $staffQ = new Staff_Query();
    $staffQ->connect();

    if ($staffQ->select($pat->getIdMember()))
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

  unset($pat);

  require_once("../layout/footer.php");
?>
