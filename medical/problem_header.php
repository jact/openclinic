<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_header.php,v 1.16 2005/08/03 17:40:19 jact Exp $
 */

/**
 * problem_header.php
 *
 * Contains showProblemHeader function
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../classes/Problem_Page_Query.php");
  require_once("../lib/misc_lib.php");

  /**
   * bool showProblemHeader(int $idProblem)
   *
   * Draws a header with medical problem information.
   *
   * @param int $idProblem key of medical problem to show header
   * @return boolean false if medical problem does not exist, true otherwise
   * @access public
   * @todo suppress table
   */
  function showProblemHeader($idProblem)
  {
    $problemQ = new Problem_Page_Query();
    $problemQ->connect();
    if ($problemQ->isError())
    {
      Error::query($problemQ);
    }

    $numRows = $problemQ->select($idProblem);
    if ($problemQ->isError())
    {
      $problemQ->close();
      Error::query($problemQ);
    }

    if ( !$numRows )
    {
      return false; // maybe return HTML::message(_("That medical problem does not exist."), OPEN_MSG_ERROR);
    }

    $problem = $problemQ->fetch();
    if ($problemQ->isError())
    {
      $problemQ->close();
      Error::fetch($problemQ);
    }

    $problemQ->freeResult();
    $problemQ->close();
?>

    <table width="100%">
      <tr>
        <td><?php echo _("Wording") . ': ' . fieldPreview($problem->getWording()); ?></td>

        <td class="right"><?php echo _("Opening Date") . ': ' . I18n::localDate($problem->getOpeningDate()); ?></td>

        <td class="right"><?php echo _("Last Update Date") . ': ' . I18n::localDate($problem->getLastUpdateDate()); ?></td>
      </tr>
    </table>

<?php
    unset($problemQ);
    unset($problem);

    return true;
  }
?>
