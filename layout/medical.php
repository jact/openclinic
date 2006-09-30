<?php
/**
 * medical.php
 *
 * Navbar to the Medical Records tab
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: medical.php,v 1.15 2006/09/30 17:24:06 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    HTML::para(
      HTML::strLink(
        HTML::strStart('img',
          array(
            'src' => '../images/logout.png',
            'width' => 96,
            'height' => 22,
            'alt' => _("logout"),
            'title' => _("logout")
          ),
          true
        ),
        '../shared/logout.php'
      )
      . '<br />'
      . '[ '
      . HTML::strLink($_SESSION["loginSession"], '../admin/user_edit_form.php',
        array(
          'key' => $_SESSION["userId"],
          'all' => 'Y'
        ),
        array('title' => _("manage your user account"))
      )
      . ' ]',
      array('class' => 'sideBarLogin')
    );
    HTML::rule();
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
    $array = array(array(_("Search Results"), array('class' => 'selected')));
    HTML::itemList($array, array('class' => 'subnavbar'));
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
          echo '<li class="selected">' . HTML::strTag('em', $arrValue);
          if ($nav == "social" || $nav == "history" || $nav == "problems" || $nav == "print")
          {
            include_once("../navbars/patient.php");
          }
          echo "</li>\n";
        }
        else
        {
          echo '<li>';
          HTML::link(HTML::strTag('em', $arrValue), '../medical/patient_view.php', array('key' => $arrKey));
          echo '</li>';
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
      'onclick' => "return popSecondary('../doc/index.php?tab=" . $tab . '&nav=' . $nav . "')"
    )
  );
  echo "</li>\n";

  echo "</ul><!-- End .linkList -->\n";
?>
