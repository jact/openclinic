<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: relative_list.php,v 1.10 2004/10/04 21:40:08 jact Exp $
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

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Relative_Query.php");
  require_once("../classes/Patient_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/validator_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_GET["key"]);
  $info = (isset($_GET["info"]) ? urldecode(safeText($_GET["info"])) : "");

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
  $relQ->close();
  unset($relQ);

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

  ////////////////////////////////////////////////////////////////////
  // Display insertion message if coming from new with a successful insert.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["added"]))
  {
    showMessage(_("Relatives have been added."), OPEN_MSG_INFO);
  }

  ////////////////////////////////////////////////////////////////////
  // Display deletion message if coming from del with a successful delete.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["deleted"]) && !empty($info))
  {
    showMessage(sprintf(_("Relative, %s, has been deleted."), $info), OPEN_MSG_INFO);
  }

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
    showMessage('* ' . _("Note: Empty search to see all results."));
  } // end if

  if (count($relArray) == 0)
  {
    showMessage(_("No relatives defined for this patient."), OPEN_MSG_INFO);
    include_once("../shared/footer.php");
    exit();
  }

  echo "<hr />\n";

  echo '<h3>' . _("Relatives List:") . "</h3>\n";

  $thead = array(
    _("Function") => array('colspan' => ($hasMedicalAdminAuth ? 2 : 1)),
    _("Surname 1"),
    _("Surname 2"),
    _("First Name")
  );

  $patQ = new Patient_Query();
  $patQ->connect();
  if ($patQ->isError())
  {
    showQueryError($patQ);
  }

  $tbody = array();
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

    $row = '<a href="../medical/patient_view.php?key=' . $pat->getIdPatient() . '">' . _("view") . '</a>';
    $row .= OPEN_SEPARATOR;

    if ($hasMedicalAdminAuth)
    {
      $row .= '<a href="../medical/relative_del_confirm.php?key=' . $idPatient . '&amp;rel=' . $pat->getIdPatient() . '&amp;name=' . $relName . '">' . _("del") . '</a>';
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

  showTable($thead, $tbody);

  require_once("../shared/footer.php");
?>
