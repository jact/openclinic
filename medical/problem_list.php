<?php
/**
 * problem_list.php
 *
 * Medical problems screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_list.php,v 1.35 2008/03/23 12:00:17 jact Exp $
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

  require_once("../model/Query/Page/Problem.php");
  require_once("../model/Patient.php");

  /**
   * Retrieving vars (PGS)
   */
  $idPatient = Check::postGetSessionInt('id_patient');

  $patient = new Patient($idPatient);
  if ($patient->getName() == '')
  {
    FlashMsg::add(_("That patient does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Show page
   */
  $title = _("Medical Problems Report");
  $titlePage = $patient->getName() . ' (' . $title . ')';
  require_once("../layout/header.php");

  /**
   * Breadcrumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_patient");
  unset($links);

  echo $patient->getHeader();

  $problemQ = new Query_Page_Problem();
  $lastOrderNumber = $problemQ->getLastOrderNumber($idPatient);

  if ($_SESSION['auth']['is_administrative'])
  {
    echo HTML::para(
      HTML::link(_("Add New Medical Problem"), '../medical/problem_new_form.php',
        array(
          'id_patient' => $idPatient,
          'order_number' => $lastOrderNumber
        )
      )
    );
  }

  echo HTML::rule();

  echo HTML::section(2, _("Medical Problems List:"));

  if ( !$problemQ->selectProblems($idPatient) )
  {
    $problemQ->close();

    echo Msg::info(_("No medical problems defined for this patient."));
    include_once("../layout/footer.php");
    exit();
  }

  $thead = array(
    _("Order Number"),
    _("Function") => array('colspan' => ($_SESSION['auth']['is_administrative'] ? 5 : 3)),
    _("Wording"),
    _("Opening Date"),
    _("Last Update Date")
  );

  $options = array(
    0 => array('align' => 'right')
  );

  $tbody = array();
  while ($problem = $problemQ->fetch())
  {
    $row = $problem->getOrderNumber();
    $row .= OPEN_SEPARATOR;

    if ($_SESSION['auth']['is_administrative'])
    {
      $row .= HTML::link(
        HTML::image('../img/action_edit.png', _("edit")),
        '../medical/problem_edit_form.php',
        array(
          'id_problem' => $problem->getIdProblem(),
          'id_patient' => $problem->getIdPatient()
        )
      );
      $row .= OPEN_SEPARATOR;

      $row .= HTML::link(
        HTML::image('../img/action_delete.png', _("delete")),
        '../medical/problem_del_confirm.php',
        array(
          'id_problem' => $problem->getIdProblem(),
          'id_patient' => $problem->getIdPatient()
        )
      );
      $row .= OPEN_SEPARATOR;
    } // end if

    $row .= HTML::link(
      HTML::image('../img/action_view.png', _("view")),
      '../medical/problem_view.php',
      array(
        'id_problem' => $problem->getIdProblem(),
        'id_patient' => $problem->getIdPatient()
      )
    );
    $row .= OPEN_SEPARATOR;

    $row .= HTML::link(
      HTML::image('../img/action_test.png', _("tests")),
      '../medical/test_list.php',
      array(
        'id_problem' => $problem->getIdProblem(),
        'id_patient' => $problem->getIdPatient()
      )
    );
    $row .= OPEN_SEPARATOR;

    $row .= HTML::link(
      HTML::image('../img/action_connection.png', _("connect")),
      '../medical/connection_list.php',
      array(
        'id_problem' => $problem->getIdProblem(),
        'id_patient' => $problem->getIdPatient()
      )
    );
    $row .= OPEN_SEPARATOR;

    $row .= $problem->getWordingPreview();
    $row .= OPEN_SEPARATOR;

    $row .= I18n::localDate($problem->getOpeningDate());
    $row .= OPEN_SEPARATOR;

    $row .= I18n::localDate($problem->getLastUpdateDate());

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end while
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);

  echo HTML::table($thead, $tbody, null, $options);

  require_once("../layout/footer.php");
?>
