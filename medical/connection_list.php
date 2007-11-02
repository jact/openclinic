<?php
/**
 * connection_list.php
 *
 * List of defined connection between medical problems screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: connection_list.php,v 1.31 2007/11/02 22:21:06 jact Exp $
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
  require_once("../model/Query/Connection.php");
  require_once("../lib/misc_lib.php");
  require_once("../model/Patient.php");
  require_once("../lib/ProblemInfo.php");

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

  $problem = new ProblemInfo($idProblem);
  if ($problem->getWording() == '')
  {
    FlashMsg::add(_("That medical problem does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Show page
   */
  $title = _("View Connection Problems");
  $titlePage = $patient->getName() . ' [' . $problem->getWording() . '] (' . $title . ')';
  require_once("../layout/header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    _("Medical Problems Report") => "../medical/problem_list.php", //"?id_patient=" . $idPatient,
    $problem->getWording() => "../medical/problem_view.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  echo $patient->getHeader();
  $problem->showHeader();

  if ($hasMedicalAdminAuth)
  {
    HTML::para(
      HTML::strLink(_("Add New Connection Problems"), '../medical/connection_new_form.php',
        array(
          'id_problem' => $idProblem,
          'id_patient' => $idPatient
        )
      )
    );
  }

  $connQ = new Query_Connection();

  $connArray = array();
  if ($connQ->select($idProblem))
  {
    while ($conn = $connQ->fetch())
    {
      $connArray[] = $conn[1];
    }
    $connQ->freeResult();
  }
  $connQ->close();
  unset($connQ);

  if (count($connArray) == 0)
  {
    Msg::info(_("No connections defined for this medical problem."));
    include_once("../layout/footer.php");
    exit();
  }

  HTML::section(2, _("Connection Problems List:"));

  $thead = array(
    _("Function") => array('colspan' => ($hasMedicalAdminAuth ? 2 : 1)),
    _("Opening Date"),
    _("Wording")
  );

  $problemQ = new Query_Page_Problem();
  $problemQ->captureError(true);

  $tbody = array();
  for ($i = 0; $i < count($connArray); $i++)
  {
    $problemQ->select($connArray[$i]);
    if ($problemQ->isError())
    {
      Error::query($problemQ, false);
      continue;
    }

    $problem = $problemQ->fetch();
    if ( !$problem )
    {
      $problemQ->close();
      Error::fetch($problemQ);
    }

    $row = HTML::strLink(_("view"), '../medical/problem_view.php',
      array(
        'id_problem' => $problem->getIdProblem(),
        'id_patient' => $idPatient
      )
    );
    $row .= OPEN_SEPARATOR;

    if ($hasMedicalAdminAuth)
    {
      $row .= HTML::strLink(_("del"), '../medical/connection_del_confirm.php',
        array(
          'id_problem' => $idProblem,
          'id_patient' => $idPatient,
          'id_connection' => $problem->getIdProblem()
        )
      );
      $row .= OPEN_SEPARATOR;
    } // end if

    $row .= I18n::localDate($problem->getOpeningDate());
    $row .= OPEN_SEPARATOR;

    $row .= fieldPreview($problem->getWording());

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end for
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);

  HTML::table($thead, $tbody, null);

  require_once("../layout/footer.php");
?>
