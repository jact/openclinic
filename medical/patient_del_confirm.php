<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_del_confirm.php,v 1.15 2006/03/26 15:13:08 jact Exp $
 */

/**
 * patient_del_confirm.php
 *
 * Confirmation screen of a patient deletion process
 *
 * @author jact <jachavar@gmail.com>
 */

  /**
   * Checking for get vars. Go back to form if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || empty($_GET["name"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "social";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");

  /**
   * Retrieving get vars
   */
  $idPatient = intval($_GET["key"]);
  $patName = Check::safeText($_GET["name"]);

  /**
   * Show page
   */
  $title = _("Delete Patient");
  require_once("../shared/header.php");

  $returnLocation = "../medical/patient_view.php?key=" . $idPatient; // controlling var

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Social Data") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  /**
   * Confirm form
   */
  echo '<form method="post" action="../medical/patient_del.php">' . "\n";

  $tbody = array();

  $tbody[] = HTML::strMessage(sprintf(_("Are you sure you want to delete patient, %s?"), $patName), OPEN_MSG_WARNING, false);

  $row = Form::strHidden("id_patient", $idPatient);
  $row .= Form::strHidden("name", $patName);
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("delete", _("Delete"))
    . Form::strButton("return", _("Return"), "button", array('onclick' => "parent.location='" . $returnLocation . "'"))
  );

  $options = array('class' => 'center');

  Form::fieldset($title, $tbody, $tfoot, $options);

  echo "</form>\n";

  require_once("../shared/footer.php");
?>
