<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_search.php,v 1.7 2004/07/14 18:31:33 jact Exp $
 */

/**
 * problem_search.php
 ********************************************************************
 * Medical problems result set page
 ********************************************************************
 * Author: jact <jachavar@terra.es>
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
  require_once("../classes/Patient_Query.php");
  require_once("../classes/Problem_Query.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/search_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving post vars and scrubbing the data
  ////////////////////////////////////////////////////////////////////
  $currentPageNmbr = (isset($_POST["page"])) ? $_POST["page"] : 1;
  $searchType = $_POST["search_type_problem"];
  $logical = $_POST["logical_problem"];
  $limit = (isset($_POST["limit_problem"])) ? $_POST["limit_problem"] : 0;

  // remove slashes added by form post
  $searchText = stripslashes(trim($_POST["search_text_problem"]));
  // remove redundant whitespace
  $searchText = eregi_replace("[[:space:]]+", " ", $searchText);
  // transform string in array of strings
  $arraySearch = explodeQuoted($searchText);

  ////////////////////////////////////////////////////////////////////
  // Search database
  ////////////////////////////////////////////////////////////////////
  $problemQ = new Problem_Query();
  $problemQ->setItemsPerPage(OPEN_ITEMS_PER_PAGE);
  $problemQ->connect();
  if ($problemQ->isError())
  {
    showQueryError($problemQ);
  }

  $problemQ->search($searchType, $arraySearch, $currentPageNmbr, $logical, $limit);
  if ($problemQ->isError())
  {
    $problemQ->close();
    showQueryError($problemQ);
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
<form method="post" action="../medical/problem_search.php">
  <div>
<?php
  showInputHidden("search_type_problem", $_POST["search_type_problem"]);
  showInputHidden("search_text_problem", $_POST["search_text_problem"]);
  showInputHidden("page", $currentPageNmbr);
  showInputHidden("logical_problem", $_POST["logical_problem"]);
  showInputHidden("limit_problem", $limit_problem);
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
  $recordset = null;
  while ($problem = $problemQ->fetch())
  {
    $row = $problemQ->getCurrentRow();
    eval("\$aux = $val;");
    $recordset[$row] = $row . "|" . $problem->getIdProblem() . "|" . $problem->getIdPatient() . "|" . $aux . "|" . $problem->getOpeningDate() . "|" . $problem->getClosingDate();
  }
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);

  $rowClass = "odd";
  foreach ($recordset as $arrKey => $arrValue)
  {
    $array = split("\|", $arrValue, 6);

    $patQ = new Patient_Query();
    $patQ->connect();
    if ($patQ->isError())
    {
      $patQ->close();
      showQueryError($patQ);
    }

    $numRows = $patQ->select($array[2]);
    if ($patQ->isError())
    {
      $patQ->close();
      showQueryError($patQ);
    }

    if ($numRows)
    {
      $pat = $patQ->fetch();
      if ($patQ->isError())
      {
        $patQ->close();
        showFetchError($patQ);
      }
?>
    <tr class="<?php echo $rowClass; ?>">
      <td class="number">
        <?php echo $array[0]; ?>.
      </td>

      <td>
        <a href="../medical/problem_view.php?key=<?php echo $array[1]; ?>&amp;pat=<?php echo $array[2]; ?>&amp;reset=Y"><?php echo $pat->getSurname1() . " " . $pat->getSurname2() . ", " . $pat->getFirstName(); ?></a>
        <br />
        <?php
          echo $key . " " . $array[3] . "<br />\n";
          echo _("Opening Date") . ": " . $array[4];
          if ($array[5] != "")
          {
            echo "<br />" . _("Closing Date") . ": " . $array[5];
          }
        ?>
      </td>
    </tr>
<?php
      // swap row color
      ($rowClass == "odd") ? $rowClass = "even" : $rowClass = "odd";
    } // end if
  } // end while
  $patQ->freeResult();
  $patQ->close();
  unset($patQ);
  unset($recordset);
  unset($array);
?>
  </tbody>
</table>

<?php
  showResultPages($currentPageNmbr, $pageCount);

  require_once("../shared/footer.php");
?>
