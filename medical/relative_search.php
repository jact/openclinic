<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: relative_search.php,v 1.2 2004/04/24 14:52:15 jact Exp $
 */

/**
 * relative_search.php
 ********************************************************************
 * Possible relatives result set page
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
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

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Patient_Query.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/search_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving post vars and scrubbing the data
  ////////////////////////////////////////////////////////////////////
  $idPatient = $_POST["id_patient"];

  if (isset($_POST["page"]))
  {
    $currentPageNmbr = $_POST["page"];
  }
  else
  {
    $currentPageNmbr = 1;
  }
  $searchType = $_POST["search_type"];
  $logical = $_POST["logical"];
  $limit = $_POST["limit"];

  // remove slashes added by form post
  $searchText = stripslashes(trim($_POST["search_text"]));
  // remove redundant whitespace
  $searchText = eregi_replace("[[:space:]]+", " ", $searchText);
  // secure data
  $searchText = urlencode($searchText);
  // explode data
  $arraySearch = explode("+", $searchText);

  ////////////////////////////////////////////////////////////////////
  // Search database
  ////////////////////////////////////////////////////////////////////
  $patQ = new Patient_Query();
  $patQ->setItemsPerPage(OPEN_ITEMS_PER_PAGE);
  $patQ->connect();
  if ($patQ->errorOccurred())
  {
    showQueryError($patQ);
  }

  if ( !$patQ->search($searchType, $arraySearch, $currentPageNmbr, $logical, $limit) )
  {
    $patQ->close();
    showQueryError($patQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Show search results
  ////////////////////////////////////////////////////////////////////
  $title = _("Search Results");
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

  // Display no results message if no results returned from search.
  if ($patQ->getRowCount() == 0)
  {
    $patQ->close();
    echo '<p>' . _("No results found.") . "</p>\n";
    include_once("../shared/footer.php");
    exit();
  }

  debug($_POST);
?>

<!-- JavaScript to post back to this page -->
<script type="text/javascript">
<!--
function changePage(page)
{
  document.forms[0].page.value = page;
  document.forms[0].submit();
}
//-->
</script>

<!-- Form used by javascript to post back to this page -->
<form method="post" action="../medical/relative_search.php">
  <div>
<?php
  showInputHidden("search_type", $_POST["search_type"]);
  showInputHidden("search_text", $_POST["search_text"]);
  showInputHidden("page", $currentPageNmbr);
  showInputHidden("logical", $_POST["logical"]);
  showInputHidden("limit", $_POST["limit"]);
  showInputHidden("id_patient", $_POST["id_patient"]);

/*  $n = count($_POST["check"]);
  for ($i = 0; $i < $n; $i++)
  {
    showInputHidden(check[' . $i . '], $_POST["check"][$i]);
  }*/
?>
  </div>
</form>

<?php
  // Printing result stats and page nav
  echo '<p><strong>' . sprintf(_("%d matches found."), $patQ->getRowCount()) . "</strong></p>\n";

  $pageCount = $patQ->getPageCount();
  showResultPages($currentPageNmbr, $pageCount);

  $val = "";
  switch ($_POST["search_type"])
  {
    case OPEN_SEARCH_NIF:
      $key = _("Tax Identification Number (TIN)") . ":";
      $val = "\$pat->getNIF()";
      break;

    case OPEN_SEARCH_NTS:
      $key = _("Sanitary Card Number (SCN)") . ":";
      $val = "\$pat->getNTS()";
      break;

    case OPEN_SEARCH_NSS:
      $key = _("National Health Service Number (NHSN)") . ":";
      $val = "\$pat->getNSS()";
      break;

    case OPEN_SEARCH_BIRTHPLACE:
      $key = _("Birth Place") . ":";
      $val = "\$pat->getBirthPlace()";
      break;

    case OPEN_SEARCH_ADDRESS:
      $key = _("Address") . ":";
      $val = "\$pat->getAddress()";
      break;

    case OPEN_SEARCH_PHONE:
      $key = _("Phone Contact") . ":";
      $val = "\$pat->getPhone()";
      break;

    case OPEN_SEARCH_INSURANCE:
      $key = _("Insurance Company") . ":";
      $val = "\$pat->getInsuranceCompany()";
      break;

    case OPEN_SEARCH_COLLEGIATE:
      $key = _("Collegiate Number") . ":";
      $val = "\$pat->getCollegiateNumber()";
      break;
  }
?>

<script type="text/javascript" src="../scripts/checkboxes.js" defer="defer"></script>

<!-- Printing result table -->
<form method="post" action="../medical/relative_new.php">
  <div>
    <?php showInputHidden("id_patient", $_POST["id_patient"]); ?>

    <table>
      <thead>
        <tr>
          <th colspan="2">
            <?php echo _("Search Results"); ?>
          </th>
        </tr>
      </thead>

      <tbody>
<?php
  $rowClass = "odd";
  while ($pat = $patQ->fetchPatient())
  {
?>
        <tr class="<?php echo $rowClass; ?>">
          <td class="number">
            <?php echo $patQ->getCurrentRow(); ?>.
            <input type="checkbox" name="check[]" value="<?php echo $pat->getIdPatient(); ?>" />
          </td>

          <td>
            <!--a href="../medical/patient_view.php?key=<?php echo $pat->getIdPatient(); ?>&amp;reset=Y"--><?php echo $pat->getSurname1(); ?> <?php echo $pat->getSurname2(); ?>, <?php echo $pat->getFirstName();?><!--/a-->
            <?php
              if ($val != "")
              {
                echo "<br />" . $key . " ";
                eval("print($val);");
                echo "<br />\n";
              }
            ?>
          </td>
        </tr>
<?php
    // swap row color
    ($rowClass == "odd") ? $rowClass = "even" : $rowClass = "odd";
  } // end while
  $patQ->freeResult();
  $patQ->close();
  unset($patQ);
?>

        <tr class="<?php echo $rowClass; ?>">
          <td colspan="2" class="center">
            <a href="#" onclick="setCheckboxes(1, 'check[]', true); return false;"><?php echo _("Select all"); ?></a>

            &nbsp;/&nbsp;

            <a href="#" onclick="setCheckboxes(1, 'check[]', false); return false;"><?php echo _("Unselect all"); ?></a>
          </td>
        </tr>

        <tr>
          <td colspan="2" class="center">
            <?php showInputButton("button1", _("Add selected to Relatives List")); ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</form>

<?php
  showResultPages($currentPageNmbr, $pageCount);

  require_once("../shared/footer.php");
?>
