<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_search.php,v 1.7 2004/07/14 18:31:27 jact Exp $
 */

/**
 * patient_search.php
 ********************************************************************
 * Patient result set page
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
  $nav = "search";
  $onlyDoctor = true;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Patient_Query.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/search_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving post vars and scrubbing the data
  ////////////////////////////////////////////////////////////////////
  $currentPageNmbr = (isset($_POST["page"])) ? $_POST["page"] : 1;
  $searchType = $_POST["search_type"];
  $logical = $_POST["logical"];
  $limit = (isset($_POST["limit"])) ? $_POST["limit"] : 0;

  // remove slashes added by form post
  $searchText = stripslashes(trim($_POST["search_text"]));
  // remove redundant whitespace
  $searchText = eregi_replace("[[:space:]]+", " ", $searchText);
  // transform string in array of strings
  $arraySearch = explodeQuoted($searchText);

  ////////////////////////////////////////////////////////////////////
  // Search database
  ////////////////////////////////////////////////////////////////////
  $patQ = new Patient_Query();
  $patQ->setItemsPerPage(OPEN_ITEMS_PER_PAGE);
  $patQ->connect();
  if ($patQ->isError())
  {
    showQueryError($patQ);
  }

  $patQ->search($searchType, $arraySearch, $currentPageNmbr, $logical, $limit);
  if ($patQ->isError())
  {
    $patQ->close();
    showQueryError($patQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Show patient view screen if only one result from query
  ////////////////////////////////////////////////////////////////////
  if ($patQ->getRowCount() == 1)
  {
    $pat = $patQ->fetch();
    $patQ->freeResult();
    $patQ->close();

    header("Location: ../medical/patient_view.php?key=" . $pat->getIdPatient() . "&reset=Y");
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Show search results
  ////////////////////////////////////////////////////////////////////
  $title = _("Search Results");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    $title => ""
  );
  showNavLinks($links, "search.png");
  unset($links);

  // Display no results message if no results returned from search.
  if ($patQ->getRowCount() == 0)
  {
    $patQ->close();
    echo '<p>' . _("No results found.") . "</p>\n";
    include_once("../shared/footer.php");
    exit();
  }
?>

<!-- JavaScript to post back to this page -->
<script type="text/javascript">
<!--/*--><![CDATA[/*<!--*/
function changePage(page)
{
  document.forms[0].page.value = page;
  document.forms[0].submit();

  return false;
}
/*]]>*///-->
</script>

<!-- Form used by javascript to post back to this page -->
<form method="post" action="../medical/patient_search.php">
  <div>
<?php
  showInputHidden("search_type", $_POST["search_type"]);
  showInputHidden("search_text", $_POST["search_text"]);
  showInputHidden("page", $currentPageNmbr);
  showInputHidden("logical", $_POST["logical"]);
  showInputHidden("limit", $limit);
?>
  </div>
</form>

<?php
  // Printing result stats and page nav
  echo '<p><strong>' . sprintf(_("%d matches found."), $patQ->getRowCount()) . "</strong></p>\n";

  $pageCount = $patQ->getPageCount();
  showResultPages($currentPageNmbr, $pageCount);

  // Choose field
  $val = "";
  switch ($searchType)
  {
    case OPEN_SEARCH_SURNAME1:
      $key = _("Surname 1");
      break;

    case OPEN_SEARCH_SURNAME2:
      $key = _("Surname 2");
      break;

    case OPEN_SEARCH_FIRSTNAME:
      $key = _("First Name");
      break;

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

  // Build query
  $searchText = urldecode($searchText);
  $word = explode(" ", $searchText);
  $query = $key . " = (";
  $num = sizeof($word);
  if ($num > 1)
  {
    for ($i = 0; $i < ($num - 1); $i++)
    {
      if ($logical == OPEN_NOT)
      {
        $query .= " NOT " . $word[$i] . " AND ";
      }
      else
      {
        $query .= $word[$i] . " " . $logical . " ";
      }
    }
  }
  if ($logical == OPEN_NOT)
  {
    $query .= " NOT ";
  }
  $query .= $word[$num - 1] . ")";
?>

<!-- Printing result table -->
<table>
  <thead>
    <tr>
      <th colspan="2">
        <?php echo sprintf(_("Search Results From Query: %s"), $query); ?>
      </th>
    </tr>
  </thead>

  <tbody>
<?php
  $rowClass = "odd";
  while ($pat = $patQ->fetch())
  {
?>
    <tr class="<?php echo $rowClass; ?>">
      <td class="number">
        <?php echo $patQ->getCurrentRow(); ?>.
      </td>

      <td>
        <a href="../medical/patient_view.php?key=<?php echo $pat->getIdPatient(); ?>&amp;reset=Y"><?php echo $pat->getSurname1() . " " . $pat->getSurname2() . ", " . $pat->getFirstName(); ?></a>
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
  </tbody>
</table>

<?php
  showResultPages($currentPageNmbr, $pageCount);

  require_once("../shared/footer.php");
?>
