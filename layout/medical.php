<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: medical.php,v 1.4 2004/08/09 10:03:13 jact Exp $
 */

/**
 * medical.php
 ********************************************************************
 * Navbar to the Medical Records tab
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    echo '<div class="sideBarLogin">';
    echo '<a href="../shared/logout.php"><img src="../images/logout.png" width="96" height="22" alt="logout" title="logout" /></a>';
    echo '<br />';
    echo '[ <a href="../admin/user_edit_form.php?key=' . $_SESSION["userId"] . '&amp;reset=Y&amp;all=Y" title="' . _("manage your user account") . '">' . $_SESSION["loginSession"] . "</a> ]\n";
    echo "</div>\n";
    echo "<hr />\n";
  }

  echo '<div class="linkList">';

  echo ($nav == "summary")
    ? '<span class="selected">' . _("Summary") . '</span>'
    : '<a href="../medical/index.php">' . _("Summary") . '</a>';
  echo "<span class='noPrint'> | </span>\n";

  echo ($nav == "searchform")
    ? '<span class="selected">' . _("Search Patient") . '</span>'
    : '<a href="../medical/patient_search_form.php">' . _("Search Patient") . '</a>';
  echo "<span class='noPrint'> | </span>\n";

  if ($nav == "search")
  {
    echo '<span class="selected subnavbar">' . _("Search Results") . "</span>\n";
  }

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    if (isset($_SESSION["visitedPatients"]))
    {
      foreach ($_SESSION["visitedPatients"] as $arrKey => $arrValue)
      {
        if (isset($idPatient) && $arrKey == $idPatient)
        {
          echo '<span class="selected"><em>' . $arrValue . "</em></span>\n";
          if ($nav == "social" || $nav == "history" || $nav == "problems" || $nav == "print")
          {
            include_once("../navbars/patient.php");
          }
        }
        else
        {
          echo '<a href="../medical/patient_view.php?key=' . $arrKey . '"><em>' . $arrValue . '</em></a>';
          echo "<span class='noPrint'> | </span>\n";
        }
      }
    }
  }
  else
  {
    if ($nav == "social" || $nav == "history" || $nav == "problems" || $nav == "print")
    {
      include_once("../navbars/patient.php");
    }
  }

  if ($hasMedicalAdminAuth)
  {
    echo ($nav == "new")
      ? '<span class="selected">' . _("New Patient") . '</span>'
      : '<a href="../medical/patient_new_form.php?reset=Y">' . _("New Patient") . '</a>';
    echo "<span class='noPrint'> | </span>\n";
  }
?>

  <a href="../doc/index.php?tab=<?php echo $tab; ?>&amp;nav=<?php echo $nav; ?>" title="<?php echo _("Opens a new window"); ?>" onclick="return popSecondary('../doc/index.php?tab=<?php echo $tab; ?>&amp;nav=<?php echo $nav; ?>')" onkeypress="return popSecondary('../doc/index.php?tab=<?php echo $tab; ?>&amp;nav=<?php echo $nav; ?>')"><?php echo _("Help"); ?></a>
</div><!-- End .linkList -->
