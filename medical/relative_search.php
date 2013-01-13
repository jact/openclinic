<?php
/**
 * relative_search.php
 *
 * Possible relatives result set page
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: relative_search.php,v 1.46 2013/01/13 14:24:55 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "relatives";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE);

  require_once("../lib/Form.php");
  require_once("../lib/Search.php");
  require_once("../lib/String.php");
  require_once("../model/Patient.php");

  /**
   * Retrieving vars (PGS) and scrubbing the data
   */
  $idPatient = Check::postGetSessionInt('id_patient');
  $currentPage = Check::postGetSessionInt('page', 1);
  $searchType = Check::postGetSessionInt('search_type');
  $logical = Check::postGetSessionString('logical');
  $limit = Check::postGetSessionInt('limit');

  // remove slashes added by form post
  $searchText = stripslashes(Check::postGetSessionString('search_text'));
  // remove redundant whitespace
  $searchText = preg_replace("/[[:space:]]+/i", " ", $searchText);
  // secure data
  $searchText = urlencode($searchText);
  // explode data
  $arraySearch = explode("+", $searchText);

  $patient = new Patient($idPatient);
  if ($patient->getName() == '')
  {
    FlashMsg::add(_("That patient does not exist."), OPEN_MSG_ERROR);
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

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
    header("Location: " . $returnLocation);
    exit();
  }

  /**
   * Show page
   */
  $title = _("Search Results");
  $titlePage = $patient->getName() . ' (' . $title . ')';
  require_once("../layout/header.php");

  //$returnLocation = "../medical/relative_list.php?id_patient=" . $idPatient;
  $returnLocation = "../medical/relative_list.php";

  /**
   * Breadcrumb
   */
  $links = array(
    _("Medical Records") => "../medical/index.php",
    $patient->getName() => "../medical/patient_view.php",
    _("View Relatives") => $returnLocation,
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_patient");
  unset($links);

  echo $patient->getHeader();

  /**
   * Printing result stats and page nav
   */
  HTML::para(HTML::tag('strong', sprintf(_("%d matches found."), $patQ->getRowCount())));

  $pageCount = $patQ->getPageCount();
  $pageLinks = Search::pageLinks($currentPage, $pageCount, $_SERVER['PHP_SELF']);
  echo $pageLinks;

  $val = "";
  switch ($searchType)
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

  echo HTML::insertScript('checkboxes.js');

  echo HTML::start('form', array('method' => 'post', 'action' => '../medical/relative_new.php'));

  echo Form::hidden("id_patient", $idPatient, array('id' => 'r_id_patient'));

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
    $row .= Form::checkBox("check[]", $pat->getIdPatient(),
      array('id' => String::numberToAlphabet($patQ->getCurrentRow()))
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
    0 => HTML::link(_("Select all"), '#', null, array('id' => 'select_all_checks')) // @todo created by JS
      . ' / '
      . HTML::link(_("Unselect all"), '#', null, array('id' => 'unselect_all_checks')), // @todo created by JS
    1 => Form::button("add", _("Add selected to Relatives List")) . Form::generateToken(),
  );

  echo HTML::table($thead, $tbody, $tfoot, $options);

  echo HTML::end('form');

  echo $pageLinks;

  require_once("../layout/footer.php");
?>
