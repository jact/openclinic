<?php
/**
 * medical.php
 *
 * Navigation links to the Medical Records tab
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: medical.php,v 1.29 2013/01/13 16:28:24 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../lib/LastViewedPatient.php");
  require_once("../layout/component.php");

  $array = null;

  $array[] = HTML::link(_("Summary"), '../medical/index.php', null,
    $nav == 'summary' ? array('class' => 'selected') : null
  );

  $array[] = HTML::link(_("Search Patient"), '../medical/patient_search_form.php', null,
    ($nav == 'searchform' || $nav == 'search') ? array('class' => 'selected') : null
  );

  if ($nav == 'search')
  {
    //$array[] = array(HTML::link(_("Search Results"), '../medical/???.php', null, array('class' => 'selected')));
    $array[] = array(HTML::tag('span', _("Search Results"), array('class' => 'selected')));
  }

  if (defined("OPEN_DEMO") && !OPEN_DEMO)
  {
    $_viewedPatients = LastViewedPatient::get();
    if ($_viewedPatients)
    {
      foreach ($_viewedPatients as $arrKey => $arrValue)
      {
        if (isset($idPatient) && $arrKey == $idPatient)
        {
          $array[] = HTML::link(HTML::tag('em', $arrValue), '../medical/patient_view.php',
            array('id_patient' => $arrKey),
            /*$nav == 'social' ?*/ array('class' => 'selected') /*: null*/
          );
          if ($nav == "social" || $nav == "relatives" || $nav == "history" || $nav == "problems" || $nav == "print")
          {
            $array[] = patientLinks($idPatient, $nav);
          }
        }
        else
        {
          $array[] = HTML::link(HTML::tag('em', $arrValue), '../medical/patient_view.php',
            array('id_patient' => $arrKey)
          );
        }
      }
    }
  }
  else
  {
    if ($nav == "relatives" || $nav == "history" || $nav == "problems" || $nav == "print")
    {
      $array[] = patientLinks($idPatient, $nav);
    }
  }

  if ($_SESSION['auth']['is_administrative'])
  {
    $array[] = HTML::link(_("New Patient"), '../medical/patient_new_form.php', null,
      $nav == 'new' ? array('class' => 'selected') : null
    );
  }

  /*$array[] = HTML::link(_("Help"), '../doc/index.php',
    array(
      'tab' => $tab,
      'nav' => $nav
    ),
    array(
      'title' => _("Opens a new window"),
      'class' => 'popup'
    )
  );*/

  echo navigation($array);
  unset($array);
?>
