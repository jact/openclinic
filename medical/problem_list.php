<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_list.php,v 1.5 2004/07/10 16:59:23 jact Exp $
 */

/**
 * problem_list.php
 ********************************************************************
 * Medical problems screen
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || empty($_GET["key"]))
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
  // Retrieving get var
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_GET["key"]);

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Problem_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/misc_lib.php");

  $problemQ = new Problem_Query();
  $problemQ->connect();
  if ($problemQ->isError())
  {
    showQueryError($problemQ);
  }

  $lastOrderNumber = $problemQ->getLastOrderNumber($idPatient);

  $count = $problemQ->selectProblems($idPatient);
  if ($problemQ->isError())
  {
    $problemQ->close();
    showQueryError($problemQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Medical Problems Report");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  if ( !showPatientHeader($idPatient) )
  {
    echo _("That patient does not exist.");

    include_once("../shared/footer.php");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Display insertion message if coming from new with a successful insert.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["added"]) && isset($_GET["info"]))
  {
    echo '<p>' . sprintf(_("Medical problem, %s, has been added."), urldecode($_GET["info"])) . "</p>\n";
  }

  ////////////////////////////////////////////////////////////////////
  // Display update message if coming from edit with a successful update.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["updated"]) && isset($_GET["info"]))
  {
    echo '<p>' . sprintf(_("Medical problem, %s, has been updated."), urldecode($_GET["info"])) . "</p>\n";
  }

  ////////////////////////////////////////////////////////////////////
  // Display deletion message if coming from del with a successful delete.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["deleted"]) && isset($_GET["info"]))
  {
    echo '<p>' . sprintf(_("Medical problem, %s, has been deleted."), urldecode($_GET["info"])) . "</p>\n";
  }

  if ($hasMedicalAdminAuth)
  {
    echo '<p><a href="../medical/problem_new_form.php?key=' . $idPatient . '&amp;num=' . $lastOrderNumber . '&amp;reset=Y">' . _("Add New Medical Problem") . "</a></p>\n";
  }

  echo '<h3>' . _("Medical Problems List:") . "</h3>\n";

  if ($count == 0)
  {
    $problemQ->close();
    echo '<p>' . _("No medical problems defined for this patient.") . "</p>\n";
    include_once("../shared/footer.php");
    exit();
  }
?>

<table>
  <thead>
    <tr>
      <th>
        <?php echo _("Order Number"); ?>
      </th>

      <th colspan="<?php echo ($hasMedicalAdminAuth ? 5 : 3); ?>">
        <?php echo _("Function"); ?>
      </th>

      <th>
        <?php echo _("Wording"); ?>
      </th>

      <th>
        <?php echo _("Opening Date"); ?>
      </th>

      <th>
        <?php echo _("Last Update Date"); ?>
      </th>
    </tr>
  </thead>

  <tbody>
<?php
  $rowClass = "odd";
  while ($problem = $problemQ->fetch())
  {
?>
    <tr class="<?php echo $rowClass; ?>">
      <td class="center">
        <?php echo $problem->getOrderNumber(); ?>
      </td>

<?php
    if ($hasMedicalAdminAuth)
    {
?>
      <td>
        <a href="../medical/problem_edit_form.php?key=<?php echo $problem->getIdProblem(); ?>&amp;pat=<?php echo $problem->getIdPatient(); ?>&amp;reset=Y"><?php echo _("edit"); ?></a>
      </td>

      <td>
        <a href="../medical/problem_del_confirm.php?key=<?php echo $problem->getIdProblem(); ?>&amp;pat=<?php echo $problem->getIdPatient(); ?>&amp;wording=<?php echo fieldPreview($problem->getWording()); ?>"><?php echo _("del"); ?></a>
      </td>
<?php
    } // end if
?>

      <td>
        <a href="../medical/problem_view.php?key=<?php echo $problem->getIdProblem(); ?>&amp;pat=<?php echo $problem->getIdPatient(); ?>"><?php echo _("view"); ?></a>
      </td>

      <td>
        <a href="../medical/test_list.php?key=<?php echo $problem->getIdProblem(); ?>&amp;pat=<?php echo $problem->getIdPatient(); ?>"><?php echo _("tests"); ?></a>
      </td>

      <td>
        <a href="../medical/connection_list.php?key=<?php echo $problem->getIdProblem(); ?>&amp;pat=<?php echo $problem->getIdPatient(); ?>"><?php echo _("connect"); ?></a>
      </td>

      <td>
        <?php echo fieldPreview($problem->getWording()); ?>
      </td>

      <td>
        <?php echo $problem->getOpeningDate(); ?>
      </td>

      <td>
        <?php echo $problem->getLastUpdateDate(); ?>
      </td>
    </tr>
<?php
    // swap row color
    ($rowClass == "odd") ? $rowClass = "even" : $rowClass = "odd";
  } // end while
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);
?>
  </tbody>
</table>

<?php require_once("../shared/footer.php"); ?>
