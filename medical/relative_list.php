<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: relative_list.php,v 1.6 2004/07/07 17:23:21 jact Exp $
 */

/**
 * relative_list.php
 ********************************************************************
 * List of defined relation between patients screen
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
  $nav = "social";
  $onlyDoctor = true;

  ////////////////////////////////////////////////////////////////////
  // Retrieving get var
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_GET["key"]);

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Relative_Query.php");
  require_once("../classes/Patient_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../lib/input_lib.php");

  $relQ = new Relative_Query;
  $relQ->connect();
  if ($relQ->isError())
  {
    showQueryError($relQ);
  }

  $numRows = $relQ->select($idPatient);
  if ($relQ->isError())
  {
    $relQ->close();
    showQueryError($relQ);
  }

  $relArray = array();
  if ($numRows)
  {
    while ($rel = $relQ->fetch())
    {
      $relArray[] = $rel[1];
    }
    $relQ->freeResult();
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("View Relatives");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Social Data") => "../medical/patient_view.php?key=" . $idPatient,
    $title => ""
  );
  showNavLinks($links, "patient.png");
  unset($links);

  showPatientHeader($idPatient);

  if ($hasMedicalAdminAuth)
  {
    $title = _("Search Relatives to add to list");
?>

<p>&nbsp;</p>

<form method="post" action="../medical/relative_search.php">
  <div>
<?php
  showInputHidden("id_patient", $idPatient);

  require_once("../medical/patient_search_fields.php");
?>
  </div>
</form>

<?php
    echo '<p class="advice center">* ' . _("Note: Empty search to see all results.") . "</p>\n";
  } // end if

  if (count($relArray) == 0)
  {
    echo '<p>' . _("No relatives defined for this patient.") . "</p>\n";
  }
  else
  {
?>

<h3><?php echo _("Relatives List:"); ?></h3>

<table>
  <thead>
    <tr>
      <th colspan="<?php echo ($hasMedicalAdminAuth ? 2 : 1); ?>">
        <?php echo _("Function"); ?>
      </th>

      <th>
        <?php echo _("Surname 1"); ?>
      </th>

      <th>
        <?php echo _("Surname 2"); ?>
      </th>

      <th>
        <?php echo _("First Name"); ?>
      </th>
    </tr>
  </thead>

  <tbody>
<?php
    $patQ = new Patient_Query();
    $patQ->connect();
    if ($patQ->isError())
    {
      showQueryError($patQ);
    }

    $rowClass = "odd";
    for ($i = 0; $i < count($relArray); $i++)
    {
      $patQ->select($relArray[$i]);
      if ($patQ->isError())
      {
        showQueryError($patQ, false);
        continue;
      }

      $pat = $patQ->fetch();
      if ($patQ->isError())
      {
        $patQ->close();
        showFetchError($patQ);
      }

      $relName = $pat->getFirstName() . " " . $pat->getSurname1() . " " . $pat->getSurname2();
?>
    <tr class="<?php echo $rowClass; ?>">
      <td>
        <a href="../medical/patient_view.php?key=<?php echo $pat->getIdPatient(); ?>"><?php echo _("view"); ?></a>
      </td>

<?php
      if ($hasMedicalAdminAuth)
      {
?>
      <td>
        <a href="../medical/relative_del_confirm.php?key=<?php echo $idPatient; ?>&amp;rel=<?php echo $pat->getIdPatient(); ?>&amp;name=<?php echo $relName; ?>"><?php echo _("del"); ?></a>
      </td>
<?php
      } // end if
?>

      <td>
        <?php echo $pat->getSurname1(); ?>
      </td>

      <td>
        <?php echo $pat->getSurname2(); ?>
      </td>

      <td>
        <?php echo $pat->getFirstName(); ?>
      </td>
    </tr>
<?php
      // swap row color
      ($rowClass == "odd") ? $rowClass = "even" : $rowClass = "odd";
    } // end while
    $patQ->freeResult();
    $patQ->close();
?>
  </tbody>
</table>

<?php
    unset($patQ);
    unset($pat);
  } // end if-else
  unset($relQ);

  require_once("../shared/footer.php");
?>
