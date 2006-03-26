<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: connection_new_form.php,v 1.15 2006/03/26 15:13:08 jact Exp $
 */

/**
 * connection_new_form.php
 *
 * Addition screen of a connection between medical problems
 *
 * @author jact <jachavar@gmail.com>
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
   * Controlling get vars
   */
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Problem_Page_Query.php");
  require_once("../lib/Form.php");
  require_once("../lib/misc_lib.php");

  /**
   * Retrieving get vars
   */
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);

  /**
   * Show page
   */
  $title = _("Add New Connection Problems");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");
  require_once("../medical/problem_header.php");

  $returnLocation = "../medical/connection_list.php?key=" . $idProblem . "&amp;pat=" . $idPatient;

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Medical Problems Report") => "../medical/problem_list.php?key=" . $idPatient,
    _("View Medical Problem") => "../medical/problem_view.php?key=" . $idProblem . "&amp;pat=" . $idPatient,
    _("View Connection Problems") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  showPatientHeader($idPatient);
  showProblemHeader($idProblem);

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
    include_once("../shared/footer.php");
    exit();
  }

  echo '<h2>' . _("Medical Problems List:") . "</h2>\n";

  /**
   * New form
   */
  echo '<form method="post" action="../medical/connection_new.php">' . "\n";

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
    Form::strButton("button1", _("Add selected to Connection Problems List"))
  );

  $options = array(
    0 => array('align' => 'right'),
    'tfoot' => array('align' => 'center')
  );

  HTML::table($thead, $tbody, $tfoot, $options);
  echo "</form>\n";

  require_once("../shared/footer.php");
?>
