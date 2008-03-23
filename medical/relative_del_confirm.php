<?php
/**
 * relative_del_confirm.php
 *
 * Confirmation screen of a relation between patients deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: relative_del_confirm.php,v 1.28 2008/03/23 12:00:17 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "social";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE);

  require_once("../lib/Form.php");
  require_once("../lib/Check.php");
  require_once("../model/Patient.php");

  /**
   * Retrieving vars (PGS)
   */
  $idPatient = Check::postGetSessionInt('id_patient');
  $idRelative = Check::postGetSessionInt('id_relative');

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
  $title = _("Delete Relative from list");
  $titlePage = $patient->getName() . ' (' . $title . ')';
  require_once("../layout/header.php");

  //$returnLocation = "../medical/relative_list.php?id_patient=" . $idPatient; // controlling var
  $returnLocation = "../medical/relative_list.php"; // controlling var

  /**
   * Breadcrumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    _("View Relatives") => $returnLocation,
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_patient");
  unset($links);

  echo $patient->getHeader();

  /**
   * Confirm form
   */
  echo HTML::start('form', array('method' => 'post', 'action' => '../medical/relative_del.php'));

  $tbody = array();

  $relative = new Patient($idRelative);
  $tbody[] = Msg::warning(sprintf(_("Are you sure you want to delete relative, %s, from list?"),
    $relative->getName())
  );

  $row = Form::hidden("id_patient", $idPatient);
  $row .= Form::hidden("id_relative", $idRelative);
  $row .= Form::hidden("name", $relative->getName());
  $tbody[] = $row;

  $tfoot = array(
    Form::button("delete", _("Delete"))
    . Form::generateToken()
  );

  $options = array('class' => 'center');

  echo Form::fieldset($title, $tbody, $tfoot, $options);

  echo HTML::end('form');

  echo HTML::para(HTML::link(_("Return"), $returnLocation));

  require_once("../layout/footer.php");
?>
