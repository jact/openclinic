<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: connection_list.php,v 1.2 2004/04/24 14:52:13 jact Exp $
 */

/**
 * connection_list.php
 ********************************************************************
 * List of defined connection between medical problems screen
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || empty($_GET["key"]) || empty($_GET["pat"]))
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = true;

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Connection_Query.php");
  require_once("../classes/Problem_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/misc_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("View Connection Problems");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");
  require_once("../medical/problem_header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Medical Problems Report") => "../medical/problem_list.php?key=" . $idPatient,
    _("View Medical Problem") => "../medical/problem_view.php?key=" . $idProblem . "&amp;pat=" . $idPatient,
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  showPatientHeader($idPatient);
  showProblemHeader($idProblem);

  if ($hasMedicalAdminAuth)
  {
    echo '<p><a href="../medical/connection_new_form.php?key=' . $idProblem . '&amp;pat=' . $idPatient . '">' . _("Add New Connection Problems") . "</a></p>\n";
  }

  $connQ = new Connection_Query;
  $connQ->connect();
  if ($connQ->errorOccurred())
  {
    showQueryError($connQ);
  }

  $connQ->select($idProblem);
  if ($connQ->errorOccurred())
  {
    $connQ->close();
    showQueryError($connQ);
  }

  $connArray = array();
  while ($conn = $connQ->fetchConn())
  {
    $connArray[] = $conn[1];
  }
  $connQ->freeResult();

  if (count($connArray) == 0)
  {
    echo '<p>' . _("No connections defined for this medical problem.") . "</p>\n";
  }
  else
  {
?>

<h3><?php echo _("Connection Problems List:"); ?></h3>

<table>
  <thead>
    <tr>
      <th colspan="<?php echo ($hasMedicalAdminAuth ? 2 : 1); ?>">
        <?php echo _("Function"); ?>
      </th>

      <th>
        <?php echo _("Opening Date"); ?>
      </th>

      <th>
        <?php echo _("Wording"); ?>
      </th>
    </tr>
  </thead>

  <tbody>
<?php
    $problemQ = new Problem_Query();
    $problemQ->connect();
    if ($problemQ->errorOccurred())
    {
      showQueryError($problemQ);
    }

    $rowClass = "odd";
    for ($i = 0; $i < count($connArray); $i++)
    {
      if ( !$problemQ->select($connArray[$i]) )
      {
        showQueryError($problemQ, false);
        continue;
      }

      $problem = $problemQ->fetchProblem();
?>
    <tr class="<?php echo $rowClass; ?>">
      <td>
        <a href="../medical/problem_view.php?key=<?php echo $problem->getIdProblem(); ?>&amp;pat=<?php echo $idPatient; ?>"><?php echo _("view"); ?></a>
      </td>

<?php
      if ($hasMedicalAdminAuth)
      {
?>
      <td>
        <a href="../medical/connection_del_confirm.php?key=<?php echo $idProblem; ?>&amp;conn=<?php echo $problem->getIdProblem(); ?>&amp;pat=<?php echo $idPatient; ?>&amp;wording=<?php echo fieldPreview($problem->getWording()); ?>"><?php echo _("del"); ?></a>
      </td>
<?php
      } // end if
?>

      <td>
        <?php echo $problem->getOpeningDate(); ?>
      </td>

      <td>
        <?php echo fieldPreview($problem->getWording()); ?>
      </td>
    </tr>
<?php
      // swap row color
      ($rowClass == "odd") ? $rowClass = "even" : $rowClass = "odd";
    } // end while
    $problemQ->freeResult();
    $problemQ->close();
?>
  </tbody>
</table>

<?php
    unset($problemQ);
    unset($problem);
  } // end if-else
  unset($connQ);

  require_once("../shared/footer.php");
?>
