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
 * @version   CVS: $Id: connection_list.php,v 1.35 2007/12/07 16:51:44 jact Exp $
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
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE);

  require_once("../model/Query/Connection.php");
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

  /**
   * Show page
   */
  $title = _("View Connection Problems");
  $titlePage = $patient->getName() . ' [' . $problem->getWordingPreview() . '] (' . $title . ')';
  require_once("../layout/header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    _("Medical Problems Report") => "../medical/problem_list.php", //"?id_patient=" . $idPatient,
    $problem->getWordingPreview() => "../medical/problem_view.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon icon_patient");
  unset($links);

  echo $patient->getHeader();
  echo $problem->getHeader();

  if ($_SESSION['auth']['is_medical_doctor'])
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
    _("#"),
    _("Function") => array('colspan' => ($_SESSION['auth']['is_medical_doctor'] ? 2 : 1)),
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

    $row = $i + 1 . '.';
    $row .= OPEN_SEPARATOR;

    $row .= HTML::strLink(
      HTML::strImage('../img/action_view.png', _("view")),
      '../medical/problem_view.php',
      array(
        'id_problem' => $problem->getIdProblem(),
        'id_patient' => $idPatient
      )
    );
    $row .= OPEN_SEPARATOR;

    if ($_SESSION['auth']['is_medical_doctor'])
    {
      $row .= HTML::strLink(
        HTML::strImage('../img/action_delete.png', _("delete")),
        '../medical/connection_del_confirm.php',
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

    $row .= $problem->getWordingPreview();

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end for
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);

  $options = array(
    0 => array('align' => 'right')
  );

  HTML::table($thead, $tbody, null, $options);

  require_once("../layout/footer.php");
?>
