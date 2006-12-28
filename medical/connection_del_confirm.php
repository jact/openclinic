<?php
/**
 * connection_del_confirm.php
 *
 * Confirmation screen of a connection between medical problems deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: connection_del_confirm.php,v 1.19 2006/12/28 16:24:32 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || !is_numeric($_GET["conn"]) || !is_numeric($_GET["pat"]) || empty($_GET["wording"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = false;

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");

  /**
   * Retrieving get vars
   */
  $idProblem = intval($_GET["key"]);
  $idConnection = intval($_GET["conn"]);
  $idPatient = intval($_GET["pat"]);
  $wording = Check::safeText($_GET["wording"]);

  /**
   * Show page
   */
  $title = _("Delete Connection with Medical Problem");
  require_once("../layout/header.php");
  require_once("../medical/patient_header.php");
  require_once("../medical/problem_header.php");

  $returnLocation = "../medical/connection_list.php?key=" . $idProblem . "&pat=" . $idPatient; // controlling var

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Medical Problems Report") => "../medical/problem_list.php?key=" . $idPatient,
    _("View Medical Problem") => "../medical/problem_view.php?key=" . $idProblem . "&pat=" . $idPatient,
    _("View Connection Problems") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  showPatientHeader($idPatient);
  showProblemHeader($idProblem);

  /**
   * Form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../medical/connection_del.php'));

  $tbody = array();

  $tbody[] = HTML::strMessage(sprintf(_("Are you sure you want to delete connection, %s, from list?"), $wording), OPEN_MSG_WARNING, false);

  $row = Form::strHidden("id_problem", $idProblem);
  $row .= Form::strHidden("id_connection", $idConnection);
  $row .= Form::strHidden("id_patient", $idPatient);
  $row .= Form::strHidden("wording", $wording);
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("delete", _("Delete"))
    . Form::generateToken()
  );

  $options = array('class' => 'center');

  Form::fieldset($title, $tbody, $tfoot, $options);

  HTML::end('form');

  HTML::para(HTML::strLink(_("Return"), $returnLocation));

  require_once("../layout/footer.php");
?>
