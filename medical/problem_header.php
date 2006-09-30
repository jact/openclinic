<?php
/**
 * problem_header.php
 *
 * Contains showProblemHeader function
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_header.php,v 1.21 2006/09/30 17:18:48 jact Exp $
 * @author    jact <jachavar@gmail.com>
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

    if ( !$problemQ->select($idProblem) )
    {
      return false; // maybe return HTML::message(_("That medical problem does not exist."), OPEN_MSG_ERROR);
    }

    $problem = $problemQ->fetch();
    if ( !$problem )
    {
      $problemQ->close();
      Error::fetch($problemQ);
    }

    $problemQ->freeResult();
    $problemQ->close();

    HTML::start('div', array('id' => 'problemHeader', 'class' => 'clearfix'));
    HTML::para(_("Wording") . ': ' . fieldPreview($problem->getWording()));
    HTML::para(
      _("Opening Date") . ': ' . I18n::localDate($problem->getOpeningDate()),
      array('class' => 'right')
    );
    HTML::para(
      _("Last Update Date") . ': ' . I18n::localDate($problem->getLastUpdateDate()),
      array('class' => 'right')
    );
    HTML::end('div');

    unset($problemQ);
    unset($problem);

    return true;
  }
?>
