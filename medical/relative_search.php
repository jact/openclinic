<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: relative_search.php,v 1.9 2004/10/04 21:40:28 jact Exp $
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
  require_once("../lib/validator_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving post vars and scrubbing the data
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_POST["id_patient"]);
  $currentPageNmbr = (isset($_POST["page"])) ? intval($_POST["page"]) : 1;
  $searchType = safeText($_POST["search_type"]);
  $logical = safeText($_POST["logical"]);
  $limit = (isset($_POST["limit"])) ? intval($_POST["limit"]) : 0;

  // remove slashes added by form post
  $searchText = stripslashes(safeText($_POST["search_text"]));
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
    showMessage(_("No results found."), OPEN_MSG_INFO);
    include_once("../shared/footer.php");
    exit();
  }

  //debug($_POST);
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

<script type="text/javascript" src="../scripts/checkboxes.js" defer="defer"></script>

<!-- Printing result table -->
<form method="post" action="../medical/relative_new.php">
  <div>
<?php
  showInputHidden("id_patient", $_POST["id_patient"]);

  $thead = array(
    sprintf(_("Search Results From Query: %s"), $query) => array('colspan' => 2)
  );

  $options = array(
    0 => array('align' => 'right')
  );

  $tbody = array();
  while ($pat = $patQ->fetch())
  {
    $row = $patQ->getCurrentRow() . '.';
    $row .= '<input type="checkbox" name="check[]" value="' . $pat->getIdPatient() . '" />';
    $row .= OPEN_SEPARATOR;

    $row .= $pat->getSurname1() . ' ' . $pat->getSurname2() . ' ' . $pat->getFirstName();

    if ($val != "")
    {
      $row .= "<br />" . $key . " ";
      eval("\$row .= $val;");
    }

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end while
  $patQ->freeResult();
  $patQ->close();
  unset($patQ);

  $tfoot = array(
    0 => '<a href="#" onclick="setCheckboxes(1, \'check[]\', true); return false;">' . _("Select all") . '</a>' . ' / ' . '<a href="#" onclick="setCheckboxes(1, \'check[]\', false); return false;">' . _("Unselect all") . '</a>',
    1 => htmlInputButton("button1", _("Add selected to Relatives List"))
  );

  showTable($thead, $tbody, $tfoot, $options);
?>
  </div>
</form>

<?php
  showResultPages($currentPageNmbr, $pageCount);

  require_once("../shared/footer.php");
?>
