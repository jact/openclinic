<?php
/**
 * history_list.php
 *
 * Closed medical problems screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: history_list.php,v 1.29 2007/12/07 16:51:44 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "history";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE);

  require_once("../model/Query/Page/Problem.php");
  require_once("../model/Patient.php");

  /**
   * Retrieving var (PGS)
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
  $title = _("Clinic History");
  $titlePage = $patient->getName() . ' (' . $title . ')';
  require_once("../layout/header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon icon_patient");
  unset($links);

  echo $patient->getHeader();

  HTML::para(
    HTML::strLink(_("View Personal Antecedents"), '../medical/history_personal_view.php',
      array('id_patient' => $idPatient)
    )
    . ' | '
    . HTML::strLink(_("View Family Antecedents"), '../medical/history_family_view.php',
      array('id_patient' => $idPatient)
    )
  );

  HTML::rule();

  HTML::section(2, _("Closed Medical Problems List:"));

  $problemQ = new Query_Page_Problem();
  if ( !$problemQ->selectProblems($idPatient, true) )
  {
    $problemQ->close();

    Msg::info(_("No closed medical problems defined for this patient."));
    include_once("../layout/footer.php");
    exit();
  }

  $thead = array(
    _("Order Number"),
    _("Function") => array('colspan' => ($_SESSION['auth']['is_medical_doctor'] ? 4 : 3)),
    _("Wording"),
    _("Opening Date"),
    _("Closing Date")
  );

  $options = array(
    0 => array('align' => 'right')
  );

  $tbody = array();
  while ($problem = $problemQ->fetch())
  {
    $row = $problem->getOrderNumber();
    $row .= OPEN_SEPARATOR;

    // a closed medical problem is not editable

    $row .= HTML::strLink(
      HTML::strImage('../img/action_view.png', _("view")),
      '../medical/problem_view.php',
      array(
        'id_problem' => $problem->getIdProblem(),
        'id_patient' => $problem->getIdPatient()
      )
    );
    $row .= OPEN_SEPARATOR;

    if ($_SESSION['auth']['is_medical_doctor'])
    {
      $row .= HTML::strLink(
        HTML::strImage('../img/action_delete.png', _("delete")),
        '../medical/problem_del_confirm.php',
        array(
          'id_problem' => $problem->getIdProblem(),
          'id_patient' => $problem->getIdPatient()
        )
      );
      $row .= OPEN_SEPARATOR;
    } // end if

    $row .= HTML::strLink(
      HTML::strImage('../img/action_test.png', _("tests")),
      '../medical/test_list.php',
      array(
        'id_problem' => $problem->getIdProblem(),
        'id_patient' => $problem->getIdPatient()
      )
    );
    $row .= OPEN_SEPARATOR;

    $row .= HTML::strLink(
      HTML::strImage('../img/action_connection.png', _("connect")),
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

    $row .= I18n::localDate($problem->getClosingDate());

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end while
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);

  HTML::table($thead, $tbody, null, $options);

  require_once("../layout/footer.php");
?>
