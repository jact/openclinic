<?php
/**
 * relative_list.php
 *
 * List of defined relation between patients screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: relative_list.php,v 1.37 2008/03/23 12:00:17 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "relatives";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_DOCTOR);

  require_once("../model/Query/Relative.php");
  require_once("../model/Patient.php");
  require_once("../lib/Form.php");

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
   * Search database for relatives
   */
  $relQ = new Query_Relative();

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

  if ($_SESSION['auth']['is_administrative'])
  {
    $title = _("Search Relatives to add to list");

    /**
     * Search form
     */
    echo HTML::start('form', array('method' => 'post', 'action' => '../medical/relative_search.php'));

    echo Form::hidden("id_patient", $idPatient);

    require_once("../medical/patient_search_fields.php");

    echo HTML::end('form');

    echo Msg::hint('* ' . _("Note: Empty search to see all results."));
  } // end if

  if (count($relArray) == 0)
  {
    echo Msg::info(_("No relatives defined for this patient."));
    include_once("../layout/footer.php");
    exit();
  }

  echo HTML::rule();

  echo HTML::section(2, _("Relatives List:"));

  $thead = array(
    _("#"),
    _("Function") => array('colspan' => ($_SESSION['auth']['is_administrative'] ? 2 : 1)),
    _("Surname 1"),
    _("Surname 2"),
    _("First Name")
  );

  $options = array(
    0 => array('align' => 'right')
  );

  $patQ = new Query_Page_Patient();
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

    $row = $i + 1 . '.';
    $row .= OPEN_SEPARATOR;

    $row .= HTML::link(
      HTML::image('../img/action_view.png', _("view")),
      '../medical/patient_view.php',
      array('id_patient' => $pat->getIdPatient())
    );
    $row .= OPEN_SEPARATOR;

    if ($_SESSION['auth']['is_administrative'])
    {
      $row .= HTML::link(
        HTML::image('../img/action_delete.png', _("delete")),
        '../medical/relative_del_confirm.php',
        array(
          'id_patient' => $idPatient,
          'id_relative' => $pat->getIdPatient()
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

  echo HTML::table($thead, $tbody, null, $options);

  require_once("../layout/footer.php");
?>
