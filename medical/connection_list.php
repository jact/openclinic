<?php
/**
 * connection_list.php
 *
 * List of defined connection between medical problems screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: connection_list.php,v 1.37 2008/03/23 12:00:17 jact Exp $
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
   * Breadcrumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    _("Medical Problems Report") => "../medical/problem_list.php", //"?id_patient=" . $idPatient,
    $problem->getWordingPreview() => "../medical/problem_view.php",
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_patient");
  unset($links);

  echo $patient->getHeader();
  echo $problem->getHeader();

  if ($_SESSION['auth']['is_administrative'])
  {
    echo HTML::para(
      HTML::link(_("Add New Connection Problems"), '../medical/connection_new_form.php',
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
    echo Msg::info(_("No connections defined for this medical problem."));
    include_once("../layout/footer.php");
    exit();
  }

  echo HTML::section(2, _("Connection Problems List:"));

  $thead = array(
    _("#"),
    _("Function") => array('colspan' => ($_SESSION['auth']['is_administrative'] ? 2 : 1)),
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

    $row .= HTML::link(
      HTML::image('../img/action_view.png', _("view")),
      '../medical/problem_view.php',
      array(
        'id_problem' => $problem->getIdProblem(),
        'id_patient' => $idPatient
      )
    );
    $row .= OPEN_SEPARATOR;

    if ($_SESSION['auth']['is_administrative'])
    {
      $row .= HTML::link(
        HTML::image('../img/action_delete.png', _("delete")),
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

  echo HTML::table($thead, $tbody, null, $options);

  require_once("../layout/footer.php");
?>
