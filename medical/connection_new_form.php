<?php
/**
 * connection_new_form.php
 *
 * Addition screen of a connection between medical problems
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: connection_new_form.php,v 1.22 2007/10/27 14:05:26 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling get vars
   */
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = false;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Problem_Page_Query.php");
  require_once("../lib/Form.php");
  require_once("../lib/misc_lib.php");
  require_once("../medical/PatientInfo.php");
  require_once("../medical/ProblemInfo.php");

  /**
   * Retrieving vars (PGS)
   */
  $idProblem = Check::postGetSessionInt('id_problem');
  $idPatient = Check::postGetSessionInt('id_patient');

  $patient = new PatientInfo($idPatient);
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
  $title = _("Add New Connection Problems");
  $titlePage = $patient->getName() . ' [' . $problem->getWording() . '] (' . $title . ')';
  require_once("../layout/header.php");

  //$returnLocation = "../medical/connection_list.php?id_problem=" . $idProblem . "&id_patient=" . $idPatient;
  $returnLocation = "../medical/connection_list.php";

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    _("Medical Problems Report") => "../medical/problem_list.php", //"?id_patient=" . $idPatient,
    $problem->getWording() => "../medical/problem_view.php",
    _("View Connection Problems") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  $patient->showHeader();
  $problem->showHeader();

  /**
   * Search database
   */
  $problemQ = new Problem_Page_Query();
  $problemQ->connect();

  /**
   * Display no results message if no results returned from search.
   */
  if ( !$problemQ->selectProblems($idPatient) )
  {
    $problemQ->close();
    HTML::message(_("No medical problems defined for this patient."), OPEN_MSG_INFO);
    include_once("../layout/footer.php");
    exit();
  }

  HTML::section(2, _("Medical Problems List:"));

  /**
   * New form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../medical/connection_new.php'));

  Form::hidden("id_problem", $idProblem);
  Form::hidden("id_patient", $idPatient);

  $thead = array(
    _("Order Number"),
    _("Wording")
  );

  $tbody = array();
  while ($problem = $problemQ->fetch())
  {
    $row = $problem->getOrderNumber() . '.';
    $row .= Form::strCheckBox("check[]", $problem->getIdProblem(), false,
      array('id' => numberToAlphabet($problem->getOrderNumber()))
    );
    $row .= OPEN_SEPARATOR;
    $row .= $problem->getWording();

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end while
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);

  $tfoot = array(
    Form::strButton("add", _("Add selected to Connection Problems List"))
    . Form::generateToken()
  );

  $options = array(
    0 => array('align' => 'right'),
    'tfoot' => array('align' => 'center')
  );

  HTML::table($thead, $tbody, $tfoot, $options);
  HTML::end('form');

  require_once("../layout/footer.php");
?>
