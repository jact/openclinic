<?php
/**
 * patient_header.php
 *
 * Contains showPatientHeader function
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_header.php,v 1.19 2006/09/30 17:13:07 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../classes/Patient_Page_Query.php");

  /**
   * bool showPatientHeader(int $idPatient)
   *
   * Draws a header with patient information.
   *
   * @param int $idPatient key of patient to show header
   * @return boolean false if patient does not exist, true otherwise
   * @access public
   */
  function showPatientHeader($idPatient)
  {
    $patQ = new Patient_Page_Query();
    $patQ->connect();

    if ( !$patQ->select($idPatient) )
    {
      return false; // maybe return HTML::message(_("That patient does not exist."), OPEN_MSG_ERROR);
    }

    $pat = $patQ->fetch();
    if ( !$pat )
    {
      $patQ->close();
      Error::fetch($patQ);
    }

    $patQ->freeResult();
    $patQ->close();

    $patName = $pat->getFirstName() . " " . $pat->getSurname1() . " " . $pat->getSurname2();

    HTML::start('div', array('id' => 'patientHeader', 'class' => 'clearfix'));
    HTML::para(_("Patient") . ': ' . $patName);
    HTML::para(_("Sex") . ': ' . ($pat->getSex() == 'V' ? _("Male") : _("Female")));
    HTML::para(_("Age") . ': ' . $pat->getAge(), array('class' => 'right'));
    HTML::end('div');

    unset($patQ);
    unset($pat);

    return true;
  }
?>
