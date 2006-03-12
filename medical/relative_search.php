<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: relative_search.php,v 1.22 2006/03/12 18:47:05 jact Exp $
 */

/**
 * relative_search.php
 *
 * Possible relatives result set page
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "social";
  $onlyDoctor = false;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Patient_Page_Query.php");
  require_once("../lib/Form.php");
  require_once("../lib/Search.php");
  require_once("../lib/misc_lib.php");

  /**
   * Retrieving post vars and scrubbing the data
   */
  $idPatient = intval($_POST["id_patient"]);
  $currentPageNmbr = (isset($_POST["page"])) ? intval($_POST["page"]) : 1;
  $searchType = Check::safeText($_POST["search_type"]);
  $logical = Check::safeText($_POST["logical"]);
  $limit = (isset($_POST["limit"])) ? intval($_POST["limit"]) : 0;

  // remove slashes added by form post
  $searchText = stripslashes(Check::safeText($_POST["search_text"]));
  // remove redundant whitespace
  $searchText = eregi_replace("[[:space:]]+", " ", $searchText);
  // secure data
  $searchText = urlencode($searchText);
  // explode data
  $arraySearch = explode("+", $searchText);

  /**
   * Search database
   */
  $patQ = new Patient_Page_Query();
  $patQ->setItemsPerPage(OPEN_ITEMS_PER_PAGE);
  $patQ->connect();

  $patQ->search($searchType, $arraySearch, $currentPageNmbr, $logical, $limit);

  /**
   * Show page
   */
  $title = _("Search Results");
  require_once("../shared/header.php");
  require_once("../medical/patient_header.php");

  $returnLocation = "../medical/relative_list.php?key=" . $idPatient;

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    _("Social Data") => "../medical/patient_view.php?key=" . $idPatient,
    _("View Relatives") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon patientIcon");
  unset($links);

  showPatientHeader($idPatient);

  /**
   * Display no results message if no results returned from search.
   */
  if ($patQ->getRowCount() == 0)
  {
    $patQ->close();
    HTML::message(_("No results found."), OPEN_MSG_INFO);
    include_once("../shared/footer.php");
    exit();
  }

  Search::changePageJS();

  /**
   * Form used by javascript to post back to this page (id="changePage" important)
   */
  echo '<form id="changePage" method="post" action="../medical/relative_search.php">' . "\n";
  echo "<div>\n";
  Form::hidden("search_type", $searchType);
  Form::hidden("search_text", $searchText);
  Form::hidden("page", $currentPageNmbr);
  Form::hidden("logical", $logical);
  Form::hidden("limit", $limit);
  Form::hidden("id_patient", $idPatient);

/*  $n = count($_POST["check"]);
  for ($i = 0; $i < $n; $i++)
  {
    Form::hidden('check[' . $i . ']', 'check[' . $i . ']', $_POST["check"][$i]);
  }*/
  echo "</div>\n</form>\n";

  /**
   * Printing result stats and page nav
   */
  echo '<p><strong>' . sprintf(_("%d matches found."), $patQ->getRowCount()) . "</strong></p>\n";

  $pageCount = $patQ->getPageCount();
  Search::pageLinks($currentPageNmbr, $pageCount);

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

    default:
      $key = "*";
      break;
  }

  /**
   * Build query
   */
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
  Form::hidden("id_patient", $idPatient, array('id' => 'r_id_patient'));

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
    $row .= Form::strCheckBox("check[]", $pat->getIdPatient(), false,
      array('id' => numberToAlphabet($patQ->getCurrentRow()))
    );
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
    1 => Form::strButton("button1", _("Add selected to Relatives List"))
  );

  HTML::table($thead, $tbody, $tfoot, $options);
?>
  </div>
</form>

<?php
  Search::pageLinks($currentPageNmbr, $pageCount);

  require_once("../shared/footer.php");
?>
