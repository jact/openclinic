<?php
/**
 * problem_view.php
 *
 * View medical problem data screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_view.php,v 1.32 2008/03/23 12:00:17 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "problems";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_DOCTOR);

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
   * Breadcrumb
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
  echo HTML::breadcrumb($links, "icon icon_patient");
  unset($links);

  echo $patient->getHeader();

  $relatedLinks = "";
  if ($_SESSION['auth']['is_administrative'])
  {
    if ($problem->getClosingDate() == "" || $problem->getClosingDate() == '0000-00-00')
    {
      $relatedLinks .= HTML::link(_("Edit Medical Problem Data"), '../medical/problem_edit_form.php',
        array(
          'id_problem' => $idProblem,
          'id_patient' => $idPatient
        )
      );
      $relatedLinks .= ' | ';
    }
    $relatedLinks .= HTML::link(_("Delete Medical Problem"), '../medical/problem_del_confirm.php',
      array(
        'id_problem' => $idProblem,
        'id_patient' => $idPatient
      )
    );
    $relatedLinks .= ' | ';
  }
  $relatedLinks .= HTML::link(_("View Connection Problems"), '../medical/connection_list.php',
    array(
      'id_problem' => $idProblem,
      'id_patient' => $idPatient
    )
  );
  $relatedLinks .= ' | ';
  $relatedLinks .= HTML::link(_("View Medical Tests"), '../medical/test_list.php',
    array(
      'id_problem' => $idProblem,
      'id_patient' => $idPatient
    )
  );
  echo HTML::para($relatedLinks);

  echo HTML::section(2, _("Medical Problem Data"));

  if ($problem->getIdMember())
  {
    $staffQ = new Query_Staff();
    if ($staffQ->select($problem->getIdMember()))
    {
      $staff = $staffQ->fetch();
      if ($staff)
      {
        echo HTML::section(3, _("Attending Physician"));
        echo HTML::para($staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName());
      }
      $staffQ->freeResult();
    }
    $staffQ->close();
    unset($staffQ);
    unset($staff);
  }

  echo HTML::section(3, _("Opening Date"));
  echo HTML::para(I18n::localDate($problem->getOpeningDate()));

  if ($problem->getLastUpdateDate() != "" && $problem->getLastUpdateDate() != "0000-00-00") // backwards compatibility
  {
    echo HTML::section(3, _("Last Update Date"));
    echo HTML::para(I18n::localDate($problem->getLastUpdateDate()));
  }

  if ($problem->getClosingDate() != "" && $problem->getClosingDate() != "0000-00-00")
  {
    echo HTML::section(3, _("Closing Date"));
    echo HTML::para(I18n::localDate($problem->getClosingDate()));
  }

  if ($problem->getMeetingPlace())
  {
    echo HTML::section(3, _("Meeting Place"));
    echo HTML::para($problem->getMeetingPlace());
  }

  echo HTML::section(3, _("Wording"));
  echo HTML::para(nl2br($problem->getWording()));

  if ($problem->getSubjective())
  {
    echo HTML::section(3, _("Subjective"));
    echo HTML::para(nl2br($problem->getSubjective()));
  }

  if ($problem->getObjective())
  {
    echo HTML::section(3, _("Objective"));
    echo HTML::para(nl2br($problem->getObjective()));
  }

  if ($problem->getAppreciation())
  {
    echo HTML::section(3, _("Appreciation"));
    echo HTML::para(nl2br($problem->getAppreciation()));
  }

  if ($problem->getActionPlan())
  {
    echo HTML::section(3, _("Action Plan"));
    echo HTML::para(nl2br($problem->getActionPlan()));
  }

  if ($problem->getPrescription())
  {
    echo HTML::section(3, _("Prescription"));
    echo HTML::para(nl2br($problem->getPrescription()));
  }

  require_once("../layout/footer.php");
?>
