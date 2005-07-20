<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_search.php,v 1.14 2005/07/20 20:25:24 jact Exp $
 */

/**
 * problem_search.php
 *
 * Medical problems result set page
 *
 * Author: jact <jachavar@gmail.com>
 * @since 0.4
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "search";
  $onlyDoctor = true;

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: ../medical/patient_search_form.php");
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Patient_Page_Query.php");
  require_once("../classes/Problem_Page_Query.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/search_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving post vars and scrubbing the data
  ////////////////////////////////////////////////////////////////////
  $currentPageNmbr = (isset($_POST["page"])) ? intval($_POST["page"]) : 1;
  $searchType = Check::safeText($_POST["search_type_problem"]);
  $logical = Check::safeText($_POST["logical_problem"]);
  $limit = (isset($_POST["limit_problem"])) ? intval($_POST["limit_problem"]) : 0;

  // remove slashes added by form post
  $searchText = stripslashes(Check::safeText($_POST["search_text_problem"]));
  // remove redundant whitespace
  $searchText = eregi_replace("[[:space:]]+", " ", $searchText);
  // transform string in array of strings
  $arraySearch = explodeQuoted($searchText);

  ////////////////////////////////////////////////////////////////////
  // Search database
  ////////////////////////////////////////////////////////////////////
  $problemQ = new Problem_Page_Query();
  $problemQ->setItemsPerPage(OPEN_ITEMS_PER_PAGE);
  $problemQ->connect();
  if ($problemQ->isError())
  {
    Error::query($problemQ);
  }

  $problemQ->search($searchType, $arraySearch, $currentPageNmbr, $logical, $limit);
  if ($problemQ->isError())
  {
    $problemQ->close();
    Error::query($problemQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Show problem view screen if only one result from query
  ////////////////////////////////////////////////////////////////////
  if ($problemQ->getRowCount() == 1)
  {
    $problem = $problemQ->fetch();
    $problemQ->close();

    header("Location: ../medical/problem_view.php?key=" . $problem->getIdProblem() . "&pat=" . $problem->getIdPatient() . "&reset=Y");
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

  ////////////////////////////////////////////////////////////////////
  // Display no results message if no results returned from search.
  ////////////////////////////////////////////////////////////////////
  if ($problemQ->getRowCount() == 0)
  {
    $problemQ->close();
    showMessage(_("No results found."), OPEN_MSG_INFO);
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
<form method="post" action="../medical/problem_search.php">
  <div>
<?php
  showInputHidden("search_type_problem", $_POST["search_type_problem"]);
  showInputHidden("search_text_problem", $_POST["search_text_problem"]);
  showInputHidden("page", $currentPageNmbr);
  showInputHidden("logical_problem", $_POST["logical_problem"]);
  showInputHidden("limit_problem", $_POST["limit_problem"]);
?>
  </div>
</form>

<?php
  // Printing result stats and page nav
  echo '<p><strong>' . sprintf(_("%d matches found."), $problemQ->getRowCount()) . "</strong></p>\n";

  $pageCount = $problemQ->getPageCount();
  showResultPages($currentPageNmbr, $pageCount);

  // Choose field
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
    $recordset[$row] = $row . OPEN_SEPARATOR . $problem->getIdProblem() . OPEN_SEPARATOR . $problem->getIdPatient() . OPEN_SEPARATOR . $aux . OPEN_SEPARATOR . localDate($problem->getOpeningDate()) . OPEN_SEPARATOR . localDate($problem->getClosingDate());
  } // end while
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);

  $tbody = array();
  foreach ($recordset as $arrKey => $arrValue)
  {
    $array = explode(OPEN_SEPARATOR, $arrValue, 6);

    $patQ = new Patient_Page_Query();
    $patQ->connect();
    if ($patQ->isError())
    {
      Error::query($patQ);
    }

    $numRows = $patQ->select($array[2]);
    if ($patQ->isError())
    {
      $patQ->close();
      Error::query($patQ);
    }

    if ($numRows)
    {
      $pat = $patQ->fetch();
      if ($patQ->isError())
      {
        $patQ->close();
        Error::fetch($patQ);
      }

      $row = $array[0] . '.';
      $row .= OPEN_SEPARATOR;

      $row .= '<a href="../medical/problem_view.php?key=' . $array[1] . '&amp;pat=' . $array[2] . '&amp;reset=Y">' . $pat->getSurname1() . " " . $pat->getSurname2() . ", " . $pat->getFirstName() . '</a>';
      $row .= "<br />\n" . $key . " " . $array[3] . "<br />\n";
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

  showTable($thead, $tbody, null, $options);

  showResultPages($currentPageNmbr, $pageCount);

  require_once("../shared/footer.php");
?>
