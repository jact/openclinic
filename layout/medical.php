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
 * @version   CVS: $Id: medical.php,v 1.16 2006/10/12 17:26:02 jact Exp $
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
            echo _patientLinks($idPatient, $nav);
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
      echo _patientLinks($idPatient, $nav);
    }
    echo "</li>\n"; // end searchform
  }

  if ($hasMedicalAdminAuth)
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
        'onclick' => "return popSecondary('../doc/index.php?tab=" . $tab . '&nav=' . $nav . "')"
      )
    )
  );

  echo "</ul><!-- End .linkList -->\n";

  /**
   * string _patientLinks(int $idPatient, string $nav)
   *
   * Returns a list with links about a patient
   *
   * @param int $idPatient
   * @param string $nav
   * @return string
   * @access private
   * @since 0.8
   */
  function _patientLinks($idPatient, $nav)
  {
    $linkList = array(
      "social" => array(_("Social Data"), "../medical/patient_view.php?key=" . $idPatient),
      //"preventive" => array(_("Datos Preventivos"), ""), // I don't know how implement it
      "history" => array(_("Clinic History"), "../medical/history_list.php?key=" . $idPatient),
      "problems" => array(_("Medical Problems Report"), "../medical/problem_list.php?key=" . $idPatient)
    );

    $array = null;
    foreach ($linkList as $key => $value)
    {
      if ($nav == $key)
      {
        $array[] = array($value[0], array('class' => 'selected'));
      }
      else
      {
        $array[] = HTML::strLink($value[0], $value[1]);
      }
    }
    unset($linkList);

    $array[] = ($nav == "print")
      ? array(_("Print Medical Record"), array('class' => 'selected'))
      : HTML::strLink(_("Print Medical Record"), '../medical/print_medical_record.php',
          array('key' => $idPatient),
          array('onclick' => "return popSecondary('../medical/print_medical_record.php?key=" . $idPatient . "')")
        );

    return HTML::strItemList($array, array('class' => 'subnavbar'));
  }
?>
