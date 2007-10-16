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
 * @version   CVS: $Id: connection_list.php,v 1.23 2007/10/16 20:19:25 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || empty($_GET["key"]) || empty($_GET["pat"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = true;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Connection_Query.php");
  require_once("../model/Problem_Page_Query.php");
  require_once("../lib/misc_lib.php");

  /**
   * Retrieving get vars
   */
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);

  /**
   * Show page
   */
  $title = _("View Connection Problems");
  require_once("../layout/header.php");
  require_once("../medical/patient_header.php");
  require_once("../medical/problem_header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Medical Problems Report") => "../medical/problem_list.php?key=" . $idPatient,
    _("View Medical Problem") => "../medical/problem_view.php?key=" . $idProblem . "&pat=" . $idPatient,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  showPatientHeader($idPatient);
  showProblemHeader($idProblem);

  if ($hasMedicalAdminAuth)
  {
    HTML::para(
      HTML::strLink(_("Add New Connection Problems"), '../medical/connection_new_form.php',
        array(
          'key' => $idProblem,
          'pat' => $idPatient
        )
      )
    );
  }

  $connQ = new Connection_Query;
  $connQ->connect();

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
    HTML::message(_("No connections defined for this medical problem."), OPEN_MSG_INFO);
    include_once("../layout/footer.php");
    exit();
  }

  HTML::section(2, _("Connection Problems List:"));

  $thead = array(
    _("Function") => array('colspan' => ($hasMedicalAdminAuth ? 2 : 1)),
    _("Opening Date"),
    _("Wording")
  );

  $problemQ = new Problem_Page_Query();
  $problemQ->connect();
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
        'key' => $problem->getIdProblem(),
        'pat' => $idPatient
      )
    );
    $row .= OPEN_SEPARATOR;

    if ($hasMedicalAdminAuth)
    {
      $row .= HTML::strLink(_("del"), '../medical/connection_del_confirm.php',
        array(
          'key' => $idProblem,
          'conn' => $problem->getIdProblem(),
          'pat' => $idPatient,
          'wording' => fieldPreview($problem->getWording())
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
