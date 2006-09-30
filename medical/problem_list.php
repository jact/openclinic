<?php
/**
 * problem_list.php
 *
 * Medical problems screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_list.php,v 1.21 2006/09/30 17:19:23 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || empty($_GET["key"]))
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

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Problem_Page_Query.php");
  require_once("../lib/misc_lib.php");

  /**
   * Retrieving get vars
   */
  $idPatient = intval($_GET["key"]);
  $info = (isset($_GET["info"]) ? urldecode(Check::safeText($_GET["info"])) : "");

  /**
   * Show page
   */
  $title = _("Medical Problems Report");
  require_once("../shared/header.php");
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

    include_once("../shared/footer.php");
    exit();
  }

  /**
   * Display insertion message if coming from new with a successful insert.
   */
  if (isset($_GET["added"]) && !empty($info))
  {
    if (isset($_GET["closed"]) && $_GET["closed"])
    {
      HTML::message(sprintf(_("Medical problem, %s, has been added to closed medical problems list."), $info), OPEN_MSG_INFO);
    }
    else
    {
      HTML::message(sprintf(_("Medical problem, %s, has been added."), $info), OPEN_MSG_INFO);
    }
  }

  /**
   * Display update message if coming from edit with a successful update.
   */
  if (isset($_GET["updated"]) && !empty($info))
  {
    if (isset($_GET["closed"]) && $_GET["closed"])
    {
      HTML::message(sprintf(_("Medical problem, %s, has been added to closed medical problems list."), $info), OPEN_MSG_INFO);
    }
    else
    {
      HTML::message(sprintf(_("Medical problem, %s, has been updated."), $info), OPEN_MSG_INFO);
    }
  }

  /**
   * Display deletion message if coming from del with a successful delete.
   */
  if (isset($_GET["deleted"]) && !empty($info))
  {
    HTML::message(sprintf(_("Medical problem, %s, has been deleted."), $info), OPEN_MSG_INFO);
  }

  $problemQ = new Problem_Page_Query();
  $problemQ->connect();

  $lastOrderNumber = $problemQ->getLastOrderNumber($idPatient);

  if ($hasMedicalAdminAuth)
  {
    HTML::para(
      HTML::strLink(_("Add New Medical Problem"), '../medical/problem_new_form.php',
        array(
          'key' => $idPatient,
          'num' => $lastOrderNumber
        )
      )
    );
  }

  HTML::rule();

  HTML::section(2, _("Medical Problems List:"));

  if ( !$problemQ->selectProblems($idPatient) )
  {
    $problemQ->close();
    HTML::message(_("No medical problems defined for this patient."), OPEN_MSG_INFO);
    include_once("../shared/footer.php");
    exit();
  }

  $thead = array(
    _("Order Number"),
    _("Function") => array('colspan' => ($hasMedicalAdminAuth ? 5 : 3)),
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

    if ($hasMedicalAdminAuth)
    {
      $row .= HTML::strLink(_("edit"), '../medical/problem_edit_form.php',
        array(
          'key' => $problem->getIdProblem(),
          'pat' => $problem->getIdPatient()
        )
      );
      $row .= OPEN_SEPARATOR;

      $row .= HTML::strLink(_("del"), '../medical/problem_del_confirm.php',
        array(
          'key' => $problem->getIdProblem(),
          'pat' => $problem->getIdPatient(),
          'wording' => fieldPreview($problem->getWording())
        )
      );
      $row .= OPEN_SEPARATOR;
    } // end if

    $row .= HTML::strLink(_("view"), '../medical/problem_view.php',
      array(
        'key' => $problem->getIdProblem(),
        'pat' => $problem->getIdPatient()
      )
    );
    $row .= OPEN_SEPARATOR;

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

    $row .= I18n::localDate($problem->getLastUpdateDate());

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end while
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);

  HTML::table($thead, $tbody, null, $options);

  require_once("../shared/footer.php");
?>
