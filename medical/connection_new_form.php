<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: connection_new_form.php,v 1.3 2004/06/16 19:11:02 jact Exp $
 */

/**
 * connection_new_form.php
 ********************************************************************
 * Addition screen of a connection between medical problems
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
  // Controlling get vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "problems";
  $onlyDoctor = false;

  ////////////////////////////////////////////////////////////////////
  // Retrieving get vars
  ////////////////////////////////////////////////////////////////////
  $idProblem = intval($_GET["key"]);
  $idPatient = intval($_GET["pat"]);

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Problem_Query.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/search_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Search database
  ////////////////////////////////////////////////////////////////////
  $problemQ = new Problem_Query();
  $problemQ->connect();
  if ($problemQ->errorOccurred())
  {
    showQueryError($problemQ);
  }

  $count = $problemQ->selectProblems($idPatient);
  if ($problemQ->errorOccurred())
  {
    $problemQ->close();
    showQueryError($problemQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Show search results
  ////////////////////////////////////////////////////////////////////
  $title = _("Add New Connection Problems");
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

  // Display no results message if no results returned from search.
  if ($count == 0)
  {
    $problemQ->close();
    echo '<p>' . _("No medical problems defined for this patient.") . "</p>\n";
    include_once("../shared/footer.php");
    exit();
  }

  echo '<h3>' . _("Medical Problems List:") . "</h3>\n";
?>

<form method="post" action="../medical/connection_new.php">
  <div>
    <?php
      showInputHidden("id_problem", $idProblem);
      showInputHidden("id_patient", $idPatient);
    ?>

    <table>
      <thead>
        <tr>
          <th>
            <?php echo _("Order Number"); ?>
          </th>

          <th width="100%">
            <?php echo _("Wording"); ?>
          </th>
        </tr>
      </thead>

      <tbody>
<?php
  while ($problem = $problemQ->fetch())
  {
?>
        <tr>
          <td class="number">
            <?php echo $problem->getOrderNumber();?>.
            <input type="checkbox" name="check[]" value="<?php echo $problem->getIdProblem(); ?>" />
          </td>

          <td>
            <!--a href="../medical/problem_view.php?key=<?php echo $problem->getIdProblem(); ?>&amp;pat=<?php echo $idPatient; ?>&amp;reset=Y"--><?php echo $problem->getWording(); ?><!--/a-->
          </td>
        </tr>
<?php
  }
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);
?>

        <tr>
          <td colspan="2" class="center">
            <?php showInputButton("button1", _("Add selected to Connection Problems List")); ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</form>

<?php require_once("../shared/footer.php"); ?>
