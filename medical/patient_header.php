<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_header.php,v 1.7 2004/09/22 18:21:22 jact Exp $
 */

/**
 * patient_header.php
 ********************************************************************
 * Contains showPatientHeader function
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../classes/Patient_Query.php");

  /**
   * bool showPatientHeader(int $idPatient)
   ********************************************************************
   * Draws a header with patient information.
   ********************************************************************
   * @param int $idPatient key of patient to show header
   * @return boolean false if patient does not exist, true otherwise
   * @access public
   * @todo suppress table
   */
  function showPatientHeader($idPatient)
  {
    $patQ = new Patient_Query();
    $patQ->connect();
    if ($patQ->isError())
    {
      showQueryError($patQ);
    }

    $numRows = $patQ->select($idPatient);
    if ($patQ->isError())
    {
      $patQ->close();
      showQueryError($patQ);
    }

    if ( !$numRows )
    {
      return false; // maybe return showMessage(_("That patient does not exist."), OPEN_MSG_ERROR);
    }

    $pat = $patQ->fetch();
    if ($patQ->isError())
    {
      $patQ->close();
      showFetchError($patQ);
    }

    $patQ->freeResult();

    $patName = $pat->getFirstName() . " " . $pat->getSurname1() . " " . $pat->getSurname2();
?>

    <table width="100%">
      <tr>
        <td><?php echo _("Patient") . ': ' . $patName; ?></td>

        <td><?php echo _("Sex") . ': ' . ($pat->getSex() == 'V' ? _("Male") : _("Female")); ?></td>

        <td class="right"><?php echo _("Age") . ': ' . $pat->getAge(); ?></td>
      </tr>
    </table>

<?php
    unset($patQ);
    unset($pat);

    return true;
  }
?>
