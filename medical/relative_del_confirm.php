<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: relative_del_confirm.php,v 1.1 2004/03/20 20:37:12 jact Exp $
 */

/**
 * relative_del_confirm.php
 ********************************************************************
 * Confirmation screen of a relation between patients deletion process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 20/03/04 21:37
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || empty($_GET["key"]) || empty($_GET["rel"]) || empty($_GET["name"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "social";
  $onlyDoctor = false;

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_GET["key"]);
  $idRelative = intval($_GET["rel"]);
  $relName = $_GET["name"];

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Show confirm page
  ////////////////////////////////////////////////////////////////////
  $title = _("Delete Relative from list");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  $returnLocation = "../medical/relative_list.php?key=" . $idPatient;

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Social Data") => "../medical/patient_view.php?key=" . $idPatient,
    _("View Relatives") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  showPatientHeader($idPatient);
  echo "<br />\n";
?>

<form method="post" action="../medical/relative_del.php?key=<?php echo $idPatient; ?>&rel=<?php echo $idRelative; ?>&name=<?php echo $relName; ?>">
  <div class="center">
    <table>
      <thead>
        <tr>
          <th>
            <?php echo _("Delete Relative from list"); ?>
          </th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>
            <?php echo sprintf(_("Are you sure you want to delete relative, %s, from list?"), $relName); ?>
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
