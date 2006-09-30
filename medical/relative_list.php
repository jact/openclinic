<?php
/**
 * relative_list.php
 *
 * List of defined relation between patients screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: relative_list.php,v 1.22 2006/09/30 17:21:04 jact Exp $
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
  $nav = "social";
  $onlyDoctor = true;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Relative_Query.php");
  require_once("../classes/Patient_Page_Query.php");
  require_once("../lib/Form.php");

  /**
   * Retrieving get vars
   */
  $idPatient = intval($_GET["key"]);
  $info = (isset($_GET["info"]) ? urldecode(Check::safeText($_GET["info"])) : "");

  $relQ = new Relative_Query;
  $relQ->connect();

  $relArray = array();
  if ($relQ->select($idPatient))
  {
    while ($rel = $relQ->fetch())
    {
      $relArray[] = $rel[1];
    }
    $relQ->freeResult();
  }
  $relQ->close();
  unset($relQ);

  /**
   * Show page
   */
  $title = _("View Relatives");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Social Data") => "../medical/patient_view.php?key=" . $idPatient,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  showPatientHeader($idPatient);

  /**
   * Display insertion message if coming from new with a successful insert.
   */
  if (isset($_GET["added"]))
  {
    HTML::message(_("Relatives have been added."), OPEN_MSG_INFO);
  }

  /**
   * Display deletion message if coming from del with a successful delete.
   */
  if (isset($_GET["deleted"]) && !empty($info))
  {
    HTML::message(sprintf(_("Relative, %s, has been deleted."), $info), OPEN_MSG_INFO);
  }

  if ($hasMedicalAdminAuth)
  {
    $title = _("Search Relatives to add to list");

    echo "<p>&nbsp;</p>\n"; // @fixme should be deleted

    /**
     * Search form
     */
    HTML::start('form', array('method' => 'post', 'action' => '../medical/relative_search.php'));

    Form::hidden("id_patient", $idPatient);

    require_once("../medical/patient_search_fields.php");

    HTML::end('form');

    HTML::message('* ' . _("Note: Empty search to see all results."));
  } // end if

  if (count($relArray) == 0)
  {
    HTML::message(_("No relatives defined for this patient."), OPEN_MSG_INFO);
    include_once("../shared/footer.php");
    exit();
  }

  HTML::rule();

  HTML::section(2, _("Relatives List:"));

  $thead = array(
    _("Function") => array('colspan' => ($hasMedicalAdminAuth ? 2 : 1)),
    _("Surname 1"),
    _("Surname 2"),
    _("First Name")
  );

  $patQ = new Patient_Page_Query();
  $patQ->connect();
  $patQ->captureError(true);

  $tbody = array();
  for ($i = 0; $i < count($relArray); $i++)
  {
    $patQ->select($relArray[$i]);
    if ($patQ->isError())
    {
      Error::query($patQ, false);
      continue;
    }

    $pat = $patQ->fetch();
    if ( !$pat )
    {
      $patQ->close();
      Error::fetch($patQ);
    }

    $relName = $pat->getFirstName() . " " . $pat->getSurname1() . " " . $pat->getSurname2();

    $row = HTML::strLink(_("view"), '../medical/patient_view.php', array('key' => $pat->getIdPatient()));
    $row .= OPEN_SEPARATOR;

    if ($hasMedicalAdminAuth)
    {
      $row .= HTML::strLink(_("del"), '../medical/relative_del_confirm.php',
        array(
          'key' => $idPatient,
          'rel' => $pat->getIdPatient(),
          'name' => $relName
        )
      );
      $row .= OPEN_SEPARATOR;
    } // end if

    $row .= $pat->getSurname1();
    $row .= OPEN_SEPARATOR;

    $row .= $pat->getSurname2();
    $row .= OPEN_SEPARATOR;

    $row .= $pat->getFirstName();

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end for
  $patQ->freeResult();
  $patQ->close();
  unset($patQ);
  unset($pat);

  HTML::table($thead, $tbody);

  require_once("../shared/footer.php");
?>
