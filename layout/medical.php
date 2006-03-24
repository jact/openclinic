<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: medical.php,v 1.13 2006/03/24 20:28:54 jact Exp $
 */

/**
 * medical.php
 *
 * Navbar to the Medical Records tab
 *
 * @author jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    echo '<p class="sideBarLogin">';
    HTML::link('<img src="../images/logout.png" width="96" height="22" alt="' . _("logout") . '" title="' . _("logout") . '" />', '../shared/logout.php');
    echo '<br />';
    echo '[ ' . HTML::strLink($_SESSION["loginSession"], '../admin/user_edit_form.php',
      array(
        'key' => $_SESSION["userId"],
        'all' => 'Y'
      ),
      array('title' => _("manage your user account"))
    ) . " ]\n";
    echo "</p>\n";
    echo "<hr />\n";
  }

  echo '<ul class="linkList">';

  echo ($nav == "summary")
    ? '<li class="selected">' . _("Summary") . '</li>'
    : '<li>' . HTML::strLink(_("Summary"), '../medical/index.php') . '</li>';

  echo ($nav == "searchform")
    ? '<li class="selected">' . _("Search Patient")
    : '<li>' . HTML::strLink(_("Search Patient"), '../medical/patient_search_form.php');

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
          echo '<li>' . HTML::strLink('<em>' . $arrValue . '</em>', '../medical/patient_view.php', array('key' => $arrKey)) . '</li>';
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
      : '<li>' . HTML::strLink(_("New Patient"), '../medical/patient_new_form.php') . '</li>';
  }

  echo '<li>';
  HTML::link(_("Help"), '../doc/index.php',
    array(
      'tab' => $tab,
      'nav' => $nav
    ),
    array(
      'title' => _("Opens a new window"),
      'onclick' => "return popSecondary('../doc/index.php?tab=" . $tab . '&amp;nav=' . $nav . "')"
    )
  );
  echo "</li>\n";

  echo "</ul><!-- End .linkList -->\n";
?>
