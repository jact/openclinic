<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: medical.php,v 1.12 2006/03/15 20:28:12 jact Exp $
 */

/**
 * medical.php
 *
 * Navbar to the Medical Records tab
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    echo '<p class="sideBarLogin">';
    echo '<a href="../shared/logout.php"><img src="../images/logout.png" width="96" height="22" alt="logout" title="logout" /></a>';
    echo '<br />';
    echo '[ <a href="../admin/user_edit_form.php?key=' . $_SESSION["userId"] . '&amp;all=Y" title="' . _("manage your user account") . '">' . $_SESSION["loginSession"] . "</a> ]\n";
    echo "</p>\n";
    echo "<hr />\n";
  }

  echo '<ul class="linkList">';

  echo ($nav == "summary")
    ? '<li class="selected">' . _("Summary") . '</li>'
    : '<li><a href="../medical/index.php">' . _("Summary") . '</a></li>';

  echo ($nav == "searchform")
    ? '<li class="selected">' . _("Search Patient")
    : '<li><a href="../medical/patient_search_form.php">' . _("Search Patient") . '</a>';

  if ($nav == "search")
  {
    echo '<ul class="subnavbar">';
    echo '<li class="selected">' . _("Search Results") . "</li>\n";
    echo "</ul>\n";
  }

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    if (isset($_SESSION["visitedPatients"]))
    {
      echo "</li>\n"; // end searchform
      foreach ($_SESSION["visitedPatients"] as $arrKey => $arrValue)
      {
        if (isset($idPatient) && $arrKey == $idPatient)
        {
          echo '<li class="selected"><em>' . $arrValue . '</em>';
          if ($nav == "social" || $nav == "history" || $nav == "problems" || $nav == "print")
          {
            include_once("../navbars/patient.php");
          }
          echo "</li>\n";
        }
        else
        {
          echo '<li><a href="../medical/patient_view.php?key=' . $arrKey . '"><em>' . $arrValue . '</em></a></li>';
        }
      }
    }
    else
    {
      echo "</li>\n"; // end searchform
    }
  }
  else
  {
    if ($nav == "social" || $nav == "history" || $nav == "problems" || $nav == "print")
    {
      include_once("../navbars/patient.php");
    }
    echo "</li>\n"; // end searchform
  }

  if ($hasMedicalAdminAuth)
  {
    echo ($nav == "new")
      ? '<li class="selected">' . _("New Patient") . '</li>'
      : '<li><a href="../medical/patient_new_form.php">' . _("New Patient") . '</a></li>';
  }
?>

  <li><a href="../doc/index.php?tab=<?php echo $tab; ?>&amp;nav=<?php echo $nav; ?>" title="<?php echo _("Opens a new window"); ?>" onclick="return popSecondary('../doc/index.php?tab=<?php echo $tab; ?>&amp;nav=<?php echo $nav; ?>')" onkeypress="return popSecondary('../doc/index.php?tab=<?php echo $tab; ?>&amp;nav=<?php echo $nav; ?>')"><?php echo _("Help"); ?></a></li>
</ul><!-- End .linkList -->
