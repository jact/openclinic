<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: connection_del_confirm.php,v 1.1 2004/03/20 20:38:04 jact Exp $
 */

/**
 * connection_del_confirm.php
 ********************************************************************
 * Confirmation screen of a connection between medical problems deletion process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 20/03/04 21:38
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || empty($_GET["key"]) || empty($_GET["conn"]) || empty($_GET["pat"]) || empty($_GET["wording"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = false;

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idProblem = intval($_GET["key"]);
  $idConnection = intval($_GET["conn"]);
  $idPatient = intval($_GET["pat"]);
  $wording = $_GET["wording"];

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Show confirm page
  ////////////////////////////////////////////////////////////////////
  $title = _("Delete Connection with Medical Problem");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");
  require_once("../medical/problem_header.php");

  $returnLocation = "../medical/connection_list.php?key=" . $idProblem . "&amp;pat=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Medical Problems Report") => "../medical/problem_list.php?key=" . $idPatient,
    _("View Medical Problem") => "../medical/problem_view.php?key=" . $idProblem . "&amp;pat=" . $idPatient,
    _("View Connection Problems") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  showPatientHeader($idPatient);
  showProblemHeader($idProblem);
?>

<br />

<form method="post" action="../medical/connection_del.php?key=<?php echo $idProblem; ?>&conn=<?php echo $idConnection; ?>&pat=<?php echo $idPatient; ?>&wording=<?php echo $wording; ?>">
  <div class="center">
    <table>
      <thead>
        <tr>
          <th>
            <?php echo $title; ?>
          </th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>
            <?php echo sprintf(_("Are you sure you want to delete connection, %s, from list?"), $wording); ?>
          </td>
        </tr>

        <tr>
          <td class="center">
            <?php
              showInputButton("delete", _("Delete"));
              showInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"');
            ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</form>

<?php require_once("../shared/footer.php"); ?>
