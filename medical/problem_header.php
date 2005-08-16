<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: problem_header.php,v 1.17 2005/08/16 15:12:52 jact Exp $
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
   * @see fieldPreview
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

    echo '<div id="problemHeader" class="clearfix">' . "\n";
    echo '<p>' . _("Wording") . ': ' . fieldPreview($problem->getWording()) . "</p>\n";
    echo '<p class="right">' . _("Opening Date") . ': ' . I18n::localDate($problem->getOpeningDate()) . "</p>\n";
    echo '<p class="right">' . _("Last Update Date") . ': ' . I18n::localDate($problem->getLastUpdateDate()) . "</p>\n";
    echo "</div>\n";

    unset($problemQ);
    unset($problem);

    return true;
  }
?>
