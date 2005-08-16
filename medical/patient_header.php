<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_header.php,v 1.15 2005/08/16 15:12:52 jact Exp $
 */

/**
 * patient_header.php
 *
 * Contains showPatientHeader function
 *
 * Author: jact <jachavar@gmail.com>
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
    if ($patQ->isError())
    {
      Error::query($patQ);
    }

    $numRows = $patQ->select($idPatient);
    if ($patQ->isError())
    {
      $patQ->close();
      Error::query($patQ);
    }

    if ( !$numRows )
    {
      return false; // maybe return HTML::message(_("That patient does not exist."), OPEN_MSG_ERROR);
    }

    $pat = $patQ->fetch();
    if ($patQ->isError())
    {
      $patQ->close();
      Error::fetch($patQ);
    }

    $patQ->freeResult();
    $patQ->close();

    $patName = $pat->getFirstName() . " " . $pat->getSurname1() . " " . $pat->getSurname2();

    echo '<div id="patientHeader" class="clearfix">' . "\n";
    echo '<p>' . _("Patient") . ': ' . $patName . "</p>\n";
    echo '<p>' . _("Sex") . ': ' . ($pat->getSex() == 'V' ? _("Male") : _("Female")) . "</p>\n";
    echo '<p class="right">' . _("Age") . ': ' . $pat->getAge() . "</p>\n";
    echo "</div>\n";

    unset($patQ);
    unset($pat);

    return true;
  }
?>
