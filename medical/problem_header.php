<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_header.php,v 1.10 2004/10/17 14:57:03 jact Exp $
 */

/**
 * problem_header.php
 ********************************************************************
 * Contains showProblemHeader function
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['PATH_TRANSLATED'])
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../classes/Problem_Query.php");
  require_once("../lib/misc_lib.php");

  /**
   * bool showProblemHeader(int $idProblem)
   ********************************************************************
   * Draws a header with medical problem information.
   ********************************************************************
   * @param int $idProblem key of medical problem to show header
   * @return boolean false if medical problem does not exist, true otherwise
   * @access public
   * @todo suppress table
   */
  function showProblemHeader($idProblem)
  {
    $problemQ = new Problem_Query();
    $problemQ->connect();
    if ($problemQ->isError())
    {
      showQueryError($problemQ);
    }

    $numRows = $problemQ->select($idProblem);
    if ($problemQ->isError())
    {
      $problemQ->close();
      showQueryError($problemQ);
    }

    if ( !$numRows )
    {
      return false; // maybe return showMessage(_("That medical problem does not exist."), OPEN_MSG_ERROR);
    }

    $problem = $problemQ->fetch();
    if ($problemQ->isError())
    {
      $problemQ->close();
      showFetchError($problemQ);
    }

    $problemQ->freeResult();
    $problemQ->close();
?>

    <table width="100%">
      <tr>
        <td><?php echo _("Wording") . ': ' . fieldPreview($problem->getWording()); ?></td>

        <td class="right"><?php echo _("Opening Date") . ': ' . localDate($problem->getOpeningDate()); ?></td>

        <td class="right"><?php echo _("Last Update Date") . ': ' . localDate($problem->getLastUpdateDate()); ?></td>
      </tr>
    </table>

<?php
    unset($problemQ);
    unset($problem);

    return true;
  }
?>
