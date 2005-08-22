<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_search.php,v 1.17 2005/08/22 15:16:27 jact Exp $
 */

/**
 * patient_search.php
 *
 * Patient result set page
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
  $nav = "search";
  $onlyDoctor = true;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Patient_Page_Query.php");
  require_once("../lib/Form.php");
  require_once("../lib/Search.php");

  /**
   * Retrieving post vars and scrubbing the data
   */
  $currentPageNmbr = (isset($_POST["page"])) ? intval($_POST["page"]) : 1;
  $searchType = Check::safeText($_POST["search_type"]);
  $logical = Check::safeText($_POST["logical"]);
  $limit = (isset($_POST["limit"])) ? intval($_POST["limit"]) : 0;

  // remove slashes added by form post
  $searchText = stripslashes(Check::safeText($_POST["search_text"]));
  // remove redundant whitespace
  $searchText = eregi_replace("[[:space:]]+", " ", $searchText);
  // transform string in array of strings
  $arraySearch = Search::explodeQuoted($searchText);

  /**
   * Search database
   */
  $patQ = new Patient_Page_Query();
  $patQ->setItemsPerPage(OPEN_ITEMS_PER_PAGE);
  $patQ->connect();
  if ($patQ->isError())
  {
    Error::query($patQ);
  }

  $patQ->search($searchType, $arraySearch, $currentPageNmbr, $logical, $limit);
  if ($patQ->isError())
  {
    $patQ->close();
    Error::query($patQ);
  }

  /**
   * Show patient view screen if only one result from query
   */
  if ($patQ->getRowCount() == 1)
  {
    $pat = $patQ->fetch();
    $patQ->freeResult();
    $patQ->close();

    header("Location: ../medical/patient_view.php?key=" . $pat->getIdPatient() . "&reset=Y");
    exit();
  }

  /**
   * Show page
   */
  $title = _("Search Results");
  require_once("../shared/header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon searchIcon");
  unset($links);

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
  echo '<form id="changePage" method="post" action="../medical/patient_search.php">' . "\n";
  echo "<div>\n";
  Form::hidden("search_type", "search_type", $searchType);
  Form::hidden("search_text", "search_text", $searchText);
  Form::hidden("page", "page", $currentPageNmbr);
  Form::hidden("logical", "logical", $logical);
  Form::hidden("limit", "limit", $limit);
  echo "</div>\n</form>\n";

  /**
   * Printing result stats and page nav
   */
  echo '<p><strong>' . sprintf(_("%d matches found."), $patQ->getRowCount()) . "</strong></p>\n";

  $pageCount = $patQ->getPageCount();
  Search::pageLinks($currentPageNmbr, $pageCount);

  /**
   * Choose field
   */
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
    $row .= OPEN_SEPARATOR;
    $row .= '<a href="../medical/patient_view.php?key=' . $pat->getIdPatient() . '&amp;reset=Y">' . $pat->getSurname1() . " " . $pat->getSurname2() . ", " . $pat->getFirstName() . '</a>';

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

  HTML::table($thead, $tbody, null, $options);

  Search::pageLinks($currentPageNmbr, $pageCount);

  require_once("../shared/footer.php");
?>
