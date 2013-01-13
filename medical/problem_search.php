<?php
/**
 * problem_search.php
 *
 * Medical problems result set page
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_search.php,v 1.42 2013/01/13 14:24:39 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.4
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
  require_once("../model/Query/Page/Problem.php");
  require_once("../lib/Form.php");
  require_once("../lib/Search.php");

  /*if (isset($_POST['token_form']))
  {
    include_once("../lib/Form.php");
    Form::compareToken('../medical/patient_search_form.php');
  }*/

  /**
   * Retrieving vars (PGS) and scrubbing the data
   */
  //$currentPage = Check::postGetSessionInt('page_problem', 1);
  $currentPage = Check::postGetSessionInt('page', 1);
  $searchType = Check::postGetSessionInt('search_type_problem');
  $logical = Check::postGetSessionString('logical_problem');
  $limit = Check::postGetSessionInt('limit_problem');

  // remove slashes added by form post
  $searchText = stripslashes(Check::postGetSessionString('search_text_problem'));
  // remove redundant whitespace
  $searchText = preg_replace("/[[:space:]]+/i", " ", $searchText);
  // transform string in array of strings
  $arraySearch = Search::explodeQuoted($searchText);

  /**
   * Search database
   */
  $problemQ = new Query_Page_Problem();
  $problemQ->setItemsPerPage(OPEN_ITEMS_PER_PAGE);
  $problemQ->search($searchType, $arraySearch, $currentPage, $logical, $limit);

  /**
   * No results message if no results returned from search.
   */
  if ($problemQ->getRowCount() == 0)
  {
    $problemQ->close();

    FlashMsg::add(sprintf(_("No results found for '%s'."), $searchText));
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  /**
   * Show problem view screen if only one result from query
   */
  if ($problemQ->getRowCount() == 1)
  {
    $problem = $problemQ->fetch();
    $problemQ->close();

    header("Location: ../medical/problem_view.php?id_problem=" . $problem->getIdProblem() . "&id_patient=" . $problem->getIdPatient());
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
  HTML::para(HTML::tag('strong', sprintf(_("%d matches found."), $problemQ->getRowCount())));

  $pageCount = $problemQ->getPageCount();
  $pageLinks = Search::pageLinks($currentPage, $pageCount, $_SERVER['PHP_SELF']);
  echo $pageLinks;

  /**
   * Choose field
   */
  $val = "";
  switch ($searchType)
  {
    case OPEN_SEARCH_WORDING:
      $key = _("Wording") . ":";
      $val = "\$problem->getWording()";
      break;

    case OPEN_SEARCH_SUBJECTIVE:
      $key = _("Subjective") . ":";
      $val = "\$problem->getSubjective()";
      break;

    case OPEN_SEARCH_OBJECTIVE:
      $key = _("Objective") . ":";
      $val = "\$problem->getObjective()";
      break;

    case OPEN_SEARCH_APPRECIATION:
      $key = _("Appreciation") . ":";
      $val = "\$problem->getAppreciation()";
      break;

    case OPEN_SEARCH_ACTIONPLAN:
      $key = _("Action Plan") . ":";
      $val = "\$problem->getActionPlan()";
      break;

    case OPEN_SEARCH_PRESCRIPTION:
      $key = _("Prescription") . ":";
      $val = "\$problem->getPrescription()";
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

  $recordset = null;
  while ($problem = $problemQ->fetch())
  {
    $row = $problemQ->getCurrentRow();
    eval("\$aux = $val;");
    $recordset[$row] = $row . OPEN_SEPARATOR . $problem->getIdProblem() . OPEN_SEPARATOR . $problem->getIdPatient()
                     . OPEN_SEPARATOR . $aux . OPEN_SEPARATOR . I18n::localDate($problem->getOpeningDate())
                     . OPEN_SEPARATOR . I18n::localDate($problem->getClosingDate());
  } // end while
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);

  $tbody = array();
  foreach ($recordset as $arrKey => $arrValue)
  {
    $array = explode(OPEN_SEPARATOR, $arrValue, 6);

    $patQ = new Query_Page_Patient();
    if ($patQ->select($array[2]))
    {
      $pat = $patQ->fetch();
      if ( !$pat )
      {
        $patQ->close();
        Error::fetch($patQ);
      }

      $row = $array[0] . '.';
      $row .= OPEN_SEPARATOR;

      $row .= HTML::link($pat->getSurname1() . " " . $pat->getSurname2() . ", " . $pat->getFirstName(),
        '../medical/problem_view.php', array(
          'id_problem' => $array[1],
          'id_patient' => $array[2]
        )
      );
      $row .= "<br />" . PHP_EOL . $key . " " . $array[3] . "<br />" . PHP_EOL;
      $row .= _("Opening Date") . ": " . $array[4];
      if ($array[5] != "")
      {
        $row .= "<br />" . _("Closing Date") . ": " . $array[5];
      }

      $tbody[] = explode(OPEN_SEPARATOR, $row);
    } // end if
  } // end while
  $patQ->freeResult();
  $patQ->close();
  unset($patQ);
  unset($recordset);
  unset($array);

  echo HTML::table($thead, $tbody, null, $options);

  echo $pageLinks;

  require_once("../layout/footer.php");
?>
