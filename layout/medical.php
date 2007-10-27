<?php
/**
 * medical.php
 *
 * Navbar to the Medical Records tab
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: medical.php,v 1.23 2007/10/27 16:24:41 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @todo      remove <ul>, <li> (use HTML::*)
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../lib/LastViewedPatient.php");
  require_once("../layout/component.php");
  echo authInfo();

  echo '<ul class="linkList">';

  ($nav == "summary")
    ? HTML::tag('li', _("Summary"), array('class' => 'selected'))
    : HTML::tag('li', HTML::strLink(_("Summary"), '../medical/index.php'));

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
    $_viewedPatients = LastViewedPatient::get();
    if ($_viewedPatients)
    {
      echo "</li>\n"; // end searchform
      foreach ($_viewedPatients as $arrKey => $arrValue)
      {
        if (isset($idPatient) && $arrKey == $idPatient)
        {
          echo '<li class="selected">';
          if ($nav == "social")
          {
            HTML::tag('em', $arrValue);
          }
          else
          {
            HTML::link(HTML::strTag('em', $arrValue), '../medical/patient_view.php', array('id_patient' => $arrKey));
          }
          if ($nav == "social" || $nav == "relatives" || $nav == "history" || $nav == "problems" || $nav == "print")
          {
            echo patientLinks($idPatient, $nav);
          }
          echo "</li>\n";
        }
        else
        {
          echo '<li>';
          HTML::link(HTML::strTag('em', $arrValue), '../medical/patient_view.php', array('id_patient' => $arrKey));
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
    if ($nav == "relatives" || $nav == "history" || $nav == "problems" || $nav == "print")
    {
      echo patientLinks($idPatient, $nav);
    }
    echo "</li>\n"; // end searchform
  }

  if (isset($hasMedicalAdminAuth) && $hasMedicalAdminAuth)
  {
    ($nav == "new")
      ? HTML::tag('li', _("New Patient"), array('class' => 'selected'))
      : HTML::tag('li', HTML::strLink(_("New Patient"), '../medical/patient_new_form.php'));
  }

  HTML::tag('li',
    HTML::strLink(_("Help"), '../doc/index.php',
      array(
        'tab' => $tab,
        'nav' => $nav
      ),
      array(
        'title' => _("Opens a new window"),
        'class' => 'popup'
      )
    )
  );

  echo "</ul><!-- End .linkList -->\n";
?>
