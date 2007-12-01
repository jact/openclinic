<?php
/**
 * problem_view.php
 *
 * View medical problem data screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_view.php,v 1.29 2007/12/01 12:17:38 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = true;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Query/Staff.php");
  require_once("../model/Patient.php");
  require_once("../model/Problem.php");

  /**
   * Retrieving vars (PGS)
   */
  $idProblem = Check::postGetSessionInt('id_problem');
  $idPatient = Check::postGetSessionInt('id_patient');

  $patient = new Patient($idPatient);
  if ($patient->getName() == '')
  {
    FlashMsg::add(_("That patient does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  $problem = new Problem($idProblem);
  if ( !$problem )
  {
    FlashMsg::add(_("That medical problem does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  if ($problem->getClosingDate() != "" && $problem->getClosingDate() != '0000-00-00')
  {
    $nav = "history";
  }

  /**
   * Update session variables
   */
  require_once("../lib/LastViewedPatient.php");
  LastViewedPatient::add($idPatient, $patient->getName());

  /**
   * Show page
   */
  $title = $problem->getWordingPreview(); //_("View Medical Problem");
  $titlePage = $patient->getName() . ' [' . $title . ']';
  require_once("../layout/header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    (($nav == "problems")
      ? _("Medical Problems Report")
      : _("Clinic History")) => (($nav == "problems")
        ? "../medical/problem_list.php"
        : "../medical/history_list.php"),
    $title => ""
  );
  HTML::breadCrumb($links, "icon icon_patient");
  unset($links);

  echo $patient->getHeader();

  $relatedLinks = "";
  if ($hasMedicalAdminAuth)
  {
    if ($problem->getClosingDate() == "" || $problem->getClosingDate() == '0000-00-00')
    {
      $relatedLinks .= HTML::strLink(_("Edit Medical Problem Data"), '../medical/problem_edit_form.php',
        array(
          'id_problem' => $idProblem,
          'id_patient' => $idPatient
        )
      );
      $relatedLinks .= ' | ';
    }
    $relatedLinks .= HTML::strLink(_("Delete Medical Problem"), '../medical/problem_del_confirm.php',
      array(
        'id_problem' => $idProblem,
        'id_patient' => $idPatient
      )
    );
    $relatedLinks .= ' | ';
  }
  $relatedLinks .= HTML::strLink(_("View Connection Problems"), '../medical/connection_list.php',
    array(
      'id_problem' => $idProblem,
      'id_patient' => $idPatient
    )
  );
  $relatedLinks .= ' | ';
  $relatedLinks .= HTML::strLink(_("View Medical Tests"), '../medical/test_list.php',
    array(
      'id_problem' => $idProblem,
      'id_patient' => $idPatient
    )
  );
  HTML::para($relatedLinks);

  HTML::section(2, _("Medical Problem Data"));

  if ($problem->getIdMember())
  {
    $staffQ = new Query_Staff();
    if ($staffQ->select($problem->getIdMember()))
    {
      $staff = $staffQ->fetch();
      if ($staff)
      {
        HTML::section(3, _("Attending Physician"));
        HTML::para($staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName());
      }
      $staffQ->freeResult();
    }
    $staffQ->close();
    unset($staffQ);
    unset($staff);
  }

  HTML::section(3, _("Opening Date"));
  HTML::para(I18n::localDate($problem->getOpeningDate()));

  if ($problem->getLastUpdateDate() != "" && $problem->getLastUpdateDate() != "0000-00-00") // backwards compatibility
  {
    HTML::section(3, _("Last Update Date"));
    HTML::para(I18n::localDate($problem->getLastUpdateDate()));
  }

  if ($problem->getClosingDate() != "" && $problem->getClosingDate() != "0000-00-00")
  {
    HTML::section(3, _("Closing Date"));
    HTML::para(I18n::localDate($problem->getClosingDate()));
  }

  if ($problem->getMeetingPlace())
  {
    HTML::section(3, _("Meeting Place"));
    HTML::para($problem->getMeetingPlace());
  }

  HTML::section(3, _("Wording"));
  HTML::para(nl2br($problem->getWording()));

  if ($problem->getSubjective())
  {
    HTML::section(3, _("Subjective"));
    HTML::para(nl2br($problem->getSubjective()));
  }

  if ($problem->getObjective())
  {
    HTML::section(3, _("Objective"));
    HTML::para(nl2br($problem->getObjective()));
  }

  if ($problem->getAppreciation())
  {
    HTML::section(3, _("Appreciation"));
    HTML::para(nl2br($problem->getAppreciation()));
  }

  if ($problem->getActionPlan())
  {
    HTML::section(3, _("Action Plan"));
    HTML::para(nl2br($problem->getActionPlan()));
  }

  if ($problem->getPrescription())
  {
    HTML::section(3, _("Prescription"));
    HTML::para(nl2br($problem->getPrescription()));
  }

  require_once("../layout/footer.php");
?>
