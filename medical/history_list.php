<?php
/**
 * history_list.php
 *
 * Closed medical problems screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: history_list.php,v 1.19 2006/10/13 19:53:16 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "history";
  $onlyDoctor = true;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Problem_Page_Query.php");
  require_once("../lib/misc_lib.php");

  /**
   * Retrieving get var
   */
  $idPatient = intval($_GET["key"]);

  /**
   * Show page
   */
  $title = _("Clinic History");
  require_once("../layout/header.php");
  require_once("../medical/patient_header.php");

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

  if ( !showPatientHeader($idPatient) )
  {
    $problemQ->close();
    HTML::message(_("That patient does not exist."), OPEN_MSG_ERROR);

    include_once("../layout/footer.php");
    exit();
  }

  HTML::para(
    HTML::strLink(_("View Personal Antecedents"), '../medical/history_personal_view.php', array('key' => $idPatient))
    . ' | '
    . HTML::strLink(_("View Family Antecedents"), '../medical/history_family_view.php', array('key' => $idPatient))
  );

  HTML::rule();

  HTML::section(2, _("Closed Medical Problems List:"));

  $problemQ = new Problem_Page_Query();
  $problemQ->connect();

  if ( !$problemQ->selectProblems($idPatient, true) )
  {
    $problemQ->close();
    HTML::message(_("No closed medical problems defined for this patient."), OPEN_MSG_INFO);
    include_once("../layout/footer.php");
    exit();
  }

  $thead = array(
    _("Order Number"),
    _("Function") => array('colspan' => ($hasMedicalAdminAuth ? 4 : 3)),
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

    $row .= HTML::strLink(_("view"), '../medical/problem_view.php',
      array(
        'key' => $problem->getIdProblem(),
        'pat' => $problem->getIdPatient()
      )
    );
    $row .= OPEN_SEPARATOR;

    if ($hasMedicalAdminAuth)
    {
      $row .= HTML::strLink(_("del"), '../medical/problem_del_confirm.php',
        array(
          'key' => $problem->getIdProblem(),
          'pat' => $problem->getIdPatient(),
          'wording' => fieldPreview($problem->getWording())
        )
      );
      $row .= OPEN_SEPARATOR;
    } // end if

    $row .= HTML::strLink(_("tests"), '../medical/test_list.php',
      array(
        'key' => $problem->getIdProblem(),
        'pat' => $problem->getIdPatient()
      )
    );
    $row .= OPEN_SEPARATOR;

    $row .= HTML::strLink(_("connect"), '../medical/connection_list.php',
      array(
        'key' => $problem->getIdProblem(),
        'pat' => $problem->getIdPatient()
      )
    );
    $row .= OPEN_SEPARATOR;

    $row .= fieldPreview($problem->getWording());
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
