<?php
/**
 * patient_search.php
 *
 * Patient result set page
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_search.php,v 1.37 2013/01/13 14:24:25 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "search";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_DOCTOR);

  require_once("../model/Query/Page/Patient.php");
  require_once("../lib/Search.php");

  /*if (isset($_POST['token_form']))
  {
    include_once("../lib/Form.php");
    Form::compareToken('../medical/patient_search_form.php');
  }*/

  /**
   * Retrieving vars (PGS) and scrubbing the data
   */
  $currentPage = Check::postGetSessionInt('page', 1);
  $searchType = Check::postGetSessionInt('search_type');
  $logical = Check::postGetSessionString('logical');
  $limit = Check::postGetSessionInt('limit');

  // remove slashes added by form post
  $searchText = stripslashes(Check::postGetSessionString('search_text'));
  // remove redundant whitespace
  $searchText = preg_replace("/[[:space:]]+/i", " ", $searchText);
  // transform string in array of strings
  $arraySearch = Search::explodeQuoted($searchText);

  /**
   * Search database
   */
  $patQ = new Query_Page_Patient();
  $patQ->setItemsPerPage(OPEN_ITEMS_PER_PAGE);
  $patQ->search($searchType, $arraySearch, $currentPage, $logical, $limit);

  /**
   * No results message if no results returned from search.
   */
  if ($patQ->getRowCount() == 0)
  {
    $patQ->close();

    FlashMsg::add(sprintf(_("No results found for '%s'."), $searchText));
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Show patient view screen if only one result from query
   */
  if ($patQ->getRowCount() == 1)
  {
    $pat = $patQ->fetch();
    $patQ->freeResult();
    $patQ->close();

    header("Location: ../medical/patient_view.php?id_patient=" . $pat->getIdPatient());
    exit();
  }

  /**
   * Show page
   */
  $title = _("Search Results");
  require_once("../layout/header.php");

  /**
   * Breadcrumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    _("Search Patient") => "../medical/patient_search_form.php",
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_search");
  unset($links);

  /**
   * Printing result stats and page nav
   */
  echo HTML::para(HTML::tag('strong', sprintf(_("%d matches found."), $patQ->getRowCount())));

  $pageCount = $patQ->getPageCount();
  $pageLinks = Search::pageLinks($currentPage, $pageCount, $_SERVER['PHP_SELF']);
  echo $pageLinks;

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
    $row .= HTML::link($pat->getSurname1() . " " . $pat->getSurname2() . ", " . $pat->getFirstName(),
      '../medical/patient_view.php', array('id_patient' => $pat->getIdPatient())
    );

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

  echo HTML::table($thead, $tbody, null, $options);

  echo $pageLinks;

  require_once("../layout/footer.php");
?>
