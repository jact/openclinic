<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: test_list.php,v 1.5 2004/07/11 11:23:02 jact Exp $
 */

/**
 * test_list.php
 ********************************************************************
 * Medical tests screen
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
  $restrictInDemo = true; // To prevent users' malice

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Test_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../lib/input_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("View Medical Tests");
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

  ////////////////////////////////////////////////////////////////////
  // Display insertion message if coming from new with a successful insert.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["added"]) && isset($_GET["info"]))
  {
    echo '<p>' . sprintf(_("Medical test, %s, has been added."), urldecode($_GET["info"])) . "</p>\n";
  }

  ////////////////////////////////////////////////////////////////////
  // Display update message if coming from edit with a successful update.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["updated"]) && isset($_GET["info"]))
  {
    echo '<p>' . sprintf(_("Medical test, %s, has been updated."), urldecode($_GET["info"])) . "</p>\n";
  }

  ////////////////////////////////////////////////////////////////////
  // Display deletion message if coming from del with a successful delete.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["deleted"]) && isset($_GET["info"]))
  {
    echo '<p>' . sprintf(_("Medical test, %s, has been deleted."), urldecode($_GET["info"])) . "</p>\n";
  }

  if ($hasMedicalAdminAuth)
  {
    echo '<p><a href="../medical/test_new_form.php?key=' . $idProblem . '&amp;pat=' . $idPatient . '&amp;reset=Y">' . _("Add New Medical Test") . "</a></p>\n";
  }

  $testQ = new Test_Query;
  $testQ->connect();
  if ($testQ->isError())
  {
    showQueryError($testQ);
  }

  $count = $testQ->select($idProblem);
  if ($testQ->isError())
  {
    $testQ->close();
    showQueryError($testQ);
  }

  if ($count == 0)
  {
    $testQ->close();
    echo '<p>' . _("No medical tests defined for this medical problem.") . "</p>\n";
    include_once("../shared/footer.php");
    exit();
  }
?>

<h3><?php echo _("Medical Tests List:"); ?></h3>

<table>
  <thead>
    <tr>
      <th colspan="<?php echo ($hasMedicalAdminAuth ? 3 : 1); ?>">
        <?php echo _("Function"); ?>
      </th>

      <th>
        <?php echo _("Document Type"); ?>
      </th>

      <th>
        <?php echo _("Path Filename"); ?>
      </th>
    </tr>
  </thead>

  <tbody>
<?php
  $rowClass = "odd";
  while ($test = $testQ->fetch())
  {
    $aux = $test->getPathFilename();
    $temp = ereg_replace("\\\\", "/", $aux);
    $temp = ereg_replace("//", "/", $temp);
    $temp = urlencode($temp);
?>
    <tr class="<?php echo $rowClass; ?>">
      <td>
        <a href="<?php echo $temp; ?>" onclick="return popSecondary('<?php echo $temp; ?>')" onkeypress="return popSecondary('<?php echo $temp; ?>')"><?php echo _("view"); ?></a>
      </td>

<?php
    if ($hasMedicalAdminAuth)
    {
?>
      <td>
        <a href="../medical/test_edit_form.php?key=<?php echo $test->getIdProblem(); ?>&amp;pat=<?php echo $idPatient; ?>&amp;test=<?php echo $test->getIdTest(); ?>&amp;reset=Y"><?php echo _("edit"); ?></a>
      </td>

      <td>
        <a href="../medical/test_del_confirm.php?key=<?php echo $idProblem; ?>&amp;test=<?php echo $test->getIdTest(); ?>&amp;pat=<?php echo $idPatient; ?>&amp;file=<?php echo $test->getPathFilename(); ?>"><?php echo _("del"); ?></a>
      </td>
<?php
    } // end if
?>

      <td>
        <?php echo $test->getDocumentType(); ?>
      </td>

      <td>
        <?php echo $test->getPathFilename(); ?>
      </td>
    </tr>
<?php
    // swap row color
    ($rowClass == "odd") ? $rowClass = "even" : $rowClass = "odd";
  } // end while
  $testQ->freeResult();
  $testQ->close();
  unset($testQ);
  unset($test);
?>
  </tbody>
</table>

<?php require_once("../shared/footer.php"); ?>
